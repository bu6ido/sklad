<?php

namespace App\Models;

class Delivery extends AbstractObject
{
  protected $deliveryDate;
  public $description;

  // calculated
  public $deliveryDateStr;
  public $items;

  public function __construct()
  {
    $this->setDeliveryDate(date_create('now'));
    $this->setItems(array());
  }

  public function getDeliveryDate()
  {
    return $this->deliveryDate;
  }

  public function setDeliveryDate($deliveryDate)
  {
    $this->deliveryDate = $deliveryDate;
    $this->deliveryDateStr = date_format($this->deliveryDate, 'Y-m-d');
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription($description)
  {
    $this->description = $description;
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

