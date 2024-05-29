<?php

namespace App\Models;

class Machine extends AbstractObject
{
  public $systemNumber;
  public $model;
  public $machineType;
  public $lineId;
  public $fabricNumber;
  public $invNumber;
  protected $dateBuy;
  public $price;

  //calculated
  public $dateBuyStr;
  public $lineName;

  public function getSystemNumber()
  {
    return $this->systemNumber;
  }

  public function setSystemNumber($systemNumber)
  {
    $this->systemNumber = $systemNumber;
  }

  public function getModel()
  {
    return $this->model;
  }

  public function setModel($model)
  {
    $this->model = $model;
  }

  public function getMachineType()
  {
    return $this->machineType;
  }

  public function setMachineType($machineType)
  {
    $this->machineType = $machineType;
  }

  public function getLineId()
  {
    return $this->lineId;
  }

  public function setLineId($lineId)
  {
    $this->lineId = $lineId;
  }

  public function getFabricNumber()
  {
    return $this->fabricNumber;
  }

  public function setFabricNumber($fabricNumber)
  {
    $this->fabricNumber = $fabricNumber;
  }

  public function getInvNumber()
  {
    return $this->invNumber;
  }

  public function setInvNumber($invNumber)
  {
    $this->invNumber = $invNumber;
  }

  public function getDateBuy()
  {
    return $this->dateBuy;
  }

  public function setDateBuy($dateBuy)
  {
    $this->dateBuy = $dateBuy;
    $this->dateBuyStr = date_format($this->dateBuy, 'Y-m-d');
  }

  public function getPrice()
  {
    return $this->price;
  }

  public function setPrice($price)
  {
    $this->price = $price;
  }

  public function getLineName()
  {
    return $this->lineName;
  }

  public function setLineName($lineName)
  {
    $this->lineName = $lineName;
  }
}

