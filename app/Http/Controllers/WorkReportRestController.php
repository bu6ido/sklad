<?php

namespace App\Http\Controllers;

use App\Models\StoreSettings;
use App\Models\StoreSettingsModel;
use App\Models\GroupModel;
use App\Libraries\LaravelUtils;

class WorkReportRestController extends AbstractReportRestController
{
  protected $settingsModel;
  protected $groupModel;

  public function __construct()
  {
    //parent::__construct();

    $this->settingsModel = new StoreSettingsModel();
    $this->groupModel = new GroupModel();
  }

  protected function findSettings()
  {
    $filters = array();
    $result = $this->settingsModel->find($filters);
    if (!empty($result))
    {
      return $result[0];
    }
    return new StoreSettings();
  }

  protected function getFindFilters()
  {
    $filters = array();
    $filters['startDate'] = \Input::get('startDate');
    $filters['endDate'] = \Input::get('endDate');
    $filters['lineId'] = \Input::get('lineId');
    $filters['machineId'] = \Input::get('machineId');
    $filters['materialId'] = \Input::get('materialId');
    $filters['groupId'] = \Input::get('groupId');
    return $filters;
  }

  protected function getFindQuery($filters)
  {
    $startDate = LaravelUtils::arrVal($filters, 'startDate');
    $endDate = LaravelUtils::arrVal($filters, 'endDate');
    $lineId = LaravelUtils::arrVal($filters, 'lineId');
    $machineId = LaravelUtils::arrVal($filters, 'machineId');
    $materialId = LaravelUtils::arrVal($filters, 'materialId');
    $groupId = LaravelUtils::arrVal($filters, 'groupId');

    $query = \DB::table('machine_work_items as mwi');
    $query = $query->select(array('mwi.*', 'mw.work_date as work_date', 'mw.labour_hours as labour_hours',
				  'ma.model as machine_model', 'ma.machine_type as machine_type', 
				  'ma.fabric_number as machine_fabric_number', 'ma.inv_number as machine_inv_number',
				  'l.line_name as line_name', 'm.material_name as material_name', 'm.price as material_price', 
				  'g.id as group_id', 'g.group_name as group_name'));
    $query = $query->leftJoin('machine_work as mw', 'mw.id', '=', 'mwi.machine_work_id');
    $query = $query->leftJoin('machines as ma', 'ma.id', '=', 'mw.machine_id');
    $query = $query->leftJoin('machine_lines as l', 'l.id', '=', 'mw.line_id');
    $query = $query->leftJoin('materials as m', 'm.id', '=', 'mwi.material_id');
    $query = $query->leftJoin('groups as g', 'g.id', '=', 'm.group_id');
    if (!empty($startDate))
    {
      $query = $query->where('mw.work_date', '>=', $startDate);
    }
    if (!empty($endDate))
    {
      $query = $query->where('mw.work_date', '<=', $endDate);
    }
    if (LaravelUtils::isInt($lineId))
    {
      $query = $query->where('mw.line_id', '=', $lineId);
    }
    if (LaravelUtils::isInt($machineId))
    {
      $query = $query->where('mw.machine_id', '=', $machineId);
    }
    if (LaravelUtils::isInt($materialId))
    {
      $query = $query->where('mwi.material_id', '=', $materialId);
    }
    if (LaravelUtils::isInt($groupId))
    {
      $groups = $this->groupModel->findAllGroups($groupId);

      $query = $query->whereIn('m.group_id', $groups);
    }
    $query = $query->orderBy('mw.work_date');
    $query = $query->orderBy('ma.model');
    $query = $query->orderBy('mw.line_id');
    $query = $query->orderBy('m.material_name');

    $settings = $this->findSettings();
    $machineWorks = array();

    $result = $query->get();
    $totalQuantity = 0;
    $totalSum = 0;
    $totalLabourHours = 0;
    $totalLabourSum = 0;
    if (!empty($result))
    {
      foreach ($result as &$item)
      {
        $item->sum = $item->used_quantity * $item->material_price;
	$item->labour_sum = $settings->getLabourPrice() * $item->labour_hours;

        $totalQuantity += $item->used_quantity;
        $totalSum += $item->sum;

        if (!in_array($item->machine_work_id, $machineWorks))
        {
          $machineWorks []= $item->machine_work_id;
          $totalLabourHours += $item->labour_hours;
        }
      }
    }
    $totalLabourSum = $settings->getLabourPrice() * $totalLabourHours;

    $total = new \StdClass();
    $total->work_date = 'Общо:';
    $total->machine_model = '';
    $total->machine_type = '';
    $total->machine_fabric_number = '';
    $total->machine_inv_number = '';
    $total->line_name = '';
    $total->material_name = '';
    $total->material_price = '';
    $total->used_quantity = $totalQuantity;
    $total->sum = $totalSum;
    $total->labour_hours = $totalLabourHours;
    $total->labour_sum = $totalLabourSum;
    $total->status = 'inf';
    $result []= $total;

    return $result;
  }

  protected function getExportName()
  {
    return 'report_work';
  }

  protected function getExportSheetName()
  {
    return 'Изразходвани материали';
  }

  protected function getExportRow($row)
  {
    return array('Дата' => $row->work_date,
		 'Машина' => $row->machine_model,
		 'Тип на машина' => $row->machine_type,
		 'Фабричен №' => $row->machine_fabric_number,
		 'Инвентарен №' => $row->machine_inv_number,
		 'Линия' => $row->line_name,
		 'Материал' => $row->material_name,
		 'Цена(лв.)' => $row->material_price,
		 'Количество' => $row->used_quantity,
		 'Стойност(лв.)' => $row->sum,
		 'Труд(ч)' => $row->labour_hours,
		 'Труд(лв.)' => $row->labour_sum);
  }
  public function index()
  {
    return \View::make('report_work');
  }
}

