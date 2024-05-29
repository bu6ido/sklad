<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\DeliveryModel;
use App\Models\DeliveryItemModel;
use App\Libraries\LaravelUtils;

class DeliveryRestController extends AbstractRestController
{
  protected $itemModel;

  public function __construct()
  {
    parent::__construct();
    $this->itemModel = new DeliveryItemModel();
  }

  protected function getModel()
  {
    return new DeliveryModel();
  }

  protected function getNewObject()
  {
    return new Delivery();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();

    $filters['description'] = \Input::get('description');
    $filters['startDate'] = \Input::get('startDate');
    $filters['endDate'] = \Input::get('endDate');

    return $filters;
  }


  protected function getInputObject()
  {
    $deliveryDateStr = \Input::get('deliveryDate');
    $description = \Input::get('description');

    $validator = \Validator::make(
      [
        'delivery_date' => $deliveryDateStr,
        'description' => $description,
      ],
      [
        'delivery_date' => 'required | date_format:Y-m-d',
	'description' => 'required'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $deliveryDate = date_create_from_format('Y-m-d', $deliveryDateStr);

    $index = 0;
    $items = array();
    while (\Input::has('items.'.$index.'.materialId'))
    {
      $deliveryId = \Input::get('items.'.$index.'.deliveryId');
      $materialId = \Input::get('items.'.$index.'.materialId');
      $quantity = \Input::get('items.'.$index.'.quantity');

      $di = new DeliveryItem();
      $di->setDeliveryId($deliveryId);
      $di->setMaterialId($materialId);
      $di->setQuantity($quantity);
      $items []= $di;

      $index++;
    }

    $res = new Delivery();
    $res->setDeliveryDate($deliveryDate);
    $res->setDescription($description);
    $res->setItems($items);
    return $res;
  }

  public function index()
  {
    return \View::make('deliveries');
  }

  public function store()
  {
    $obj = $this->getInputObject();
    $id = null;
    if (isset($obj))
    {
      $id = $this->model->insert($obj);
    }
    if (LaravelUtils::isInt($id))
    {
      $items = $obj->getItems();
      foreach($items as &$di)
      {
        $di->setDeliveryId($id);
      }
      $this->itemModel->insertMultiple($items);
    }
    if ($id)
    {
      $success = true;
    }
    else
    {
      $success = false;
    }
    return \Response::json(array('success' => $success, 'id' => $id));
  }

  public function show($id)
  {
    $obj = $this->model->findById($id);
    if (LaravelUtils::isInt($id))
    {
      $filters = array('deliveryId' => $id);
      $items = $this->itemModel->find($filters);
      $index = 0;
      foreach ($items as &$di)
      {
        $index++;
        $di->setId($index);
      }
      $obj->setItems($items);
    }
    return \Response::json($obj);
  }

  public function edit($id)
  {
    $obj = $this->model->findById($id);
    if (LaravelUtils::isInt($id))
    {
      $filters = array('deliveryId' => $id);
      $items = $this->itemModel->find($filters);
      $index = 0;
      foreach ($items as &$di)
      {
        $index++;
        $di->setId($index);
      }
      $obj->setItems($items);
    }
    return \Response::json($obj);
  }

  public function update($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return \Response::json(array('success' => false));
    }
    $obj = $this->getInputObject();
    if (isset($obj))
    {
      $obj->setId($id);
      $this->model->update($obj);
      
      $this->itemModel->deleteByDelivery($id);
      $items = $obj->getItems();
      foreach($items as &$di)
      {
        $di->setDeliveryId($id);
      }
      $this->itemModel->insertMultiple($items);
    }
    return \Response::json(array('success' => true));
  }

  public function destroy($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return \Response::json(array('success' => false));
    }
    $this->model->delete($id);
    $this->itemModel->deleteByDelivery($id);

    return \Response::json(array('success' => true));
  }

}

