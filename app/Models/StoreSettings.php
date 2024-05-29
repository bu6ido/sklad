<?php

namespace App\Models;

class StoreSettings extends AbstractObject
{
  public $labourPrice = 0.0;

  public function getLabourPrice()
  {
    return $this->labourPrice;
  }

  public function setLabourPrice($labourPrice)
  {
    $this->labourPrice = $labourPrice;
  }
}

