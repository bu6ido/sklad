<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;
use App\Models\AbstractObject;

abstract class AbstractSessionLibrary
{
  protected $index;
  protected $selItem, $delItem;
  protected $showForm, $showDelForm;

  protected abstract function getNewObject();

  public function __construct()
  {
    $this->index = 0;
    $this->showForm = false;
    $this->showDelForm = false;
    $this->selItem = $this->getNewObject();
    $this->delItem = $this->getNewObject();
  }

  // returns null or object
  protected abstract function loadObjectState($index);

  // returns nothing
  protected abstract function saveObjectState($index, $obj);

  // returns nothing
  protected abstract function deleteObjectState($index);

  public function deleteState()
  {
    $index = 0;

    while (true)
    {
      $obj = $this->loadObjectState($index);
      if (!isset($obj))
        return;
      $this->deleteObjectState($index);
      $index++;
    }
  }

  public function loadState()
  {
    $index = 0;
    $result = array();
    $obj = null;

    while (true)
    {
      $obj = $this->loadObjectState($index);
      if (!isset($obj))
        break;
      $result []= $obj;
      $index++;
    }

    return $result;
  }

  public function saveState($items)
  {
    $this->deleteState();

    if (!LaravelUtils::isArray($items))
    {
      return;
    }

    $index = 0;
    foreach($items as $obj)
    {
      $this->saveObjectState($index, $obj);
      $index++;
    }
  }

  public abstract function getViewData();

  public function add()
  {
    $this->index = null;
    $this->showForm = true;
    $this->showDelForm = false;
    $this->selItem = $this->getNewObject();
    $this->delItem = $this->getNewObject();
  }

  public function edit($index)
  {
    $this->index = $index;
    $this->showForm = true;
    $this->showDelForm = false;
    $this->selItem = $this->loadObjectState($this->index);
    $this->delItem = $this->getNewObject();
  }

  public function predelete($index)
  {
    $this->index = $index;
    $this->showForm = false;
    $this->showDelForm = true;
    $this->selItem = $this->getNewObject();
    $this->delItem = $this->loadObjectState($this->index);
  }

  public function reset($index)
  {
    
  }

  protected abstract function getInputObject();

  protected abstract function getInputValidator($obj);

  public function save()
  {
    if (\Input::has('save'))
    {
      $obj = $this->getInputObject();

      $validator = $this->getInputValidator($obj);
      if ($validator->fails())
      {
        return $validator;
      }

      $index = \Input::get('index');

      if (LaravelUtils::isInt($index))
      {
        $this->saveObjectState($index, $obj);
      }
      else
      {
        $items = $this->loadState();
        $index = count($items);
        $this->saveObjectState($index, $obj);
      }
    }
    return null;
  }

  public function delete()
  {
    if (\Input::has('yes'))
    {
      $index = \Input::get('index');
      if (LaravelUtils::isInt($index))
      {
        $items = $this->loadState();
        unset($items[$index]);
        $items = array_values($items);
        $this->saveState($items);
        return true;
      }
    }
    return false;
  }

}

