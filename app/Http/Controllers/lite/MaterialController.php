<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;

class MaterialController extends AbstractController
{
  protected function getLib()
  {
    return new MaterialLibrary();
  }

  protected function getViewName()
  {
    return 'lite/materials';
  }

  protected function getRedirectAction()
  {
    $url = LaravelUtils::sessionGet('materials', 'chooseUrl');
    $fullClassName = get_class($this);
    $arr = explode('\\', $fullClassName);
    $className = end($arr);
    $result = 'lite\\'.$className.'@index';
    if (empty($url))
    {
      return \Redirect::action($result);
    }
    return \Redirect::action($result, array('url' => $chooseUrl));
  }

  public function choose($id)
  {
    $url = LaravelUtils::sessionGet('materials', 'chooseUrl');
    $this->lib->choose($id);
    return \Redirect::to($url);
  }
}

