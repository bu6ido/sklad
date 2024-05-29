<?php

namespace App\Http\Controllers;

use App\Libraries\LaravelUtils;
use Excel;

abstract class AbstractReportRestController extends Controller
{
  protected abstract function getFindFilters();

  protected abstract function getFindQuery($filters);

  protected abstract function getExportName();
 
  protected abstract function getExportSheetName();

  protected abstract function getExportRow($row);

  public function find()
  {
    $filters = $this->getFindFilters();
    $result = $this->getFindQuery($filters);

    return \Response::json(array('rows' => $result, 'total' => count($result), 'current' => 1, 'rowCount' => -1));
  }

  public function export()
  {
    return Excel::create($this->getExportName(), function($excel) {

      $excel->sheet($this->getExportSheetName(), function($sheet) {
        $filters = $this->getFindFilters();
	$result = $this->getFindQuery($filters);
        $data = array();
        if (!empty($result))
        {
          foreach ($result as $row)
          {
            $data []= $this->getExportRow($row);
          }
        }
        $sheet->fromArray($data, null, false, true);

	$sheet->row(1, function($row) {
          $row->setBackground('#00FFFF');
        });

	$sheet->row(count($data) + 1, function($row) {
          $row->setBackground('#FFFF00');
          $row->setFontWeight('bold');
        });
      });

    })->export('xlsx');

  }
}

