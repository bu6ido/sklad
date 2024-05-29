<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;
use App\Models\AbstractObject;
use App\Models\AbstractModel;

abstract class AbstractLibrary
{
  const DEFAULT_PAGE_ROWS = 3;

  protected $selItem, $delItem;
  protected $showForm, $showDelForm;

  protected $model;

  protected abstract function getModel();

  protected abstract function getNewObject();

  public function __construct()
  {
    $this->showForm = false;
    $this->showDelForm = false;
    $this->selItem = $this->getNewObject();
    $this->delItem = $this->getNewObject();
    $this->model = $this->getModel();
  }

  protected function getFindFilters()
  {
    $filters = array();
    $idName = $this->model->getId(); 
    $filters[$idName] = \Input::get($idName);
    $current = \Input::get('current');
    if (!LaravelUtils::isInt($current))
    {
      $current = 1;
    }
    $rowCount = \Input::get('rowCount');
    if (!LaravelUtils::isInt($rowCount))
    {
      $rowCount = self::DEFAULT_PAGE_ROWS;
    }
    $filters['current'] = $current;
    $filters['offset'] = ($current - 1) * $rowCount;
    $filters['limit'] = $rowCount;
    $sort = \Input::get('sort');
    if (LaravelUtils::isArray($sort))
    {
      $filters['sort'] = $sort;
    }

    return $filters;
  }

  protected function findGrid($filters)
  {
    $current = $filters['current'];
    $limit = $filters['limit'];
    if ($limit < 0)
    {
      unset($filters['limit']);
      unset($filters['offset']);
    }

    $filters['selectId'] = true;
    $ids = $this->model->find($filters);
    if (empty($ids))
    {
      //Log::info(DB::getQueryLog());

      return array('rows' => $ids, 'total' => 0, 'current' => 1, 'rowCount' => $limit);
    }

    unset($filters['limit']);
    unset($filters['offset']);
    unset($filters['selectId']);
    $filters['count'] = true;

    $totArr = $this->model->find($filters);
    $total = 0;
    if (!empty($totArr))
    {
      $total = $totArr[0];
    }

    unset($filters['count']);
    $filters['ids'] = $ids;
    $result = $this->model->find($filters);

    //Log::info(DB::getQueryLog());

    return array('rows' => $result, 'total' => $total, 'current' => $current, 'rowCount' => $limit);
  }

  public abstract function getViewData();

  public function add()
  {
    $this->showForm = true;
    $this->showDelForm = false;
    $this->selItem = $this->getNewObject();
    $this->delItem = $this->getNewObject();
  }

  public function edit($id)
  {
    $this->showForm = true;
    $this->showDelForm = false;
    $this->selItem = $this->model->findById($id);
    $this->delItem = $this->getNewObject();
  }

  public function predelete($id)
  {
    $this->showForm = false;
    $this->showDelForm = true;
    $this->selItem = $this->getNewObject();
    $this->delItem = $this->model->findById($id);
  }

  public function reset($id)
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

      if (LaravelUtils::isInt($obj->getId()))
      {
        $this->model->update($obj);
      }
      else
      {
        $this->model->insert($obj);
      }
    }
    return null;
  }

  public function delete()
  {
    if (\Input::has('yes'))
    {
      $delId = \Input::get('id');
      if (LaravelUtils::isInt($delId))
      {
        $this->model->delete($delId);
        return true;
      }
    }
    return false;
  }
}

