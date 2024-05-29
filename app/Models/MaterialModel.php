<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class MaterialModel extends AbstractModel
{
  protected $groupModel;

  public function __construct()
  {
    //parent::__construct();
    
    $this->groupModel = new GroupModel();
  }

  protected function getTableName()
  {
    return 'materials';
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
    $materialName = LaravelUtils::arrVal($filters, 'materialName');
    $groupId = LaravelUtils::arrVal($filters, 'groupId');

    $query = \DB::table($this->getTableName().' as m');
    $query = $query->select(array('m.*', 'g.group_name as group_name'));
    $query = $query->leftJoin('groups as g', 'g.id', '=', 'm.group_id');
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where('m.'.$this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn('m.'.$this->getId(), $ids);
    }
    if (!empty($materialName))
    {
      $query = $query->where('m.material_name', 'like', '%'.$materialName.'%');
    }
    if (LaravelUtils::isInt($groupId))
    {
      $groups = $this->groupModel->findAllGroups($groupId);

      $query = $query->whereIn('m.group_id', $groups);
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
          case 'materialName':
            $column = 'material_name';
            break;
	  case 'price':
	    $column = 'price';
	    break;
	  case 'groupId':
	    $column = 'group_id';
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
    return new Material();
  }

  protected function extractRow($row)
  {
    if (!isset($row))
    {
      return new Material();
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
    $materialName = $row->material_name;
    $price = $row->price;
    $groupId = $row->group_id;
    $groupName = $row->group_name;

    $res = new Material();
    $res->setId($id);
    $res->setMaterialName($materialName);
    $res->setPrice($price);
    $res->setGroupId($groupId);
    $res->setGroupName($groupName);
    return $res;
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['material_name'] = $obj->getMaterialName();
      $data['price'] = $obj->getPrice();
      $data['group_id'] = $obj->getGroupId();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['material_name'] = $obj->getMaterialName();
      $data['price'] = $obj->getPrice();
      $data['group_id'] = $obj->getGroupId();
    }
    return $data;
  }

}

