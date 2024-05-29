<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\LineModel;
use App\Models\MachineModel;
use App\Models\MachineWorkModel;
use App\Models\MachineWorkItemModel;
use App\Libraries\LaravelUtils;

class LineRestController extends AbstractRestController
{
  protected $machineModel;
  protected $machineWorkModel;
  protected $machineWorkItemModel;
  
  public function __construct()
  {
    parent::__construct();

    $this->machineModel = new MachineModel();
    $this->machineWorkModel = new MachineWorkModel();
    $this->machineWorkItemModel = new MachineWorkItemModel();
  }

  protected function getModel()
  {
    return new LineModel();
  }

  protected function getNewObject()
  {
    return new Line();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();

    $filters['lineName'] = \Input::get('lineName');
    
    return $filters;
  }


  protected function getInputObject()
  {
    $lineName = \Input::get('lineName');

    $validator = \Validator::make(
      [
        'line_name' => $lineName
      ],
      [
        'line_name' => 'required'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $res = new Line();
    $res->setLineName($lineName);
    return $res;
  }

  public function index()
  {
    return \View::make('lines');
  }

  protected function deleteWork($machineWorkId)
  {
    $this->machineWorkModel->delete($machineWorkId);
    $this->machineWorkItemModel->deleteByWork($machineWorkId);
  }

  protected function deleteMachine($machineId)
  {
    $this->machineModel->delete($machineId);
    $filters = array();
    $filters['machineId'] = $machineId;
    $works = $this->machineWorkModel->find($filters);
    foreach ($works as $mw)
    {
      $this->deleteWork($mw->getId());
    }
  }

  public function destroy($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return \Response::json(array('success' => false));
    }
    $this->model->delete($id);

    // deletes all machines with lineId
    $filters = array();
    $filters['lineId'] = $id;
    $machines = $this->machineModel->find($filters);
    foreach ($machines as $m)
    {
      $this->deleteMachine($m->getId());
    }

    // deletes all works with lineId
    $works = $this->machineWorkModel->find($filters);
    foreach ($works as $mw)
    {
      $this->deleteWork($mw->getId());
    }

    return \Response::json(array('success' => true));
  }

}

