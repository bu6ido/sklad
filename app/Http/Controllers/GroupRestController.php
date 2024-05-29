<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupModel;
use App\Models\MaterialModel;
use App\Models\DeliveryItemModel;
use App\Models\MachineWorkItemModel;
use App\Libraries\LaravelUtils;

class GroupRestController extends AbstractRestController
{
  protected $materialModel;
  protected $deliveryItemModel;
  protected $machineWorkItemModel;

  public function __construct()
  {
    parent::__construct();

    $this->materialModel = new MaterialModel();
    $this->deliveryItemModel = new DeliveryItemModel();
    $this->machineWorkItemModel = new MachineWorkItemModel();
  }

  protected function getModel()
  {
    return new GroupModel();
  }

  protected function getNewObject()
  {
    return new Group();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();

    $filters['groupName'] = \Input::get('groupName');
    $filters['parentId'] = \Input::get('parentId');

    return $filters;
  }


  protected function getInputObject()
  {
    $groupName = \Input::get('groupName');
    $parentId = \Input::get('parentId');

    $validator = \Validator::make(
      [
        'group_name' => $groupName,
        'parent_id' => $parentId
      ],
      [
        'group_name' => 'required',
        'parent_id' => 'integer'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $res = new Group();
    $res->setGroupName($groupName);
    $res->setParentId($parentId);
    return $res;
  }

  public function index()
  {
    return \View::make('groups');
  }

  protected function deleteMaterial($materialId)
  {
    $this->materialModel->delete($materialId);
    $this->deliveryItemModel->deleteByMaterial($materialId);
    $this->machineWorkItemModel->deleteByMaterial($materialId);
  }

  public function destroy($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return \Response::json(array('success' => false));
    }
    // deletes all materials for this group and its subgroups
    $filters = array();
    $filters['groupId'] = $id;
    $mats = $this->materialModel->find($filters);
    foreach ($mats as $m)
    {
      $this->deleteMaterial($m->getId());
    }
    // delete the group and all its subgroups
    $groups = $this->model->findAllGroups($id);
    $this->model->deleteMultiple($groups);

    return \Response::json(array('success' => true));
  }
}

