<?php

namespace App\Http\Controllers\lite;

use App\Libraries\LaravelUtils;
use App\Models\Line;
use App\Models\LineModel;
use Illuminate\Pagination\LengthAwarePaginator;

class LineLibrary extends AbstractLibrary
{
  protected function getModel()
  {
    return new LineModel();
  }

  protected function getNewObject()
  {
    return new Line();
  }

  protected function getFindFilters()
  {
    $filters = parent::getFindFilters();
    $filters['lineName'] = \Input::get('lineName');

    return $filters;
  }

  public function getViewData()
  {
    $linChooseUrl = \Input::get('chooseUrl');
    if (!empty($linChooseUrl))
    {
      LaravelUtils::sessionPut('lines', 'chooseUrl', $linChooseUrl);
    }
    else
    {
      $linChooseUrl = LaravelUtils::sessionGet('lines', 'chooseUrl');
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

    $data = array('lines' => $result, 
                  'selLine' => $this->selItem, 
                  'delLine' => $this->delItem,
                  'showForm' => $this->showForm,
                  'showDelForm' => $this->showDelForm,
		  'linChooseUrl' => $linChooseUrl);
    return $data;
  }

  protected function getInputObject()
  {
    $id = \Input::get('id');
    $lineName = \Input::get('lineName');

    $result = new Line();
    $result->setId($id);
    $result->setLineName($lineName);
    return $result;
  }

  protected function getInputValidator($obj)
  {
    $validator = \Validator::make(
      array(
        'id' => $obj->getId(),
        'line_name' => $obj->getLineName()
      ),
      array(
        'id' => 'integer',
        'line_name' => 'required'
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
    $line = $this->model->findById($id);
    if (isset($line))
    {
      LaravelUtils::sessionPut('lines', 'line_id', $line->getId());

      LaravelUtils::sessionDel('lines', 'chooseUrl');
    }
  }
}

