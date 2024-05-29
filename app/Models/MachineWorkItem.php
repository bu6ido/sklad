<?php

namespace App\Models;

class MachineWorkItem extends AbstractObject
{
  public $machineWorkId;
  public $materialId;
  public $usedQuantity;

  // calculated
  protected $isSummary;
  protected $sum;
  public $materialName;

  public $price;
  public $groupId;
  public $groupName;

  public function getMachineWorkId()
  {
    return $this->machineWorkId;
  }

  public function setMachineWorkId($machineWorkId)
  {
    $this->machineWorkId = $machineWorkId;
  }

  public function getMaterialId()
  {
    return $this->materialId;
  }

  public function setMaterialId($materialId)
  {
    $this->materialId = $materialId;
  }

  public function getUsedQuantity()
  {
    return $this->usedQuantity;
  }

  public function setUsedQuantity($usedQuantity)
  {
    $this->usedQuantity = $usedQuantity;
  }

  public function getIsSummary()
  {
    return $this->isSummary;
  }

  public function setIsSummary($isSummary)
  {
    $this->isSummary = $isSummary;
  }

  public function getSum()
  {
    return $this->sum;
  }

  public function setSum($sum)
  {
    $this->sum = $sum;
  }

  public function getMaterialName()
  {
    return $this->materialName;
  }

  public function setMaterialName($materialName)
  {
    $this->materialName = $materialName;
  }

  public function getPrice()
  {
    return $this->price;
  }

  public function setPrice($price)
  {
    $this->price = $price;
  }

  public function getGroupId()
  {
    return $this->groupId;
  }

  public function setGroupId($groupId)
  {
    $this->groupId = $groupId;
  }

  public function getGroupName()
  {
    return $this->groupName;
  }

  public function setGroupName($groupName)
  {
    $this->groupName = $groupName;
  }
}

