<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class MachineWorkItemModel extends AbstractModel
{
  protected $materialModel;

  public function __construct()
  {
    //parent::__construct();
    
    $this->materialModel = new MaterialModel();
  }

  protected function getTableName()
  {
    return 'machine_work_items';
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
    $machineWorkId = LaravelUtils::arrVal($filters, 'machineWorkId');

    $query = \DB::table($this->getTableName().' as mwi');
    $query = $query->select(array('mwi.*', 'm.material_name as material_name'));
    $query = $query->leftJoin('materials as m', 'm.id', '=', 'mwi.material_id');
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where('mwi.'.$this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn('mwi.'.$this->getId(), $ids);
    }
    if (LaravelUtils::isInt($machineWorkId))
    {
      $query = $query->where('mwi.machine_work_id', $machineWorkId);
    }
    if ($selectId)
    {
      $query = $query->select('mwi.'.$this->getId());
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
          case 'machineWorkId':
            $column = 'machine_work_id';
            break;
          case 'materialId':
            $column = 'material_id';
            break;
          case 'usedQuantity':
            $column = 'used_quantity';
            break;
        }
        if (!empty($column))
        {
          $query = $query->orderBy('mwi.'.$column, $dir);
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
      return new MachineWorkItem();
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
    $machineWorkId = $row->machine_work_id;
    $materialId = $row->material_id;
    $usedQuantity = $row->used_quantity;
    $materialName = $row->material_name;

    $res = new MachineWorkItem();
    $res->setId($id);
    $res->setMachineWorkId($machineWorkId);
    $res->setMaterialId($materialId);
    $res->setUsedQuantity($usedQuantity);
    $res->setMaterialName($materialName);
    return $res;
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
    return new MachineWorkItem();
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['machine_work_id'] = $obj->getMachineWorkId();
      $data['material_id'] = $obj->getMaterialId();
      $data['used_quantity'] = $obj->getUsedQuantity();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['machine_work_id'] = $obj->getMachineWorkId();
      $data['material_id'] = $obj->getMaterialId();
      $data['used_quantity'] = $obj->getUsedQuantity();
    }
    return $data;
  }

  public function deleteByWork($machineWorkId)
  {
    \DB::table($this->getTableName())
       ->where('machine_work_id', $machineWorkId)
       ->delete();
  }

  public function deleteByMaterial($materialId)
  {
    \DB::table($this->getTableName())
       ->where('material_id', $materialId)
       ->delete();
  }

  public function insertMultiple($items)
  {
    $data = array();
    foreach ($items as $mwi)
    {
      $materialId = $mwi->getMaterialId();
      if (!LaravelUtils::isInt($materialId))
      {
        $mat = new Material();
        $mat->setMaterialName($mwi->getMaterialName());
        $mat->setPrice($mwi->getPrice());
        $mat->setGroupId($mwi->getGroupId());
        $materialId = $this->materialModel->insert($mat);
        $mwi->setMaterialId($materialId);
      }
      $itemData = $this->getInsertData($mwi);
      $data []= $itemData;
    }
    \DB::table($this->getTableName())->insert($data);
  }
}

