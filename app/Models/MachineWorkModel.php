<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class MachineWorkModel extends AbstractModel
{
  protected function getTableName()
  {
    return 'machine_work';
  }

  protected function getFindQuery($filters)
  {
    $id = LaravelUtils::arrVal($filters, $this->getId());
    $limit = LaravelUtils::arrVal($filters, 'limit');
    $offset = LaravelUtils::arrVal($filters, 'offset');
    $count = LaravelUtils::arrVal($filters, 'count');
    $selectId = LaravelUtils::arrVal($filters, 'selectId');
    $ids = LaravelUtils::arrVal($filters, 'ids');
    $sort = LaravelUtils::arrVal($filters, 'sort');
    $startDateStr = LaravelUtils::arrVal($filters, 'startDate');
    $endDateStr = LaravelUtils::arrVal($filters, 'endDate');
    $machineId = LaravelUtils::arrVal($filters, 'machineId');
    $lineId = LaravelUtils::arrVal($filters, 'lineId');

    $query = \DB::table($this->getTableName().' as mw');
    $query = $query->select(array('mw.*', 'm.model as machine_model', 'l.line_name as line_name'));
    $query = $query->leftJoin('machines as m', 'm.id', '=', 'mw.machine_id');
    $query = $query->leftJoin('machine_lines as l', 'l.id', '=', 'mw.line_id'); 
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where('mw.'.$this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn('mw.'.$this->getId(), $ids);
    }
    if (!empty($startDateStr))
    {
      $query = $query->where('mw.work_date', '>=', $startDateStr);
    }
    if (!empty($endDateStr))
    {
      $query = $query->where('mw.work_date', '<=', $endDateStr);
    }
    if (LaravelUtils::isInt($machineId))
    {
      $query = $query->where('mw.machine_id', $machineId);
    }
    if (LaravelUtils::isInt($lineId))
    {
      $query = $query->where('mw.line_id', $lineId);
    }
    if ($selectId)
    {
      $query = $query->select('mw.'.$this->getId());
    }

    if (LaravelUtils::isArray($sort))
    {
      foreach($sort as $field => $dir)
      {
        $column = '';
        switch($field)
        {
          case 'id':
            $column = 'id';
            break;
          case 'machineId':
            $column = 'machine_id';
            break;
          case 'lineId':
            $column = 'line_id';
            break;
          case 'workDate':
            $column = 'work_date';
            break;
          case 'description':
            $column = 'description';
            break;
          case 'labourHours':
            $column = 'labour_hours';
            break;
        }
        if (!empty($column))
        {
          $query = $query->orderBy('mw.'.$column, $dir);
        }
      }
    }

    if ($count)
    {
      return array($query->count());
    }
    if (LaravelUtils::isInt($limit) && LaravelUtils::isInt($offset))
    {
      $query = $query->skip($offset)->take($limit);
    }

    return $query->get();
  }

  public function findById($id)
  {
    $filters = array();
    $filters[$this->getId()] = $id;
    $res = $this->find($filters);
    if (!empty($res))
    {
      return $res[0];
    }
    return new MachineWork();
  }

  protected function extractRow($row)
  {
    if (!isset($row))
    {
      return new MachineWork();
    }
    if (LaravelUtils::isInt($row))
    {
      return $row;
    }
    $props = get_object_vars($row);
    if (count($props) == 1)
    {
      reset($props);
      $key = key($props);
      return $props[$key];
    }

    $id = $row->id;
    $machineId = $row->machine_id;
    $lineId = $row->line_id;
    $workDate = date_create_from_format('Y-m-d', $row->work_date);
    $description = $row->description;
    $labourHours = $row->labour_hours;
    $machineModel = $row->machine_model;
    $lineName = $row->line_name;

    $res = new MachineWork();
    $res->setId($id);
    $res->setMachineId($machineId);
    $res->setLineId($lineId);
    $res->setWorkDate($workDate);
    $res->setDescription($description);
    $res->setLabourHours($labourHours);
    $res->setMachineModel($machineModel);
    $res->setLineName($lineName);
    return $res;
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['machine_id'] = $obj->getMachineId();
      $data['line_id'] = $obj->getLineId();
      $data['work_date'] = date_format($obj->getWorkDate(), 'Y-m-d');
      $data['description'] = $obj->getDescription();
      $data['labour_hours'] = $obj->getLabourHours();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['machine_id'] = $obj->getMachineId();
      $data['line_id'] = $obj->getLineId();
      $data['work_date'] = date_format($obj->getWorkDate(), 'Y-m-d');
      $data['description'] = $obj->getDescription();
      $data['labour_hours'] = $obj->getLabourHours();
    }
    return $data;
  }

}

