<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;
use App\Models\Material;
use App\Models\MaterialModel;
use Illuminate\Pagination\LengthAwarePaginator;

class MaterialLibrary extends AbstractLibrary
{
  protected function getModel()
  {
    return new MaterialModel();
  }

  protected function getNewObject()
  {
    return new Material();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();
    $filters['materialName'] = \Input::get('materialName');

    return $filters;
  }

  public function getViewData()
  {
    $matChooseUrl = \Input::get('chooseUrl');
    if (!empty($matChooseUrl))
    {
      LaravelUtils::sessionPut('materials', 'chooseUrl', $matChooseUrl);
    }
    else
    {
      LaravelUtils::sessionGet('materials', 'chooseUrl');
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

    $data = array('materials' => $result, 
                  'selMaterial' => $this->selItem, 
                  'delMaterial' => $this->delItem,
                  'showForm' => $this->showForm,
                  'showDelForm' => $this->showDelForm,
                  'matChooseUrl' => $matChooseUrl);
    return $data;
  }

  protected function getInputObject()
  {
    $id = \Input::get('id');
    $materialName = \Input::get('materialName');
    $price = \Input::get('price');
    $groupId = \Input::get('groupId');

    $result = new Material();
    $result->setId($id);
    $result->setMaterialName($materialName);
    $result->setPrice($price);
    $result->setGroupId($groupId);
    return $result;
  }

  protected function getInputValidator($obj)
  {
    $validator = \Validator::make(
      array(
        'id' => $obj->getId(),
        'material_name' => $obj->getMaterialName(),
        'price' => $obj->getPrice(),
        'groupId' => $obj->getGroupId()
      ),
      array(
        'id' => 'integer',
        'material_name' => 'required',
        'price' => 'required | numeric',
        'groupId' => 'integer'
      )
    );
    return $validator;
  }

/*  public function save()
  {
    if (\Input::has('btnTestis'))
    {
      LaravelUtils::sessionPut('materials', 'testis', 'This is a test message !!!');

      $obj = $this->getInputObject();
      $validator = $this->getInputValidator($obj);
      return $validator;
    }
    
    return parent::save();
  }
*/

  public function choose($id)
  {
    if (!LaravelUtils::isInt($id))
    {
      return;
    }
    $mat = $this->model->findById($id);
    if (isset($mat))
    {
      LaravelUtils::sessionPut('materials', 'material_id', $mat->getId());

      LaravelUtils::sessionDel('materials', 'chooseUrl');
    }
  }
}

