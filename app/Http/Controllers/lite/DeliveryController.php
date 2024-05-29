<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;

class DeliveryController extends AbstractController
{
  protected $dilib;

  public function __construct()
  {
    parent::__construct();
    $this->dilib = new DeliveryItemLibrary();
  }

  protected function getLib()
  {
    return new DeliveryLibrary();
  }

  protected function getViewName()
  {
    return 'lite/deliveries';
  }

  public function index()
  {
    $viewData = $this->lib->getViewData();
    $viewData = array_merge($viewData, $this->dilib->getViewData());

    return \View::make($this->getViewName(), $viewData);
  }

  protected function getRedirectAction()
  {
    $url = LaravelUtils::sessionGet('deliveries', 'chooseUrl');
    $fullClassName = get_class($this);
    $arr = explode('\\', $fullClassName);
    $className = end($arr);
    $result = 'lite\\'.$className.'@index';
    if (empty($url))
    {
      return \Redirect::action($result);
    }
    return \Redirect::action($result, array('chooseUrl' => $url));
  }

  public function choose($id)
  {
    $url = LaravelUtils::sessionGet('deliveries', 'chooseUrl');
    $this->lib->choose($id);
    return \Redirect::to($url);
  }


  public function di_add()
  {
    if (\Input::has('deliveryId'))
    {
      $this->lib->edit(\Input::get('deliveryId'));
    }
    else
    {
      $this->lib->add();
    }

    $this->dilib->add();

    return $this->index();
  }

  public function di_edit($index)
  {
    if (\Input::has('deliveryId'))
    {
      $this->lib->edit(\Input::get('deliveryId'));
    }
    else
    {
      $this->lib->add();
    }

    $this->dilib->edit($index);

    return $this->index();
  }

  public function di_predelete($index)
  {
    if (\Input::has('deliveryId'))
    {
      $this->lib->edit(\Input::get('deliveryId'));
    }
    else
    {
      $this->lib->add();
    }

    $this->dilib->predelete($index);

    return $this->index();
  }

  protected function getDelivItemRedirectAction()
  {
    if (\Input::has('deliveryId'))
    {
      return \Redirect::action('lite\\DeliveryController@edit', array('id' => \Input::get('deliveryId')) );
    }
    return \Redirect::action('lite\\DeliveryController@add');
  }

  public function di_save()
  {
    $validator = $this->dilib->save();
    if (isset($validator))
    {
      return \Redirect::back()->withInput()->withErrors($validator);
    }

    return $this->getDelivItemRedirectAction();
  }

  public function di_delete()
  {
    $this->dilib->delete();

    return $this->getDelivItemRedirectAction();
  }
}

