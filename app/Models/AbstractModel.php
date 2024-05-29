<?php

namespace App\Models;

abstract class AbstractModel
{
  protected abstract function getTableName();

  public function getId()
  {
    return 'id';
  }

  protected abstract function getFindQuery($filters);
  protected abstract function extractRow($row);

  public function find($filters)
  {
    $data = $this->getFindQuery($filters);
    $result = array();
    if (!empty($data))
    {
      foreach($data as $row)
      {
        $obj = $this->extractRow($row);
        $result []= $obj;
      }
    }
    return $result;
  }

  public function findById($id)
  {
    $row =  \DB::table($this->getTableName())
              ->where($this->getId(), $id)
              ->first();
    return $this->extractRow($row);
  }

  protected abstract function getInsertData($obj);
  protected abstract function getUpdateData($obj);

  public function insert($obj)
  {
    $data = $this->getInsertData($obj);
    $id = \DB::table($this->getTableName())
            ->insertGetId($data);
    return $id;
  }

  public function update($obj)
  {
    if (!isset($obj))
    {
      return;
    }
    $data = $this->getUpdateData($obj);
    \DB::table($this->getTableName())
      ->where($this->getId(), $obj->getId())
      ->update($data);
  }

  public function delete($id)
  {
    \DB::table($this->getTableName())
      ->where($this->getId(), $id)
      ->delete();
  }
}

