<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class GroupModel extends AbstractModel
{
  protected function getTableName()
  {
    return 'groups';
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
    $groupName = LaravelUtils::arrVal($filters, 'groupName');
    $parentId = LaravelUtils::arrVal($filters, 'parentId');

    $query = \DB::table($this->getTableName().' as g');
    $query = $query->select(array('g.*', 'pg.group_name as parent_name'));
    $query = $query->leftJoin($this->getTableName().' as pg', 'pg.'.$this->getId(), '=', 'g.parent_id');
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where('g.'.$this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn('g.'.$this->getId(), $ids);
    }
    if (!empty($groupName))
    {
      $query = $query->where('g.group_name', 'like', '%'.$groupName.'%');
    }
    if (LaravelUtils::isInt($parentId))
    {
      $query = $query->where('g.parent_id', $parentId);
    }
    else
    if ($parentId == 'null')
    {
      $query = $query->whereNull('g.parent_id');
    }
    if ($selectId)
    {
      $query = $query->select('g.'.$this->getId());
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
          case 'groupName':
            $column = 'group_name';
            break;
          case 'parentId':
            $column = 'parent_id';
            break;
        }
        if (!empty($column))
        {
          $query = $query->orderBy('g.'.$column, $dir);
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
    return new Group();
  }

  protected function extractRow($row)
  {
    if (!isset($row))
    {
      return new Group();
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
    $groupName = $row->group_name;
    $parentId = $row->parent_id;
    $parentName = $row->parent_name;

    $res = new Group();
    $res->setId($id);
    $res->setGroupName($groupName);
    $res->setParentId($parentId);
    $res->setParentName($parentName);
    return $res;
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['group_name'] = $obj->getGroupName();
      $data['parent_id'] = $obj->getParentId();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['group_name'] = $obj->getGroupName();
      $data['parent_id'] = $obj->getParentId();
    }
    return $data;
  }

  public function findAllGroups($groupId)
  {
    $result = array((int) $groupId);
    $query = \DB::table($this->getTableName());
    $query = $query->select($this->getId());
    $query = $query->where('parent_id', $groupId);
    $sub = $query->get();
    foreach ($sub as $row)
    {
      $res = $this->findAllGroups($row->id);
      $result = array_merge($result, $res);
    }
    return $result;
  }

  public function deleteMultiple($ids)
  {
    \DB::table($this->getTableName())
       ->whereIn($this->getId(), $ids)
       ->delete();
  }
}

