<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialModel;
use App\Models\DeliveryItemModel;
use App\Models\MachineWorkItemModel;
use App\Libraries\LaravelUtils;

class MaterialRestController extends AbstractRestController
{
  protected $deliveryItemModel;
  protected $machineWorkItemModel;

  public function __construct()
  {
    parent::__construct();

    $this->deliveryItemModel = new DeliveryItemModel();
    $this->machineWorkItemModel = new MachineWorkItemModel();
  }

  protected function getModel()
  {
    return new MaterialModel();
  }

  protected function getNewObject()
  {
    return new Material();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();

    $filters['materialName'] = \Input::get('materialName');
    $filters['groupId'] = \Input::get('groupId');

    return $filters;
  }


  protected function getInputObject()
  {
    $materialName = \Input::get('materialName');
    $price = \Input::get('price');
    $groupId = \Input::get('groupId');

    $validator = \Validator::make(
      [
        'material_name' => $materialName,
        'price' => $price,
        'group_id' => $groupId
      ],
      [
        'material_name' => 'required',
        'price' => 'required | numeric',
        'groupId' => 'integer'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $res = new Material();
    $res->setMaterialName($materialName);
    $res->setPrice($price);
    $res->setGroupId($groupId);
    return $res;
  }

  public function index()
  {
    return \View::make('materials');
  }

  public function destroy($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return \Response::json(array('success' => false));
    }
    $this->model->delete($id);
    $this->deliveryItemModel->deleteByMaterial($id);
    $this->machineWorkItemModel->deleteByMaterial($id);

    return \Response::json(array('success' => true));
  }

}

