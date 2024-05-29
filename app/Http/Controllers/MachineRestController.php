<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineModel;
use App\Models\MachineWorkModel;
use App\Models\MachineWorkItemModel;
use App\Libraries\LaravelUtils;
use Excel;

class MachineRestController extends AbstractRestController
{
  protected $machineWorkModel;
  protected $machineWorkItemModel;
  
  public function __construct()
  {
    parent::__construct();

    $this->machineWorkModel = new MachineWorkModel();
    $this->machineWorkItemModel = new MachineWorkItemModel();
  }

  protected function getModel()
  {
    return new MachineModel();
  }

  protected function getNewObject()
  {
    return new Machine();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();

    $filters['systemNumber'] = \Input::get('systemNumber');
    $filters['lineId'] = \Input::get('lineId');

    return $filters;
  }


  protected function getInputObject()
  {
    $systemNumber = \Input::get('systemNumber');
    $model = \Input::get('model');
    $machineType = \Input::get('machineType');
    $lineId = \Input::get('lineId');
    $fabricNumber = \Input::get('fabricNumber');
    $invNumber = \Input::get('invNumber');
    $dateBuyStr = \Input::get('dateBuy');
    $price = \Input::get('price');

    $validator = \Validator::make(
      [
        'system_number' => $systemNumber,
        'model' => $model,
        'machine_type' => $machineType,
        'line_id' => $lineId,
        'fabric_number' => $fabricNumber,
        'date_buy' => $dateBuyStr,
        'price' => $price
      ],
      [
        'system_number' => 'required | integer',
        'model' => 'required',
        'machine_type' => 'required',
        'line_id' => 'required | integer',
        'fabric_number' => 'required',
        'date_buy' => 'required | date_format:Y-m-d',
        'price' => 'required | numeric'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $dateBuy = date_create_from_format('Y-m-d', $dateBuyStr);

    $res = new Machine();
    $res->setSystemNumber($systemNumber);
    $res->setModel($model);
    $res->setMachineType($machineType);
    $res->setLineId($lineId);
    $res->setFabricNumber($fabricNumber);
    $res->setInvNumber($invNumber);
    $res->setDateBuy($dateBuy);
    $res->setPrice($price);
    return $res;
  }

  public function index()
  {
    return \View::make('machines');
  }

  public function destroy($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return \Response::json(array('success' => false));
    }
    $this->model->delete($id);
    $filters = array();
    $filters['machineId'] = $id;
    $works = $this->machineWorkModel->find($filters);
    foreach ($works as $mw)
    {
      $this->machineWorkModel->delete($mw->getId());
      $this->machineWorkItemModel->deleteByWork($mw->getId());
    }

    return \Response::json(array('success' => true));
  }

  public function export()
  {
    return Excel::create('machines', function($excel) {

      $excel->sheet('Машини', function($sheet) {
        $filters = $this->getFindFilters();
        $result = $this->model->find($filters);

        $data = array();
        if (empty($result))
	{
	  $result = array(new Machine());
	}

	if (!empty($result))
        {
          foreach ($result as $row)
          {
            $data []= array('Сист. №' => $row->getSystemNumber(),
			    'Модел' => $row->getModel(),
			    'Тип' => $row->getMachineType(),
			    'Линия' => $row->getLineName(),
			    'Фабричен №' => $row->getFabricNumber(),
			    'Инвентарен №' => $row->getInvNumber()
			   );
          }
        }
        $sheet->fromArray($data, null, false, true);

	$sheet->row(1, function($row) {
          $row->setBackground('#00FFFF');
        });

/*	$sheet->row(count($data) + 1, function($row) {
          $row->setBackground('#FFFF00');
          $row->setFontWeight('bold');
        });
*/
      });

    })->export('xlsx');
  }

}

