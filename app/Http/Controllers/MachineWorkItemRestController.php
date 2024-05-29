<?php

namespace App\Http\Controllers;

use App\Models\MachineWorkItem;
use App\Models\MachineWorkItemModel;

class MachineWorkItemRestController extends AbstractRestController
{
  protected function getModel()
  {
    return new MachineWorkItemModel();
  }

  protected function getNewObject()
  {
    return new MachineWorkItem();
  }

/*  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();

    $filters['lineName'] = \Input::get('lineName');
    
    return $filters;
  }
*/

  protected function getInputObject()
  {
    $machineWorkId = \Input::get('machineWorkId');
    $materialId = \Input::get('materialId');
    $usedQuantity = \Input::get('usedQuantity');

    $validator = \Validator::make(
      [
        'machine_work_id' => $machineWorkId,
        'material_id' => $materialId,
        'used_quantity' => $usedQuantity
      ],
      [
        'machine_work_id' => 'required | integer',
        'material_id' => 'required | integer',
        'used_quantity' => 'required | numeric'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $res = new MachineWorkItem();
    $res->setMachineWorkId($machineWorkId);
    $res->setMaterialId($materialId);
    $res->setUsedQuantity($usedQuantity);
    return $res;
  }

  public function index()
  {
    return \View::make('machine_work_items');
  }
}

