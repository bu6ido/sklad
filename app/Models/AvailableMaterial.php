<?php

namespace App\Models;

class AvailableMaterial extends AbstractObject
{
  // calculated
  protected $quantity;
  protected $isSummary;
  protected $sum;

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
}

