<?php

namespace App\Models;

abstract class AbstractObject
{
  public $id;

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }
}

