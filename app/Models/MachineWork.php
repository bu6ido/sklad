<?php

namespace App\Models;

class MachineWork extends AbstractObject
{
  public $machineId;
  public $lineId;
  protected $workDate;
  public $description;
  public $labourHours;

  // calculated
  public $workDateStr;
  public $machineModel;
  public $lineName;
  public $items;

  public function __construct()
  {
    $this->setItems(array());
  }

  public function getMachineId()
  {
    return $this->machineId;
  }

  public function setMachineId($machineId)
  {
    $this->machineId = $machineId;
  }

  public function getLineId()
  {
    return $this->lineId;
  }

  public function setLineId($lineId)
  {
    $this->lineId = $lineId;
  }

  public function getWorkDate()
  {
    return $this->workDate;
  }

  public function setWorkDate($workDate)
  {
    $this->workDate = $workDate;
    $this->workDateStr = date_format($this->workDate, 'Y-m-d');
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function getLabourHours()
  {
    return $this->labourHours;
  }

  public function setLabourHours($labourHours)
  {
    $this->labourHours = $labourHours;
  }

  public function getMachineModel()
  {
    return $this->machineModel;
  }

  public function setMachineModel($machineModel)
  {
    $this->machineModel = $machineModel;
  }

  public function getLineName()
  {
    return $this->lineName;
  }

  public function setLineName($lineName)
  {
    $this->lineName = $lineName;
  }

  public function getItems()
  {
    return $this->items;
  }

  public function setItems($items)
  {
    $this->items = $items;
  }
}

