<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class DeliveryModel extends AbstractModel
{
  protected function getTableName()
  {
    return 'deliveries';
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
    $description = LaravelUtils::arrVal($filters, 'description');
    $startDateStr = LaravelUtils::arrVal($filters, 'startDate');
    $endDateStr = LaravelUtils::arrVal($filters, 'endDate');

    $query = \DB::table($this->getTableName());
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where($this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn($this->getId(), $ids);
    }
    if (!empty($description))
    {
      $query = $query->where('description', 'like', '%'.$description.'%');
    }
    if (!empty($startDateStr))
    {
      $query = $query->where('delivery_date', '>=', $startDateStr);
    }
    if (!empty($endDateStr))
    {
      $query = $query->where('delivery_date', '<=', $endDateStr);
    }
    if ($selectId)
    {
      $query = $query->select($this->getId());
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
	  case 'deliveryDate':
	    $column = 'delivery_date';
	    break;
          case 'description':
            $column = 'description';
            break;
        }
        if (!empty($column))
        {
          $query = $query->orderBy($column, $dir);
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

  protected function extractRow($row)
  {
    if (!isset($row))
    {
      return new Delivery();
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
    $deliveryDate = date_create_from_format('Y-m-d', $row->delivery_date);
    $description = $row->description;

    $res = new Delivery();
    $res->setId($id);
    $res->setDeliveryDate($deliveryDate);
    $res->setDescription($description);
    return $res;
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['delivery_date'] = date_format($obj->getDeliveryDate(), 'Y-m-d');
      $data['description'] = $obj->getDescription();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['delivery_date'] = date_format($obj->getDeliveryDate(), 'Y-m-d');
      $data['description'] = $obj->getDescription();
    }
    return $data;
  }

}

