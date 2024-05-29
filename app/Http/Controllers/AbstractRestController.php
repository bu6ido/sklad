<?php

namespace App\Http\Controllers;

use App\Libraries\LaravelUtils;
use App\Models\AbstractObject;
use App\Models\AbstractModel;

abstract class AbstractRestController extends Controller 
{
  protected $model;

  protected abstract function getModel();

  public function __construct()
  {
    //parent::__construct();

    $this->model = $this->getModel();
  }

  protected abstract function getNewObject();

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

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function find()
  {
    $filters = $this->getFindFilters();
    $result = $this->model->find($filters);
    //Log::info(DB::getQueryLog());
    return \Response::json($result); // \Response::json($result,200,[], JSON_UNESCAPED_UNICODE);
  }

  public function findGrid()
  {
    $filters = $this->getFindFilters();
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

      return \Response::json(array('rows' => $ids, 'total' => 0, 'current' => 1, 'rowCount' => $limit));
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

    return \Response::json(array('rows' => $result, 'total' => $total, 'current' => $current, 'rowCount' => $limit));
  }


  protected abstract function getInputObject();

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $obj = $this->getNewObject();

    return \Response::json($obj);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $obj = $this->getInputObject();
    $id = null;
    if (isset($obj))
    {
      $id = $this->model->insert($obj);
    }
    if ($id)
    {
      $success = true;
    }
    else
    {
      $success = false;
    }
    return \Response::json(array('success' => $success, 'id' => $id));
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $obj = $this->model->findById($id);

    return \Response::json($obj);
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $obj = $this->model->findById($id);

    return \Response::json($obj);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return \Response::json(array('success' => false));
    }
    $obj = $this->getInputObject();
    if (isset($obj))
    {
      $obj->setId($id);
      $this->model->update($obj);
    }
    return \Response::json(array('success' => true));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return \Response::json(array('success' => false));
    }
    $this->model->delete($id);

    return \Response::json(array('success' => true));
  }
}
