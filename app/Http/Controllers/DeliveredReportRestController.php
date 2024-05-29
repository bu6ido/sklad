<?php

namespace App\Http\Controllers;

use App\Models\GroupModel;
use App\Libraries\LaravelUtils;

class DeliveredReportRestController extends AbstractReportRestController
{
  protected $groupModel;

  public function __construct()
  {
    //parent::_construct();

    $this->groupModel = new GroupModel();
  }

  protected function getFindFilters()
  {
    $filters = array();
    $filters['startDate'] = \Input::get('startDate');
    $filters['endDate'] = \Input::get('endDate');
    $filters['materialId'] = \Input::get('materialId');
    $filters['groupId'] = \Input::get('groupId');
    return $filters;
  }

  protected function getFindQuery($filters)
  {
    $startDate = LaravelUtils::arrVal($filters, 'startDate');
    $endDate = LaravelUtils::arrVal($filters, 'endDate');
    $materialId = LaravelUtils::arrVal($filters, 'materialId');
    $groupId = LaravelUtils::arrVal($filters, 'groupId');

    $query = \DB::table('delivery_items as di');
    $query = $query->select(array('di.*', 'd.delivery_date as delivery_date', 
				  'm.material_name as material_name', 'm.price as material_price', 
				  'g.id as group_id', 'g.group_name as group_name'));
    $query = $query->leftJoin('deliveries as d', 'd.id', '=', 'di.delivery_id');
    $query = $query->leftJoin('materials as m', 'm.id', '=', 'di.material_id');
    $query = $query->leftJoin('groups as g', 'g.id', '=', 'm.group_id');
    if (!empty($startDate))
    {
      $query = $query->where('d.delivery_date', '>=', $startDate);
    }
    if (!empty($endDate))
    {
      $query = $query->where('d.delivery_date', '<=', $endDate);
    }
    if (LaravelUtils::isInt($materialId))
    {
      $query = $query->where('di.material_id', '=', $materialId);
    }
    if (LaravelUtils::isInt($groupId))
    {
      $groups = $this->groupModel->findAllGroups($groupId);

      $query = $query->whereIn('m.group_id', $groups);
    }
    $query = $query->orderBy('d.delivery_date');
    $query = $query->orderBy('m.material_name');

    $result = $query->get();
    $totalQuantity = 0;
    $totalSum = 0;
    if (!empty($result))
    {
      foreach ($result as &$item)
      {
        $item->sum = $item->quantity * $item->material_price;

        $totalQuantity += $item->quantity;
        $totalSum += $item->sum;
      }
    }

    $total = new \StdClass();
    $total->delivery_date = 'Общо:';
    $total->material_name = '';
    $total->material_price = '';
    $total->quantity = $totalQuantity;
    $total->sum = $totalSum;
    $total->status = 'inf';
    $result []= $total;

    return $result;
  }

  protected function getExportName()
  {
    return 'report_delivered';
  }

  protected function getExportSheetName()
  {
    return 'Доставени материали';
  }

  protected function getExportRow($row)
  {
    return array('Дата на доставка' => $row->delivery_date, 
	         'Материал' => $row->material_name, 
		 'Цена(лв.)' => $row->material_price,
		 'Количество' => $row->quantity,
		 'Стойност(лв.)' => $row->sum);
  }

  public function index()
  {
    return \View::make('report_delivered');
  }
}

