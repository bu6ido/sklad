<?php

namespace App\Models;

class Group extends AbstractObject
{
  public $parentId;
  public $groupName;

  // calculated
  public $parentName;

  public function getParentId()
  {
    return $this->parentId;
  }

  public function setParentId($parentId)
  {
    $this->parentId = $parentId;
  }

  public function getGroupName()
  {
    return $this->groupName;
  }

  public function setGroupName($groupName)
  {
    $this->groupName = $groupName;
  }

  public function getParentName()
  {
    return $this->parentName;
  }

  public function setParentName($parentName)
  {
    $this->parentName = $parentName;
  }
}

