<?php

namespace App\Models;

class Line extends AbstractObject
{
  public $lineName;

  public function getLineName()
  {
    return $this->lineName;
  }

  public function setLineName($lineName)
  {
    $this->lineName = $lineName;
  }
}

