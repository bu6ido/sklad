<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class StoreSettingsModel extends AbstractModel
{
  protected function getTableName()
  {
    return 'store_settings';
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

    $query = \DB::table($this->getTableName());
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where($this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn($this->getId(), $ids);
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
          case 'labourPrice':
            $column = 'labour_price';
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
      return new StoreSettings();
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
    $labourPrice = $row->labour_price;

    $res = new StoreSettings();
    $res->setId($id);
    $res->setLabourPrice($labourPrice);
    return $res;
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['labour_price'] = $obj->getLabourPrice();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['labour_price'] = $obj->getLabourPrice();
    }
    return $data;
  }

}

