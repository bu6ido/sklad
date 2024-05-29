<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;
use App\Models\Delivery;
use App\Models\DeliveryModel;
use Illuminate\Pagination\LengthAwarePaginator;

class DeliveryLibrary extends AbstractLibrary
{
  protected function getModel()
  {
    return new DeliveryModel();
  }

  protected function getNewObject()
  {
    return new Delivery();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();
    $filters['description'] = \Input::get('description');

    return $filters;
  }

  public function getViewData()
  {
    $delivChooseUrl = \Input::get('chooseUrl');
    if (!empty($delivChooseUrl))
    {
      LaravelUtils::sessionPut('deliveries', 'chooseUrl', $delivChooseUrl);
    }
    else
    {
      $delivChooseUrl = LaravelUtils::sessionGet('deliveries', 'chooseUrl');
    }

    $filters = $this->getFindFilters();
    $result = $this->findGrid($filters);
    $rows = LaravelUtils::arrVal($result, 'rows');
    $total = LaravelUtils::arrVal($result, 'total');
    $current = LaravelUtils::arrVal($result, 'current');
    $rowCount = LaravelUtils::arrVal($result, 'rowCount');

    $result = new LengthAwarePaginator($rows, $total, $rowCount, $current);
    $result->setPath('/'.\Request::path());
    $result->setPageName('current');

    $data = array('deliveries' => $result, 
                  'selDelivery' => $this->selItem, 
                  'delDelivery' => $this->delItem,
                  'showForm' => $this->showForm,
                  'showDelForm' => $this->showDelForm,
		  'delivChooseUrl' => $delivChooseUrl);
    return $data;
  }

  protected function getInputObject()
  {
    $id = \Input::get('id');
    $deliveryDateStr = \Input::get('deliveryDate');
    $deliveryDate = date_create_from_format('Y-m-d', \Input::get('deliveryDate'));
    if (!$deliveryDate)
    {
      $deliveryDate = date_create('now');
    }
    $description = \Input::get('description');

    $result = new Delivery();
    $result->setId($id);
    $result->setDeliveryDate($deliveryDate);
    $result->deliveryDateStr = $deliveryDateStr;
    $result->setDescription($description);
    return $result;
  }

  protected function getInputValidator($obj)
  {
    $validator = \Validator::make(
      array(
        'id' => $obj->getId(),
        'delivery_date' => $obj->deliveryDateStr,
        'description' => $obj->getDescription()
      ),
      array(
        'id' => 'integer',
        'delivery_date' => 'required | date_format:Y-m-d',
	'description' => 'required'
      )
    );
    return $validator;
  }

  public function choose($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return;
    }
    $delivery = $this->model->findById($id);
    if (isset($delivery))
    {
      LaravelUtils::sessionPut('deliveries', 'delivery_id', $delivery->getId());

      LaravelUtils::sessionDel('deliveries', 'chooseUrl');
    }
  }
}

