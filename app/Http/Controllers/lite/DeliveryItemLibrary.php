<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;
use App\Models\DeliveryItem;

class DeliveryItemLibrary extends AbstractSessionLibrary
{
  protected function getNewObject()
  {
    return new DeliveryItem();
  }

  protected function loadObjectState($index)
  {
    if (!LaravelUtils::isInt($index))
    {
      return null;
    }
    
    $id = LaravelUtils::sessionGet('delivery_items', $index.'.id');
    $deliveryId = LaravelUtils::sessionGet('delivery_items', $index.'.delivery_id');
    $materialId = LaravelUtils::sessionGet('delivery_items', $index.'.material_id');
    $quantity = LaravelUtils::sessionGet('delivery_items', $index.'.quantity');

    if (empty($quantity))
    {
      return null;
    }

    $res = new DeliveryItem();
    $res->setId($id);
    $res->setDeliveryId($deliveryId);
    $res->setMaterialId($materialId);
    $res->setQuantity($quantity);
    return $res;
  }

  protected function saveObjectState($index, $obj)
  {
    if (!LaravelUtils::isInt($index))
    {
      return;
    }
    if (!isset($obj))
    {
      return;
    }  

    LaravelUtils::sessionPut('delivery_items', $index.'.id', $obj->getId());
    LaravelUtils::sessionPut('delivery_items', $index.'.delivery_id', $obj->getDeliveryId());
    LaravelUtils::sessionPut('delivery_items', $index.'.material_id', $obj->getMaterialId());
    LaravelUtils::sessionPut('delivery_items', $index.'.quantity', $obj->getQuantity());
  }

  protected function deleteObjectState($index)
  {
    if (!LaravelUtils::isInt($index))
    {
      return;
    }

    LaravelUtils::sessionDel('delivery_items', $index.'.id');
    LaravelUtils::sessionDel('delivery_items', $index.'.delivery_id');
    LaravelUtils::sessionDel('delivery_items', $index.'.material_id');
    LaravelUtils::sessionDel('delivery_items', $index.'.quantity');
  }
  
  public function getViewData()
  {
    $result = $this->loadState();
    
    $data = array('delivItems' => $result, 
                  'selDelivItem' => $this->selItem, 
                  'delDelivItem' => $this->delItem,
                  'showItemForm' => $this->showForm,
                  'showDelItemForm' => $this->showDelForm,
		  'delivItemIndex' => $this->index);
    return $data;
  }

  protected function getInputObject()
  {
    $id = \Input::get('id');
    $deliveryId = \Input::get('deliveryId');
    $materialId = \Input::get('materialId');
    $quantity = \Input::get('quantity');

    $result = new DeliveryItem();
    $result->setId($id);
    $result->setDeliveryId($deliveryId);
    $result->setMaterialId($materialId);
    $result->setQuantity($quantity);
    return $result;
  }

  protected function getInputValidator($obj)
  {
    $validator = \Validator::make(
      array(
        'id' => $obj->getId(),
        'delivery_id' => $obj->getDeliveryId(),
        'material_id' => $obj->getMaterialId(),
        'quantity' => $obj->getQuantity()
      ),
      array(
        'id' => 'integer',
        'delivery_id' => 'integer',
        'material_id' => 'required | integer',
	'quantity' => 'required | numeric'
      )
    );
    return $validator;
  }

}

