<?php

namespace App\Models;

class DeliveryItem extends AbstractObject
{
  public $deliveryId;
  public $materialId;
  public $quantity;
  
  // calculated
  protected $isSummary;
  protected $sum;
  public $materialName;

  public function getDeliveryId()
  {
    return $this->deliveryId;
  }

  public function setDeliveryId($deliveryId)
  {
    $this->deliveryId = $deliveryId;
  }

  public function getMaterialId()
  {
    return $this->materialId;
  }

  public function setMaterialId($materialId)
  {
    $this->materialId = $materialId;
  }

  public function getQuantity()
  {
    return $this->quantity;
  }

  public function setQuantity($quantity)
  {
    $this->quantity = $quantity;
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
}

