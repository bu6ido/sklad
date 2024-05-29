<?php

namespace App\Http\Controllers;

use App\Models\MachineWork;
use App\Models\MachineWorkItem;
use App\Models\MachineWorkModel;
use App\Models\MachineWorkItemModel;
use App\Libraries\LaravelUtils;

class MachineWorkRestController extends AbstractRestController
{
  protected $itemModel;

  public function __construct()
  {
    parent::__construct();
    $this->itemModel = new MachineWorkItemModel();
  }

  protected function getModel()
  {
    return new MachineWorkModel();
  }

  protected function getNewObject()
  {
    return new MachineWork();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();

    $filters['startDate'] = \Input::get('startDate');
    $filters['endDate'] = \Input::get('endDate');
    
    return $filters;
  }

  protected function getInputObject()
  {
    $machineId = \Input::get('machineId');
    $lineId = \Input::get('lineId');
    $workDateStr = \Input::get('workDate');
    $description = \Input::get('description');
    $labourHours = \Input::get('labourHours');

    $validator = \Validator::make(
      [
        'machine_id' => $machineId,
        'line_id' => $lineId,
        'work_date' => $workDateStr,
        'labour_hours' => $labourHours
      ],
      [
        'machine_id' => 'required | integer',
        'line_id' => 'required | integer',
        'work_date' => 'required | date_format:Y-m-d',
	'labour_hours' => 'required | numeric'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $workDate = date_create_from_format('Y-m-d', $workDateStr);

    $index = 0;
    $items = array();
    while (\Input::has('items.'.$index.'.usedQuantity'))
    {
      $machineWorkId = \Input::get('items.'.$index.'.machineWorkId');
      $materialId = \Input::get('items.'.$index.'.materialId');
      $usedQuantity = \Input::get('items.'.$index.'.usedQuantity');
      $materialName = \Input::get('items.'.$index.'.materialName');
      $price = \Input::get('items.'.$index.'.price');
      $groupId = \Input::get('items.'.$index.'.groupId');
      $groupName = \Input::get('items.'.$index.'.groupName');

      $mwi = new MachineWorkItem();
      $mwi->setMachineWorkId($machineWorkId);
      $mwi->setMaterialId($materialId);
      $mwi->setUsedQuantity($usedQuantity);
      $mwi->setMaterialName($materialName);
      $mwi->setPrice($price);
      $mwi->setGroupId($groupId);
      $mwi->setGroupName($groupName);
      $items []= $mwi;

      $index++;
    }

    $res = new MachineWork();
    $res->setMachineId($machineId);
    $res->setLineId($lineId);
    $res->setWorkDate($workDate);
    $res->setDescription($description);
    $res->setLabourHours($labourHours);
    $res->setItems($items);
    return $res;
  }

  public function index()
  {
    return \View::make('machine_work');
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
      foreach($items as &$mwi)
      {
        $mwi->setMachineWorkId($id);
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
      $filters = array('machineWorkId' => $id);
      $items = $this->itemModel->find($filters);
      $index = 0;
      foreach ($items as &$mwi)
      {
        $index++;
        $mwi->setId($index);
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
      $filters = array('machineWorkId' => $id);
      $items = $this->itemModel->find($filters);
      $index = 0;
      foreach ($items as &$mwi)
      {
        $index++;
        $mwi->setId($index);
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
      
      $this->itemModel->deleteByWork($id);
      $items = $obj->getItems();
      foreach($items as &$mwi)
      {
        $mwi->setMachineWorkId($id);
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
    $this->itemModel->deleteByWork($id);

    return \Response::json(array('success' => true));
  }
}

