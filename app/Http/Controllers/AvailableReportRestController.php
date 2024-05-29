<?php

namespace App\Http\Controllers;

use App\Models\GroupModel;
use App\Libraries\LaravelUtils;

class AvailableReportRestController extends AbstractReportRestController
{
  protected $groupModel;

  public function __construct()
  {
    //parent::__construct();

    $this->groupModel = new GroupModel();
  }

  protected function getFindFilters()
  {
    $filters = array();
    $filters['toDate'] = \Input::get('toDate');
    $filters['materialId'] = \Input::get('materialId');
    $filters['groupId'] = \Input::get('groupId');
    return $filters;
  }

  protected function getFindQuery($filters)
  {
    $toDate = LaravelUtils::arrVal($filters, 'toDate');
    $materialId = LaravelUtils::arrVal($filters, 'materialId');
    $groupId = LaravelUtils::arrVal($filters, 'groupId');

/* select m.*, (select coalesce(sum(di.quantity), 0) from 
ery_items di left join deliveries d on (d.id = di.delivery_id) where (di.material_id = m.id) and (d.delivery_date <= '2015-08-30')) - (select coalesce(sum(mwi.used_quantity), 0) from machine_work_items mwi left join machine_work mw on (mw.id = mwi.machine_work_id) where (mwi.material_id = m.id) and (mw.work_date <= '2015-08-30')) as mquantity from materials m where (1=1) order by m.material_name;
*/
    $queryDelivered = \DB::table('delivery_items as di');
    $queryDelivered = $queryDelivered->selectRaw('coalesce(sum(di.quantity), 0)');
    $queryDelivered = $queryDelivered->leftJoin('deliveries as d', 'd.id', '=', 'di.delivery_id');
    $queryDelivered = $queryDelivered->where('di.material_id', \DB::raw('m.id'));
    if (!empty($toDate))
    {
      $queryDelivered = $queryDelivered->where('d.delivery_date', '<=', $toDate);
    }

    $queryWork = \DB::table('machine_work_items as mwi');
    $queryWork = $queryWork->selectRaw('coalesce(sum(mwi.used_quantity), 0)');
    $queryWork = $queryWork->leftJoin('machine_work as mw', 'mw.id', '=', 'mwi.machine_work_id');
    $queryWork = $queryWork->where('mwi.material_id', \DB::raw('m.id'));
    if (!empty($toDate))
    {
      $queryWork = $queryWork->where('mw.work_date', '<=', $toDate);
    }

    $query = \DB::table('materials as m');
    $query = $query->select(array('m.*', \DB::raw('('.$queryDelivered->toSql().') - ('.$queryWork->toSql().') as mquantity'),
				  'g.group_name as group_name'));
    $query = $query->leftJoin('groups as g', 'g.id', '=', 'm.group_id');
    $query = $query->mergeBindings($queryDelivered);
    $query = $query->mergeBindings($queryWork);

    if (LaravelUtils::isInt($materialId))
    {
      $query = $query->where('m.id', '=', $materialId);
    }
    if (LaravelUtils::isInt($groupId))
    {
      $groups = $this->groupModel->findAllGroups($groupId);

      $query = $query->whereIn('m.group_id', $groups);
    }
    $query = $query->orderBy('m.material_name');

    $result = $query->get();
    $totalQuantity = 0;
    $totalSum = 0;
    if (!empty($result))
    {
      foreach ($result as &$item)
      {
        $item->sum = $item->mquantity * $item->price;

        $totalQuantity += $item->mquantity;
        $totalSum += $item->sum;
      }
    }

    $total = new \StdClass();
    $total->material_name = 'Общо:';
    $total->group_name = '';
    $total->price = '';
    $total->mquantity = $totalQuantity;
    $total->sum = $totalSum;
    $total->status = 'inf';
    $result []= $total;

    return $result;
  }

  protected function getExportName()
  {
    return 'report_available';
  }

  protected function getExportSheetName()
  {
    return 'Налични материали';
  }

  protected function getExportRow($row)
  {
    return array('Материал' => $row->material_name,
		 'Група' => $row->group_name,
		 'Цена(лв.)' => $row->price,
		 'Налично к-во' => $row->mquantity,
		 'Стойност(лв.)' => $row->sum);
  }

  public function index()
  {
    return \View::make('report_available');
  }
}

