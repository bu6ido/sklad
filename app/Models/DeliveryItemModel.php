<?php

namespace App\Models;

use App\Libraries\LaravelUtils;

class DeliveryItemModel extends AbstractModel
{
  protected function getTableName()
  {
    return 'delivery_items';
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
    $deliveryId = LaravelUtils::arrVal($filters, 'deliveryId');

    $query = \DB::table($this->getTableName().' as di');
    $query = $query->select(array('di.*', 'm.material_name as material_name'));
    $query = $query->leftJoin('materials as m', 'm.id', '=', 'di.material_id');
    if (LaravelUtils::isInt($id))
    {
      $query = $query->where('di.'.$this->getId(), $id);
    }
    if (LaravelUtils::isArray($ids))
    {
      $query = $query->whereIn('di.'.$this->getId(), $ids);
    }
    if (LaravelUtils::isInt($deliveryId))
    {
      $query = $query->where('di.delivery_id', $deliveryId);
    }
    if ($selectId)
    {
      $query = $query->select('di.'.$this->getId());
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
	  case 'deliveryId':
	    $column = 'delivery_id';
	    break;
          case 'materialId':
            $column = 'material_id';
            break;
	  case 'quantity':
	    $column = 'quantity';
	    break;
        }
        if (!empty($column))
        {
          $query = $query->orderBy('di.'.$column, $dir);
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
    return new DeliveryItem();
  }


  protected function extractRow($row)
  {
    if (!isset($row))
    {
      return new DeliveryItem();
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
    $deliveryId = $row->delivery_id;
    $materialId = $row->material_id;
    $quantity = $row->quantity;
    $materialName = $row->material_name;

    $res = new DeliveryItem();
    $res->setId($id);
    $res->setDeliveryId($deliveryId);
    $res->setMaterialId($materialId);
    $res->setQuantity($quantity);
    $res->setMaterialName($materialName);
    return $res;
  }

  protected function getInsertData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['delivery_id'] = $obj->getDeliveryId();
      $data['material_id'] = $obj->getMaterialId();
      $data['quantity'] = $obj->getQuantity();
    }
    return $data;
  }

  protected function getUpdateData($obj)
  {
    $data = array();
    if (isset($obj))
    {
      $data['delivery_id'] = $obj->getDeliveryId();
      $data['material_id'] = $obj->getMaterialId();
      $data['quantity'] = $obj->getQuantity();
    }
    return $data;
  }

  public function deleteByDelivery($deliveryId)
  {
    \DB::table($this->getTableName())
       ->where('delivery_id', $deliveryId)
       ->delete();
  }

  public function deleteByMaterial($materialId)
  {
    \DB::table($this->getTableName())
       ->where('material_id', $materialId)
       ->delete();
  }

  public function insertMultiple($delivItems)
  {
    $data = array();
    foreach ($delivItems as $di)
    {
      $itemData = $this->getInsertData($di);
      $data []= $itemData;
    }
    \DB::table($this->getTableName())->insert($data);
  }
}

