<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;
use App\Models\AbstractObject;
use App\Models\AbstractModel;
use App\Http\Controllers\Controller;

abstract class AbstractController extends Controller
{
  protected $lib;

  protected abstract function getLib();
  
  protected abstract function getViewName();

  public function __construct()
  {
    $this->lib = $this->getLib();
  }

  public function index()
  {
    return \View::make($this->getViewName(), $this->lib->getViewData());
  }

  public function add()
  {
    $this->lib->add();

    return $this->index();
  }

  public function edit($id)
  {
    $this->lib->edit($id);

    return $this->index();
  }

  public function predelete($id)
  {
    $this->lib->predelete($id);

    return $this->index();
  }

  public function reset($id)
  {
    
  }

  protected function getRedirectAction()
  {
    $fullClassName = get_class($this);
    $arr = explode('\\', $fullClassName);
    $className = end($arr);
    $result = 'lite\\'.$className.'@index';
    return \Redirect::action($result);
  }

  public function save()
  {
    $validator = $this->lib->save();
    if (isset($validator))
    {
      return \Redirect::back()->withInput()->withErrors($validator);
    }

    return $this->getRedirectAction();
  }

  public function delete()
  {
    $this->lib->delete();

    return $this->getRedirectAction();
  }
}

