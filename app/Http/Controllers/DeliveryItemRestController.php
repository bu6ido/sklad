<?php

namespace App\Http\Controllers;

use App\Models\DeliveryItem;
use App\Models\DeliveryItemModel;

class DeliveryItemRestController extends AbstractRestController
{
  protected function getModel()
  {
    return new DeliveryItemModel();
  }

  protected function getNewObject()
  {
    return new DeliveryItem();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();

    $filters['deliveryId'] = \Input::get('deliveryId');
    
    return $filters;
  }


  protected function getInputObject()
  {
    $deliveryId = \Input::get('deliveryId');
    $materialId = \Input::get('materialId');
    $quantity = \Input::get('quantity');

    $validator = \Validator::make(
      [
        'delivery_id' => $deliveryId,
        'material_id' => $materialId,
        'quantity' => $quantity

      ],
      [
        'delivery_id' => 'required | integer',
        'material_id' => 'required | integer',
	'quantity' => 'required | numeric'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $res = new DeliveryItem();
    $res->setDeliveryId($deliveryId);
    $res->setMaterialId($materialId);
    $res->setQuantity($quantity);
    return $res;
  }

  public function index()
  {
    return \View::make('delivery_items');
  }
}

