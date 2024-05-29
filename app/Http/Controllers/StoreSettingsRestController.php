<?php

namespace App\Http\Controllers;

use App\Models\StoreSettings;
use App\Models\StoreSettingsModel;

class StoreSettingsRestController extends AbstractRestController
{
  protected function getModel()
  {
    return new StoreSettingsModel();
  }

  protected function getNewObject()
  {
    return new StoreSettings();
  }

  protected function getInputObject()
  {
    $labourPrice = \Input::get('labourPrice');

    $validator = \Validator::make(
      [
        'labour_price' => $labourPrice
      ],
      [
        'labour_price' => 'required'
      ]
    );
    if ($validator->fails())
    {
      abort(500, $validator->messages());
    }

    $res = new StoreSettings();
    $res->setLabourPrice($labourPrice);
    return $res;
  }

  public function index()
  {
    return \View::make('settings');
  }
}

