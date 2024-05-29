<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class LineModel extends AbstractModel
{
  protected function getTableName()
  {
    return 'machine_lines';
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
    $lineName = LaravelUtils::arrVal($filters, 'lineName');

    $query = \DB::table($this->getTableName());
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where($this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn($this->getId(), $ids);
    }
    if (!empty($lineName))
    {
      $query = $query->where('line_name', 'like', '%'.$lineName.'%');
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
          case 'lineName':
            $column = 'line_name';
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
      return new Line();
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
    $lineName = $row->line_name;

    $res = new Line();
    $res->setId($id);
    $res->setLineName($lineName);
    return $res;
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['line_name'] = $obj->getLineName();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['line_name'] = $obj->getLineName();
    }
    return $data;
  }

}

