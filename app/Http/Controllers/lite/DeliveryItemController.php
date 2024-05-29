<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;

class DeliveryItemController extends AbstractController
{
  protected function getLib()
  {
    return new DeliveryItemLibrary();
  }

  protected function getViewName()
  {
    return 'lite/deliv_items';
  }
}

