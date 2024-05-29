<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;

class LineController extends AbstractController
{
  protected function getLib()
  {
    return new LineLibrary();
  }

  protected function getViewName()
  {
    return 'lite/lines';
  }

  protected function getRedirectAction()
  {
    $url = LaravelUtils::sessionGet('lines', 'chooseUrl');
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
    $url = LaravelUtils::sessionGet('lines', 'chooseUrl');
    $this->lib->choose($id);
    return \Redirect::to($url);
  }
}

