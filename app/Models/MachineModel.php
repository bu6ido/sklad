<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class MachineModel extends AbstractModel
{
  protected function getTableName()
  {
    return 'machines';
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
    $systemNumber = LaravelUtils::arrVal($filters, 'systemNumber');
    $lineId = LaravelUtils::arrVal($filters, 'lineId');

    $query = \DB::table($this->getTableName().' as m');
    $query = $query->select(array('m.*', 'l.line_name as line_name'));
    $query = $query->leftJoin('machine_lines as l', 'l.id', '=', 'm.line_id');
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where('m.'.$this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn('m.'.$this->getId(), $ids);
    }
    if (LaravelUtils::isInt($systemNumber))
    {
      $query = $query->where('m.system_number', $systemNumber);
    }
    if (LaravelUtils::isInt($lineId))
    {
      $query = $query->where('m.line_id', $lineId);
    }
    if ($selectId)
    {
      $query = $query->select('m.'.$this->getId());
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
          case 'systemNumber':
            $column = 'system_number';
            break;
          case 'model':
            $column = 'model';
            break;
          case 'machineType':
            $column = 'machine_type';
            break;
          case 'lineId':
            $column = 'line_id';
            break;
          case 'fabricNumber':
            $column = 'fabric_number';
            break;
          case 'invNumber':
            $column = 'inv_number';
            break;
          case 'dateBuy':
            $column = 'date_buy';
            break;
          case 'price':
            $column = 'price';
            break;
        }
        if (!empty($column))
        {
          $query = $query->orderBy('m.'.$column, $dir);
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
    return new Machine();
  }

  protected function extractRow($row)
  {
    if (!isset($row))
    {
      return new Machine();
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
    $systemNumber = $row->system_number;
    $model = $row->model;
    $machineType = $row->machine_type;
    $lineId = $row->line_id;
    $fabricNumber = $row->fabric_number;
    $invNumber = $row->inv_number;
    $dateBuy = date_create_from_format('Y-m-d', $row->date_buy);
    $price = $row->price;
    $lineName = $row->line_name;

    $res = new Machine();
    $res->setId($id);
    $res->setSystemNumber($systemNumber);
    $res->setModel($model);
    $res->setMachineType($machineType);
    $res->setLineId($lineId);
    $res->setFabricNumber($fabricNumber);
    $res->setInvNumber($invNumber);
    $res->setDateBuy($dateBuy);
    $res->setPrice($price);
    $res->setLineName($lineName);
    return $res;
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['system_number'] = $obj->getSystemNumber();
      $data['model'] = $obj->getModel();
      $data['machine_type'] = $obj->getMachineType();
      $data['line_id'] = $obj->getLineId();
      $data['fabric_number'] = $obj->getFabricNumber();
      $data['inv_number'] = $obj->getInvNumber();
      $data['date_buy'] = date_format($obj->getDateBuy(), 'Y-m-d');
      $data['price'] = $obj->getPrice();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['system_number'] = $obj->getSystemNumber();
      $data['model'] = $obj->getModel();
      $data['machine_type'] = $obj->getMachineType();
      $data['line_id'] = $obj->getLineId();
      $data['fabric_number'] = $obj->getFabricNumber();
      $data['inv_number'] = $obj->getInvNumber();
      $data['date_buy'] = date_format($obj->getDateBuy(), 'Y-m-d');
      $data['price'] = $obj->getPrice();
    }
    return $data;
  }

}

