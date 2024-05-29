<?php

namespace App\Models;

class Material extends AbstractObject
{
  public $materialName;
  public $price;
  public $groupId;

  // calculated
  public $groupName;

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

