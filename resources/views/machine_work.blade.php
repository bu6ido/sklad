@extends('column2')

@section('title_section') Работа с машини @endsection

@section('left_section')
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Навигация</h3>
  </div>
  <div class="panel-body">

<ul class="nav nav-pills nav-stacked text-left">
  <li role="navigation">
    <ul class="nav nav-pills">
      <li role="navigation"><a href="/"><b>Начало</b></a></li>
    </ul>
  </li>

  <li role="navigation">
    <ul class="nav nav-pills">
      <li role="navigation"><a href="#" data-toggle="collapse" data-target="#nomenMenu"><b>Номенклатури</b> <span class="caret"></span></a></li>
    </ul>
    <ul id="nomenMenu" class="nav nav-pills nav-stacked in">
      <li role="navigation"><a href="/settings">&nbsp;Общи настройки</a></li>
      <li role="navigation"><a href="/lines">&nbsp;Линии(бригади)</a></li>
      <li role="navigation"><a href="/machines">&nbsp;Машини</a></li>
      <li role="navigation"><a href="/groups">&nbsp;Групи</a></li>
      <li role="navigation"><a href="/materials">&nbsp;Материали</a></li>
    </ul>
  </li>

  <li role="navigation">
    <ul class="nav nav-pills">
      <li role="navigation"><a href="#" data-toggle="collapse" data-target="#actionsMenu"><b>Операции</b> <span class="caret"></span></a></li>
    </ul>
    <ul id="actionsMenu" class="nav nav-pills nav-stacked in">
      <li role="navigation"><a href="/deliveries">&nbsp;Доставки</a></li>
      <li role="navigation" class="active"><a href="/machine-work">&nbsp;Работа с машини</a></li>
    </ul>
  </li>

  <li role="navigation">
    <ul class="nav nav-pills">
      <li role="navigation"><a href="#" data-toggle="collapse" data-target="#reportsMenu"><b>Отчети</b> <span class="caret"></span></a></li>
    </ul>
    <ul id="reportsMenu" class="nav nav-pills nav-stacked in">
      <li role="navigation"><a href="/report-delivered">&nbsp;Доставени материали</a></li>
      <li role="navigation"><a href="/report-work">&nbsp;Изразходвани материали и труд</a></li>
      <li role="navigation"><a href="/report-available">&nbsp;Налични материали</a></li>
    </ul>
  </li>
</ul>

  </div>
</div>
@endsection

@section('main_section')
<div class="panel panel-primary">
  <div class="panel-heading">
    <h1 class="panel-title page-header">Работа с машини</h1>
  </div>
  <div class="panel-body">

<form id="formSearchMachineWork" method="get" class="form-inline">
  <div class="form-group">
    <label class="control-label">Начална дата:</label>
    <input type="text" class="form-control" name="startDate" />
    <label class="control-label">Крайна дата:</label>
    <input type="text" class="form-control" name="endDate" />
    <button type="submit" class="btn btn-primary">Търси</button>      
  </div>
</form>

<div class="table-responsive">
<table id="tblMachineWork" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="id" data-converter="numeric">№</th>
    <th data-column-id="machineModel">Машина</th>
    <th data-column-id="lineName">Линия</th>
    <th data-column-id="workDateStr">Дата</th>
    <th data-column-id="description">Описание</th>
    <th data-column-id="actions" data-formatter="actions" data-searchable="false" 
	data-sortable="false">Actions</th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<button id="btnCreateMachineWork" type="button" class="btn btn-primary">
  Добави нова работа
</button>

  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="dlgMachineWork" tabindex="-1" role="dialog" aria-labelledby="lblTitleMachineWork" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgMachineWork" method="post">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXMachineWork" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleMachineWork">Добави/редактирай работа:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" class="form-control" name="machineWorkId" />
          <label class="control-label">Машина:</label>
          <input type="hidden" name="machineId" />
	  <div class="input-group">
	    <input type="text" class="form-control" name="machineModel" onfocus="blur();" 
		   required data-error="Моля изберете машина !!!" />
	    <span class="input-group-btn">
	      <button id="btnChooseMachine" type="button" class="btn btn-primary"
		data-toggle="tooltip" title="Изберете машина">...</button>
            </span>
	  </div>
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Линия:</label>
          <input type="hidden" name="lineId" />
	  <div class="input-group">
	    <input type="text" class="form-control" name="lineName" onfocus="blur();" 
		   required data-error="Моля изберете линия !!!" />
	    <span class="input-group-btn">
	      <button id="btnChooseLine" type="button" class="btn btn-primary"
		data-toggle="tooltip" title="Изберете линия">...</button>
            </span>
	  </div>
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Дата:</label>
          <input type="text" class="form-control" name="workDate"
		 required pattern="^\d{4}-\d{1,2}-\d{1,2}$" data-error="Датата на работа е задължителна и в съответния формат !!!" />
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Описание:</label>
          <textarea class="form-control" rows="3" cols="20" name="description"></textarea>
        </div>
        <div class="form-group">
          <label class="control-label">Труд(часове):</label>
          <input type="number" class="form-control" name="labourHours" step="any" 
		 required data-error="Часовете труд са задължителни и трябва да бъдат число !!!" />
	  <div class="help-block with-errors"></div>
        </div>
	<div class="form-group">

<div class="table-responsive">
<table id="tblMachineWorkItems" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="id" data-converter="numeric">№</th>
    <th data-column-id="materialName">Материал</th>
    <th data-column-id="usedQuantity">Количество</th>
    <th data-column-id="actions" data-formatter="actions" data-searchable="false" 
	data-sortable="false">Actions</th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<button id="btnCreateMachineWorkItem" type="button" class="btn btn-primary">
  Добави нов материал
</button>

<button id="btnCreateMachineWorkItemNew" type="button" class="btn btn-primary">
  Добави напълно нов материал
</button>

	</div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveMachineWork" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelMachineWork" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>

<div class="modal fade" id="dlgMachineWorkItem" tabindex="-1" role="dialog" aria-labelledby="lblTitleMachineWorkItem" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgMachineWorkItem" method="post">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXMachineWorkItem" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleMachineWorkItem">Добави/редактирай използван материал:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
	  <input type="hidden" class="form-control" name="machineWorkItemId" />
          <input type="hidden" class="form-control" name="machineWorkId" />
          <label class="control-label">Материал:</label>
          <input type="hidden" name="materialId" />
	  <div class="input-group">
	    <input type="text" class="form-control" name="materialName" onfocus="blur();" 
		   required data-error="Моля изберете материал !!!" />
	    <span class="input-group-btn">
	      <button id="btnChooseMaterial" type="button" class="btn btn-primary"
		data-toggle="tooltip" title="Изберете материал">...</button>
            </span>
	  </div>
	  <div class="help-block with-errors"></div>
        </div>
	<div class="form-group">
          <label class="control-label">Използвано кол-во:</label>
          <input type="number" class="form-control" name="usedQuantity" step="any" 
		 required data-error="Използваното количество е задължително и трябва да бъде число !!!" />
	  <div class="help-block with-errors"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveMachineWorkItem" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelMachineWorkItem" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>

<div class="modal fade" id="dlgMachineWorkItemNew" tabindex="-1" role="dialog" aria-labelledby="lblTitleMachineWorkItemNew" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgMachineWorkItemNew" method="post">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXMachineWorkItemNew" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleMachineWorkItemNew">Добави/редактирай използван материал:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
	  <input type="hidden" class="form-control" name="machineWorkItemId" />
          <input type="hidden" class="form-control" name="machineWorkId" />
          <input type="hidden" class="form-control" name="materialId" />
          <label class="control-label">Име на материал:</label>
          <input type="text" class="form-control" name="materialName" required data-error="Името на материала е задължително !!!" />
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Цена:</label>
          <input type="number" class="form-control" name="price" step="any" 
		 required data-error="Цената е задължителна и трябва да бъде число !!!" />
	  <div class="help-block with-errors"></div>
        </div>
	<div class="form-group">
          <label class="control-label">Група:</label>
          <input type="hidden" name="groupId" />
	  <div class="input-group">
	    <input type="text" class="form-control" name="groupName" onfocus="blur();" 
		   required data-error="Моля изберете група !!!" />
	    <span class="input-group-btn">
	      <button id="btnChooseGroup" type="button" class="btn btn-primary"
		data-toggle="tooltip" title="Изберете група">...</button>
            </span>
	  </div>
	  <div class="help-block with-errors"></div>
	</div>

	<div class="form-group">
          <label class="control-label">Използвано кол-во:</label>
          <input type="number" class="form-control" name="usedQuantity" step="any" 
		 required data-error="Използваното количество е задължително и трябва да бъде число !!!" />
	  <div class="help-block with-errors"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveMachineWorkItemNew" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelMachineWorkItemNew" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>

<div class="modal fade" id="dlgMachines" tabindex="-1" role="dialog" aria-labelledby="lblTitleMachines" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<!-- form id="formDlgMachines" method="post" -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXMachines" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleMachines">Избери машина:</h4>
      </div>
      <div class="modal-body">

<form id="formSearchMachines" method="get" class="form-inline">
  <div class="form-group">
    <label class="control-label">Линия:</label>
    <select class="form-control" name="lineId"></select>
    <label class="control-label">Системен №:</label>
    <input type="text" class="form-control" name="systemNumber" />
    <button type="submit" class="btn btn-primary">Търси</button>      
  </div>
</form>

<div class="table-responsive">
<table id="tblMachines" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="systemNumber" data-converter="numeric">№</th>
    <th data-column-id="model">Модел</th>
    <th data-column-id="machineType">Тип</th>
    <th data-column-id="lineName">Линия</th>
    <th data-column-id="actions" data-formatter="actions" data-searchable="false" 
	data-sortable="false">Actions</th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<button id="btnCreateMachine" type="button" class="btn btn-primary">
  Добави нова машина
</button>

      </div>
    </div>
  </div>
<!-- /form -->
</div>

<div class="modal fade" id="dlgMachine" tabindex="-1" role="dialog" aria-labelledby="lblTitleMachine" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgMachine" method="post">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXMachine" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleMachine">Добави/редактирай машина:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" class="form-control" name="machineId" />
          <label class="control-label">Системен номер:</label>
          <input type="number" class="form-control" name="systemNumber" required step="1" data-error="Системният номер е задължителен и трябва да бъде цяло число !!!"/>
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Модел:</label>
          <input type="text" class="form-control" name="model" required data-error="Моделът на машината е задължителен !!!" />
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Тип:</label>
          <input type="text" class="form-control" name="machineType" required data-error="Типът на машината е задължителен !!!" />
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Линия:</label>
          <input type="hidden" name="lineId" />
	  <div class="input-group">
	    <input type="text" class="form-control" name="lineName" onfocus="blur();" 
		   required data-error="Моля изберете линия !!!" />
	    <span class="input-group-btn">
	      <button id="btnChooseLine" type="button" class="btn btn-primary"
		data-toggle="tooltip" title="Изберете линия">...</button>
            </span>
	  </div>
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Фабричен номер:</label>
          <input type="text" class="form-control" name="fabricNumber" required data-error="Фабричният номер е задължителен !!!" />
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Инв. номер:</label>
          <input type="text" class="form-control" name="invNumber" />
        </div>
        <div class="form-group">
          <label class="control-label">Дата на закупуване:</label>
          <input type="text" class="form-control" name="dateBuy" 
		 required pattern="^\d{4}-\d{1,2}-\d{1,2}$" data-error="Датата на закупуване е задължителна и в съответния формат !!!" />
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Цена:</label>
          <input type="number" class="form-control" name="price" step="any" required data-error="Цената е задължителна и трябва да бъде число !!!" />
	  <div class="help-block with-errors"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveMachine" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelMachine" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>


<div class="modal fade" id="dlgLines" tabindex="-1" role="dialog" aria-labelledby="lblTitleLines" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<!-- form id="formDlgLines" method="post" -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXLines" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleLines">Избери линия:</h4>
      </div>
      <div class="modal-body">

<div class="table-responsive">
<form id="formSearchLines" method="get" class="form-inline">
  <div class="form-group">
    <label class="control-label">Име на линия:</label>
    <input type="text" class="form-control" name="lineName" />
    <button type="submit" class="btn btn-primary">Търси</button>      
  </div>
</form>

<table id="tblLines" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="id" data-converter="numeric">№</th>
    <th data-column-id="lineName">Име на линия</th>
    <th data-column-id="actions" data-formatter="actions" data-searchable="false" 
	data-sortable="false">Actions</th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<button id="btnCreateLine" type="button" class="btn btn-primary">
  Добави нова линия
</button>

      </div>
    </div>
  </div>
<!-- /form -->
</div>

<div class="modal fade" id="dlgLine" tabindex="-1" role="dialog" aria-labelledby="lblTitleLine" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgLine" method="post">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXLine" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleLine">Добави/редактирай линия:</h4>
      </div>
      <div class="modal-body">
	<div class="form-group">
          <input type="hidden" class="form-control" name="lineId" />
          <label class="control-label">Име на линия:</label>
          <input type="text" class="form-control" name="lineName" required data-error="Името на линия е задължително !!!"/>
	  <div class="help-block with-errors"></div>
	</div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveLine" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelLine" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>

<div class="modal fade" id="dlgMaterials" tabindex="-1" role="dialog" aria-labelledby="lblTitleMaterials" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<!-- form id="formDlgMaterials" method="post" -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXMaterials" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleMaterials">Избери материал:</h4>
      </div>
      <div class="modal-body">

<form id="formSearchMaterials" method="get" class="form-inline">
  <div class="form-group">
    <label class="control-label">Група:</label>
    <select class="form-control" name="groupId"></select>
    <label class="control-label">Име на материал:</label>
    <input type="text" class="form-control" name="materialName" />
    <button type="submit" class="btn btn-primary">Търси</button>      
  </div>
</form>

<div class="table-responsive">
<table id="tblMaterials" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="id" data-converter="numeric">№</th>
    <th data-column-id="materialName">Име на материал</th>
    <th data-column-id="groupName">Група</th>
    <th data-column-id="actions" data-formatter="actions" data-searchable="false" 
	data-sortable="false">Actions</th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<button id="btnCreateMaterial" type="button" class="btn btn-primary">
  Добави нов материал
</button>

      </div>
    </div>
  </div>
<!-- /form -->
</div>

<div class="modal fade" id="dlgMaterial" tabindex="-1" role="dialog" aria-labelledby="lblTitleMaterial" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgMaterial" method="post">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXMaterial" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleMaterial">Добави/редактирай материал:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" class="form-control" name="materialId" />
          <label class="control-label">Име на материал:</label>
          <input type="text" class="form-control" name="materialName" required data-error="Името на материала е задължително !!!" />
	  <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <label class="control-label">Цена:</label>
          <input type="number" class="form-control" name="price" step="any" 
		 required data-error="Цената е задължителна и трябва да бъде число !!!" />
	  <div class="help-block with-errors"></div>
        </div>
	<div class="form-group">
          <label class="control-label">Група:</label>
          <input type="hidden" name="groupId" />
	  <div class="input-group">
	    <input type="text" class="form-control" name="groupName" onfocus="blur();" 
		   required data-error="Моля изберете група !!!" />
	    <span class="input-group-btn">
	      <button id="btnChooseGroup" type="button" class="btn btn-primary"
		data-toggle="tooltip" title="Изберете група">...</button>
            </span>
	  </div>
	  <div class="help-block with-errors"></div>
	</div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveMaterial" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelMaterial" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>

<div class="modal fade" id="dlgGroups" tabindex="-1" role="dialog" aria-labelledby="lblTitleGroups" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<!-- form id="formDlgGroups" method="post" -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXGroups" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleGroups">Избери група:</h4>
      </div>
      <div class="modal-body">

<div class="table-responsive">
<table id="treeGroups" class="table tree">
  <thead>
  <tr>
    <th>№</th>
    <th>Име на група</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
    <tr class="treegrid-0">
      <td></td><td>Групи</td><td><button type='button' class='btn btn-primary btn-group-add' data-row-id='0'>Създай подгрупа</button></td>
    </tr>
  </tbody>
</table>
</div>

      </div>
    </div>
  </div>
<!-- /form -->
</div>

<div class="modal fade" id="dlgGroup" tabindex="-1" role="dialog" aria-labelledby="lblTitleGroup" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgGroup" method="post">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXGroup" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleGroup">Добави/редактирай група:</h4>
      </div>
      <div class="modal-body">
	<div class="form-group">
          <input type="hidden" class="form-control" name="groupId" />
          <label class="control-label">Родителска група:</label>
	  <input type="hidden" name="parentId" />
          <input type="text" class="form-control" name="parentName" readonly />
	</div>
	<div class="form-group">
          <label class="control-label">Име на група:</label>
          <input type="text" class="form-control" name="groupName" required data-error="Името на групата е задължително !!!" />
	  <div class="help-block with-errors"></div>
	</div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveGroup" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelGroup" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>


<script type="text/javascript">
// ------------ MACHINE WORK -------------------
function machine_work_initTbl()
{
  $('#formSearchMachineWork input[name="startDate"], ' +
    '#formSearchMachineWork input[name="endDate"]').datepicker({
    format: "yyyy-mm-dd",
    language: "bg",
    autoclose: true
  });
  var firstDayStr = dateToString(getFirstDayOfMonth(new Date()));
  var lastDayStr = dateToString(getLastDayOfMonth(new Date()));
  $('#formSearchMachineWork input[name="startDate"]').val(firstDayStr);
  $('#formSearchMachineWork input[name="endDate"]').val(lastDayStr);

  $('#formSearchMachineWork').submit(function() {
    machine_work_refreshTbl();
    return false;
  });

  var tbl = $('#tblMachineWork').bootgrid({
    ajax: true,
    ajaxSettings: {
      method: 'GET',
      cache: false
    },
    templates: {
      search: ""
    },
    requestHandler: function (request) {
      request.startDate = $('#formSearchMachineWork input[name="startDate"]').val();
      request.endDate = $('#formSearchMachineWork input[name="endDate"]').val();
      return request;
    },
    url: "/machine-work/findgrid",
    caseSensitive: false,
    formatters: {
      'actions' : function(column, row)
        {
          return "<button type='button' class='btn btn-primary btn-edit' data-row-id='" + row.id + "'>Редактирай</button> " +
                 "<button type='button' class='btn btn-primary btn-delete' data-row-id='" + row.id + "'>Изтрий</button>";
        }
    }
  }).on('loaded.rs.jquery.bootgrid', function() {
    tbl.find('.btn-edit').on('click', function(e) {
      var rowId = $(this).data("row-id");
      machine_work_findById(rowId, function(machineWork) {
        machine_work_setDlg(machineWork);
        $('#dlgMachineWork').modal('show');
      });
    });
    tbl.find('.btn-delete').on('click', function(e) {
      var rowId = $(this).data("row-id");
      machine_work_findById(rowId, function(machineWork) {
        if (machineWork)
        {
          bootbox.confirm('Сигурни ли сте, че искате да изтриете работа: <b>' + machineWork.description + '</b> ?', 
            function(ok) { 
              if (ok) {
                machine_work_delete(machineWork, function(data) { machine_work_refreshTbl(); });
              }
          });
        }
      });
    });
  });
}

function machine_work_refreshTbl()
{
  $('#tblMachineWork').bootgrid('reload');
}

function machine_work_findById(id, callback)
{
  rest_ajax_get('/machine-work/' + id, callback);
}

function machine_work_initBtnCreate()
{
  $('#btnCreateMachineWork').click(function (e) { 
    var machineWork = {};
    machine_work_setDlg(machineWork);
    $('#dlgMachineWork').modal('show'); 
  });
}

function machine_work_initDlg()
{
  var funcHide = function (e) { $('#dlgMachineWork').modal('hide'); };
  $('#btnXMachineWork').on('click', funcHide);
  $('#btnCancelMachineWork').on('click', funcHide);
  $('#formDlgMachineWork').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      machine_work_save();
    }
    return false;
  });
  $('#dlgMachineWork').on('shown.bs.modal', function() {
    $('#dlgMachineWork input[name="machineId"]').focus();
  });
  $('#dlgMachineWork input[name="workDate"]').datepicker({
    format: "yyyy-mm-dd",
    language: "bg",
    autoclose: true
  });
  $('#dlgMachineWork #btnChooseMachine').on('click', function() {
    machines_select = function(machine)
    {
      $('#dlgMachineWork input[name="machineId"]').val(machine.id);
      $('#dlgMachineWork input[name="machineModel"]').val(machine.model);

      var oldLineId = $('#dlgMachineWork input[name="lineId"]').val();
      if (!oldLineId || !oldLineId.length)
      {
        $('#dlgMachineWork input[name="lineId"]').val(machine.lineId);
        $('#dlgMachineWork input[name="lineName"]').val(machine.lineName);
      }
    };
    machines_deselect = function()
    {
      $('#dlgMachineWork input[name="machineId"]').val('');
      $('#dlgMachineWork input[name="machineModel"]').val('');
    };
    $('#dlgMachines').modal('show');
  });
  $('#dlgMachineWork #btnChooseLine').on('click', function() {
    lines_select = function(line)
    {
      $('#dlgMachineWork input[name="lineId"]').val(line.id);
      $('#dlgMachineWork input[name="lineName"]').val(line.lineName);
    };
    lines_deselect = function()
    {
      $('#dlgMachineWork input[name="lineId"]').val('');
      $('#dlgMachineWork input[name="lineName"]').val('');
    };
    $('#dlgLines').modal('show');
  });
}

function machine_work_setDlg(machineWork)
{
  if (machineWork)
  {
    $('#dlgMachineWork input[name="machineWorkId"]').val(machineWork.id);
    $('#dlgMachineWork input[name="machineId"]').val(machineWork.machineId);
    $('#dlgMachineWork input[name="lineId"]').val(machineWork.lineId);
    $('#dlgMachineWork input[name="workDate"]').val(machineWork.workDateStr);
    $('#dlgMachineWork textarea[name="description"]').val(machineWork.description);
    $('#dlgMachineWork input[name="labourHours"]').val(machineWork.labourHours);
    $('#dlgMachineWork input[name="machineModel"]').val(machineWork.machineModel);
    $('#dlgMachineWork input[name="lineName"]').val(machineWork.lineName);
 
    items = machineWork.items;
    if (!items) items = [];
    machine_work_items_refreshTbl();
  }
}

function machine_work_getDlg()
{
  var obj = { };
  obj.id = $('#dlgMachineWork input[name="machineWorkId"]').val();
  obj.machineId = $('#dlgMachineWork input[name="machineId"]').val();
  obj.lineId = $('#dlgMachineWork input[name="lineId"]').val();
  obj.workDate = $('#dlgMachineWork input[name="workDate"]').val();
  obj.description = $('#dlgMachineWork textarea[name="description"]').val();
  obj.labourHours = $('#dlgMachineWork input[name="labourHours"]').val();
  obj.machineModel = $('#dlgMachineWork input[name="machineModel"]').val();
  obj.lineName = $('#dlgMachineWork input[name="lineName"]').val();
  obj.items = items;

  return obj;
}

function machine_work_save()
{
  var machineWork = machine_work_getDlg();
  $('#dlgMachineWork').modal('hide');
  if (!machineWork.id)
  {
    machine_work_insert(machineWork, function(data) { machine_work_refreshTbl(); });
  }
  else
  {
    machine_work_update(machineWork, function(data) { machine_work_refreshTbl(); });
  }
}

function machine_work_insert(machineWork, callback)
{
  if (!machineWork)
    return;
  machineWork._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machine-work', 'post', machineWork, callback);
}

function machine_work_update(machineWork, callback)
{
  if (!machineWork)
    return;
  if (!machineWork.id)
    return;
  machineWork._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machine-work/' + machineWork.id, 'put', machineWork, callback);
}

function machine_work_delete(machineWork, callback)
{
  if (!machineWork)
    return;
  if (!machineWork.id)
    return;
  machineWork._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machine-work/' + machineWork.id, 'delete', machineWork, callback);
}
// ------ END OF MACHINE WORK --------------


// ------ MACHINE WORK ITEMS ---------------
var items = [];

function machine_work_items_initTbl()
{
  var tbl = $('#tblMachineWorkItems').bootgrid({
    ajax: false,
    /*ajaxSettings: {
      method: 'GET',
      cache: false
    },
    url: "/machine-work-items/findgrid",*/
    templates: {
      search: ""
    },
    caseSensitive: false,
    formatters: {
      'actions' : function(column, row)
        {
          return "<button type='button' class='btn btn-primary btn-items-edit' data-row-id='" + row.id + "'>Редактирай</button> " +
                 "<button type='button' class='btn btn-primary btn-items-delete' data-row-id='" + row.id + "'>Изтрий</button>";
        }
    }
  }).on('loaded.rs.jquery.bootgrid', function() {
    tbl.find('.btn-items-edit').on('click', function(e) {
      var rowId = $(this).data("row-id");
      machine_work_items_findById(rowId, function(machineWorkItem) {
	if (machineWorkItem.materialId)
        {
          machine_work_items_setDlg(machineWorkItem);
          $('#dlgMachineWorkItem').modal('show');
        }
        else
        {
          machine_work_items_new_setDlg(machineWorkItem);
          $('#dlgMachineWorkItemNew').modal('show');
        }
      });
    });
    tbl.find('.btn-items-delete').on('click', function(e) {
      var rowId = $(this).data("row-id");
      machine_work_items_findById(rowId, function(machineWorkItem) {
        if (machineWorkItem)
        {
          bootbox.confirm('Сигурни ли сте, че искате да изтриете използван материал: <b>' + machineWorkItem.materialName + '</b> ?', 
            function(ok) { 
              if (ok) {
                machine_work_items_delete(machineWorkItem, function(data) { machine_work_items_refreshTbl(); });
              }
          });
        }
      });
    });
  });
}

function machine_work_items_refreshTbl()
{
//  $('#tblMachineWorkItems').bootgrid('reload');
  $('#tblMachineWorkItems').bootgrid('clear');
  $('#tblMachineWorkItems').bootgrid('append', items);
}

function machine_work_items_findById(id, callback)
{
//  rest_ajax_get('/machine-work-items/' + id, callback);
  var i;
  var found = null;
  for (i=0; i<items.length; i++)
  {
    var w = items[i];
    if (w.id == id)
    {
      found = w;
      break;
    }
  }

  if (callback)
  {
    callback(found);
  }
}

function machine_work_items_initBtnCreate()
{
  $('#btnCreateMachineWorkItem').click(function (e) { 
    var machineWorkItem = { machineWorkId : '0' };
    machine_work_items_setDlg(machineWorkItem);
    $('#dlgMachineWorkItem').modal('show'); 
  });

  $('#btnCreateMachineWorkItemNew').click(function (e) { 
    var machineWorkItem = { machineWorkId : '0' };
    machine_work_items_new_setDlg(machineWorkItem);
    $('#dlgMachineWorkItemNew').modal('show'); 
  });

}

function machine_work_items_initDlg()
{
  var funcHide = function (e) { $('#dlgMachineWorkItem').modal('hide'); };
  $('#btnXMachineWorkItem').on('click', funcHide);
  $('#btnCancelMachineWorkItem').on('click', funcHide);
  $('#formDlgMachineWorkItem').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      machine_work_items_save(false);
    }
    return false;
  });
  $('#dlgMachineWorkItem').on('shown.bs.modal', function() {
    $('#dlgMachineWorkItem input[name="materialName"]').focus();
  });
  $('#dlgMachineWorkItem #btnChooseMaterial').on('click', function() {
    materials_select = function(material)
    {
      $('#dlgMachineWorkItem input[name="materialId"]').val(material.id);
      $('#dlgMachineWorkItem input[name="materialName"]').val(material.materialName);
    };
    materials_deselect = function()
    {
      $('#dlgMachineWorkItem input[name="materialId"]').val('');
      $('#dlgMachineWorkItem input[name="materialName"]').val('');
    };
    $('#dlgMaterials').modal('show');
  });

}

function machine_work_items_new_initDlg()
{
  var funcHide = function (e) { $('#dlgMachineWorkItemNew').modal('hide'); };
  $('#btnXMachineWorkItemNew').on('click', funcHide);
  $('#btnCancelMachineWorkItemNew').on('click', funcHide);
  $('#formDlgMachineWorkItemNew').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      machine_work_items_save(true);
    }
    return false;
  });
  $('#dlgMachineWorkItemNew').on('shown.bs.modal', function() {
    $('#dlgMachineWorkItemNew input[name="materialName"]').focus();
  });
  $('#dlgMachineWorkItemNew #btnChooseGroup').on('click', function() {
    groups_refreshTree();
    groups_select = function(group)
    {
      $('#dlgMachineWorkItemNew input[name="groupId"]').val(group.id);
      $('#dlgMachineWorkItemNew input[name="groupName"]').val(group.groupName);
    };
    groups_deselect = function()
    {
      $('#dlgMachineWorkItemNew input[name="groupId"]').val('');
      $('#dlgMachineWorkItemNew input[name="groupName"]').val('');
    };
    $('#dlgGroups').modal('show');
  });
}

function machine_work_items_setDlg(machineWorkItem)
{
  if (machineWorkItem)
  {
    $('#dlgMachineWorkItem input[name="machineWorkItemId"]').val(machineWorkItem.id);
    $('#dlgMachineWorkItem input[name="machineWorkId"]').val(machineWorkItem.machineWorkId);
    $('#dlgMachineWorkItem input[name="materialId"]').val(machineWorkItem.materialId);
    $('#dlgMachineWorkItem input[name="usedQuantity"]').val(machineWorkItem.usedQuantity);
    $('#dlgMachineWorkItem input[name="materialName"]').val(machineWorkItem.materialName);
  }
}

function machine_work_items_new_setDlg(machineWorkItem)
{
  if (machineWorkItem)
  {
    $('#dlgMachineWorkItemNew input[name="machineWorkItemId"]').val(machineWorkItem.id);
    $('#dlgMachineWorkItemNew input[name="machineWorkId"]').val(machineWorkItem.machineWorkId);
    $('#dlgMachineWorkItemNew input[name="materialId"]').val(machineWorkItem.materialId);
    $('#dlgMachineWorkItemNew input[name="usedQuantity"]').val(machineWorkItem.usedQuantity);
    $('#dlgMachineWorkItemNew input[name="materialName"]').val(machineWorkItem.materialName);
    $('#dlgMachineWorkItemNew input[name="price"]').val(machineWorkItem.price);
    $('#dlgMachineWorkItemNew input[name="groupId"]').val(machineWorkItem.groupId);
    $('#dlgMachineWorkItemNew input[name="groupName"]').val(machineWorkItem.groupName);
  }
}

function machine_work_items_getDlg()
{
  var obj = { };
  obj.id = $('#dlgMachineWorkItem input[name="machineWorkItemId"]').val();
  obj.machineWorkId = $('#dlgMachineWorkItem input[name="machineWorkId"]').val();
  obj.materialId = $('#dlgMachineWorkItem input[name="materialId"]').val();
  obj.usedQuantity = $('#dlgMachineWorkItem input[name="usedQuantity"]').val();
  obj.materialName = $('#dlgMachineWorkItem input[name="materialName"]').val();
  return obj;
}

function machine_work_items_new_getDlg()
{
  var obj = { };
  obj.id = $('#dlgMachineWorkItemNew input[name="machineWorkItemId"]').val();
  obj.machineWorkId = $('#dlgMachineWorkItemNew input[name="machineWorkId"]').val();
  obj.materialId = $('#dlgMachineWorkItemNew input[name="materialId"]').val();
  obj.usedQuantity = $('#dlgMachineWorkItemNew input[name="usedQuantity"]').val();
  obj.materialName = $('#dlgMachineWorkItemNew input[name="materialName"]').val();
  obj.price = $('#dlgMachineWorkItemNew input[name="price"]').val();
  obj.groupId = $('#dlgMachineWorkItemNew input[name="groupId"]').val();
  obj.groupName = $('#dlgMachineWorkItemNew input[name="groupName"]').val();

  return obj;
}

function machine_work_items_save(isNew)
{
  var machineWorkItem;
  if (isNew)
  {
    machineWorkItem = machine_work_items_new_getDlg();
  }
  else
  {
    machineWorkItem = machine_work_items_getDlg();
  }

  $('#dlgMachineWorkItem').modal('hide');
  $('#dlgMachineWorkItemNew').modal('hide');
  if (!machineWorkItem.id)
  {
    machine_work_items_insert(machineWorkItem, function(data) { machine_work_items_refreshTbl(); });
  }
  else
  {
    machine_work_items_update(machineWorkItem, function(data) { machine_work_items_refreshTbl(); });
  }
}

function machine_work_items_insert(machineWorkItem, callback)
{
  if (!machineWorkItem)
    return;
/*  machineWorkItem._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machine-work-items', 'post', machineWorkItem, callback);
*/

  var i;
  var nextId = 0;
  for (i=0; i<items.length; i++)
  {
    var w = items[i];
    if (nextId < w.id)
    {
      nextId = w.id;
    }
  }
  nextId++;

  machineWorkItem.id = nextId;
  items.push(machineWorkItem);

  if (callback)
  {
    callback({});
  }
}

function machine_work_items_update(machineWorkItem, callback)
{
  if (!machineWorkItem)
    return;
  if (!machineWorkItem.id)
    return;
/*  machineWorkItem._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machine-work-items/' + machineWorkItem.id, 'put', machineWorkItem, callback);
*/

  var i;
  for (i=0; i<items.length; i++)
  {
    var w = items[i];
    if (machineWorkItem.id == w.id)
    {
      items[i] = machineWorkItem;
      break;
    }
  }

  if (callback)
  {
    callback({});
  }
}

function machine_work_items_delete(machineWorkItem, callback)
{
  if (!machineWorkItem)
    return;
  if (!machineWorkItem.id)
    return;
/*  machineWorkItem._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machine-work-items/' + machineWorkItem.id, 'delete', machineWorkItem, callback);
*/

  var i;
  for (i=0; i<items.length; i++)
  {
    var w = items[i];
    if (machineWorkItem.id == w.id)
    {
      items.splice(i, 1);
      break;
    }
  }

  if (callback)
  {
    callback({});
  }
}
// ------ END OF MACHINE WORK ITEMS --------

// ------------- MACHINES -----------------
var machines_select, machines_deselect;

function machines_initTbl()
{
  rest_ajax_get('/lines/find', function(data) {
    var cmbLines = $('#formSearchMachines select[name="lineId"]');
    cmbLines.empty();
    cmbLines.append('<option value="">Избери линия</option>');
    if (data && (data.length > 0))
    {
      for (var i=0; i<data.length; i++)
      {
        var line = data[i];
        cmbLines.append('<option value="' + line.id + '">' + line.lineName + '</option>');
      }
    }
  });

  $('#formSearchMachines').submit(function() {
    machines_refreshTbl();
    return false;
  });

  var tbl = $('#tblMachines').bootgrid({
    ajax: true,
    ajaxSettings: {
      method: 'GET',
      cache: false
    },
    templates: {
      search: ""
    },
    requestHandler: function (request) {
      request.lineId = $('#formSearchMachines select[name="lineId"]').val();
      request.systemNumber = $('#formSearchMachines input[name="systemNumber"]').val();
      return request;
    },
    url: "/machines/findgrid",
    caseSensitive: false,
    formatters: {
      'actions' : function(column, row)
        {
          return "<button type='button' class='btn btn-primary btn-choose-machine' data-row-id='" + row.id + "'>Избери</button> " +
		 "<button type='button' class='btn btn-primary btn-edit' data-row-id='" + row.id + "'>Редактирай</button> " +
                 "<button type='button' class='btn btn-primary btn-delete' data-row-id='" + row.id + "'>Изтрий</button>";
        }
    }
  }).on('loaded.rs.jquery.bootgrid', function() {
    tbl.find('.btn-choose-machine').on('click', function(e) {
      var rowId = $(this).data("row-id");
      machines_findById(rowId, function(machine) {
	if (machines_select)
	{
	  machines_select(machine);
	};
        $('#dlgMachines').modal('hide');
      });
    });
    tbl.find('.btn-edit').on('click', function(e) {
      var rowId = $(this).data("row-id");
      machines_findById(rowId, function(machine) {
        machines_setDlg(machine);
        $('#dlgMachine').modal('show');
      });
    });
    tbl.find('.btn-delete').on('click', function(e) {
      var rowId = $(this).data("row-id");
      machines_findById(rowId, function(machine) {
        if (machine)
        {
          bootbox.confirm('Сигурни ли сте, че искате да изтриете машина: <b>' + machine.model + '</b> ? ' + 
		'Съответните работи с машини и изразходваните количества също ще бъдат изтрити. Желаете ли да направите това ?', 
            function(ok) { 
              if (ok) {
                machines_delete(machine, function(data) { machines_refreshTbl(); });
              }
          });
        }
      });
    });
  });
  $('#btnXMachines').on('click', function() {
    if (machines_deselect)
    {
      machines_deselect();
    };
    $('#dlgMachines').modal('hide');
  });
}

function machines_refreshTbl()
{
  $('#tblMachines').bootgrid('reload');
}

function machines_findById(id, callback)
{
  rest_ajax_get('/machines/' + id, callback);
}

function machines_initBtnCreate()
{
  $('#btnCreateMachine').click(function (e) { 
    var machine = {};
    machines_setDlg(machine);
    $('#dlgMachine').modal('show'); 
  });
}

function machines_initDlg()
{
  var funcHide = function (e) { $('#dlgMachine').modal('hide'); };
  $('#btnXMachine').on('click', funcHide);
  $('#btnCancelMachine').on('click', funcHide);
  $('#formDlgMachine').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      machines_save();
    }
    return false;
  });
  $('#dlgMachine').on('shown.bs.modal', function() {
    $('#dlgMachine input[name="systemNumber"]').focus();
  });
  $('#dlgMachine #btnChooseLine').on('click', function() {
    lines_select = function(line)
    {
      $('#dlgMachine input[name="lineId"]').val(line.id);
      $('#dlgMachine input[name="lineName"]').val(line.lineName);
    };
    lines_deselect = function()
    {
      $('#dlgMachine input[name="lineId"]').val('');
      $('#dlgMachine input[name="lineName"]').val('');
    };
    $('#dlgLines').modal('show');
  });
  $('#dlgMachine input[name="dateBuy"]').datepicker({
    format: "yyyy-mm-dd",
    language: "bg",
    autoclose: true
  });
}

function machines_setDlg(machine)
{
  if (machine)
  {
    $('#dlgMachine input[name="machineId"]').val(machine.id);
    $('#dlgMachine input[name="systemNumber"]').val(machine.systemNumber);
    $('#dlgMachine input[name="model"]').val(machine.model);
    $('#dlgMachine input[name="machineType"]').val(machine.machineType);
    $('#dlgMachine input[name="lineId"]').val(machine.lineId);
    $('#dlgMachine input[name="fabricNumber"]').val(machine.fabricNumber);
    $('#dlgMachine input[name="invNumber"]').val(machine.invNumber);
    $('#dlgMachine input[name="dateBuy"]').val(machine.dateBuyStr);
    $('#dlgMachine input[name="price"]').val(machine.price);
    $('#dlgMachine input[name="lineName"]').val(machine.lineName);
  }
}

function machines_getDlg()
{
  var obj = { };
  obj.id = $('#dlgMachine input[name="machineId"]').val();
  obj.systemNumber = $('#dlgMachine input[name="systemNumber"]').val();
  obj.model = $('#dlgMachine input[name="model"]').val();
  obj.machineType = $('#dlgMachine input[name="machineType"]').val();
  obj.lineId = $('#dlgMachine input[name="lineId"]').val();
  obj.fabricNumber = $('#dlgMachine input[name="fabricNumber"]').val();
  obj.invNumber = $('#dlgMachine input[name="invNumber"]').val();
  obj.dateBuy = $('#dlgMachine input[name="dateBuy"]').val();
  obj.price = $('#dlgMachine input[name="price"]').val();
  obj.lineName = $('#dlgMachine input[name="lineName"]').val();

  return obj;
}

function machines_save()
{
  var machine = machines_getDlg();
  $('#dlgMachine').modal('hide');
  if (!machine.id)
  {
    machines_insert(machine, function(data) { machines_refreshTbl(); });
  }
  else
  {
    machines_update(machine, function(data) { machines_refreshTbl(); });
  }
}

function machines_insert(machine, callback)
{
  if (!machine)
    return;
  machine._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machines', 'post', machine, callback);
}

function machines_update(machine, callback)
{
  if (!machine)
    return;
  if (!machine.id)
    return;
  machine._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machines/' + machine.id, 'put', machine, callback);
}

function machines_delete(machine, callback)
{
  if (!machine)
    return;
  if (!machine.id)
    return;
  machine._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/machines/' + machine.id, 'delete', machine, callback);
}
// --------- END OF MACHINES --------------

// -------------- LINES -------------------
var lines_select, lines_deselect;

function lines_initTbl()
{
  $('#formSearchLines').submit(function() {
    lines_refreshTbl();
    return false;
  });

  var tbl = $('#tblLines').bootgrid({
    ajax: true,
    ajaxSettings: {
      method: 'GET',
      cache: false
    },
    templates: {
      search: ""
    },
    requestHandler: function (request) {
      request.lineName = $('#formSearchLines input[name="lineName"]').val();
      return request;
    },
    url: "/lines/findgrid",
    caseSensitive: false,
    formatters: {
      'actions' : function(column, row)
        {
          return "<button type='button' class='btn btn-primary btn-choose-line' data-row-id='" + row.id + "'>Избери</button> " +
		 "<button type='button' class='btn btn-primary btn-edit' data-row-id='" + row.id + "'>Редактирай</button> " +
                 "<button type='button' class='btn btn-primary btn-delete' data-row-id='" + row.id + "'>Изтрий</button>";
        }
    }
  }).on('loaded.rs.jquery.bootgrid', function() {
    tbl.find('.btn-choose-line').on('click', function(e) {
      var rowId = $(this).data("row-id");
      lines_findById(rowId, function(line) {
	if (lines_select)
	{
	  lines_select(line);
	};
        $('#dlgLines').modal('hide');
      });
    });
    tbl.find('.btn-edit').on('click', function(e) {
      var rowId = $(this).data("row-id");
      lines_findById(rowId, function(line) {
        lines_setDlg(line);
        $('#dlgLine').modal('show');
      });
    });
    tbl.find('.btn-delete').on('click', function(e) {
      var rowId = $(this).data("row-id");
      lines_findById(rowId, function(line) {
        if (line)
        {
          bootbox.confirm('Сигурни ли сте, че искате да изтриете линия: <b>' + line.lineName + '</b> ? ' +
		'Съответните машини, работа с тях, както и изразходваните количества също ще бъдат изтрити. ' +
		'Желаете ли да направите това ?',
            function(ok) { 
              if (ok) {
                lines_delete(line, function(data) { lines_refreshTbl(); });
              }
          });
        }
      });
    });
  });
  $('#btnXLines').on('click', function() {
    if (lines_deselect)
    {
      lines_deselect();
    };
    $('#dlgLines').modal('hide');
  });
}

function lines_refreshTbl()
{
  $('#tblLines').bootgrid('reload');
}

function lines_findById(id, callback)
{
  rest_ajax_get('/lines/' + id, callback);
}

function lines_initBtnCreate()
{
  $('#btnCreateLine').click(function (e) { 
    var line = {};
    lines_setDlg(line);
    $('#dlgLine').modal('show'); 
  });
}

function lines_initDlg()
{
  var funcHide = function (e) { $('#dlgLine').modal('hide'); };
  $('#btnXLine').on('click', funcHide);
  $('#btnCancelLine').on('click', funcHide);
  $('#formDlgLine').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      lines_save();
    }
    return false;
  });
  $('#dlgLine').on('shown.bs.modal', function() {
    $('#dlgLine input[name="lineName"]').focus();
  });
}

function lines_setDlg(line)
{
  if (line)
  {
    $('#dlgLine input[name="lineId"]').val(line.id);
    $('#dlgLine input[name="lineName"]').val(line.lineName);
  }
}

function lines_getDlg()
{
  var obj = { id : '', lineName : '' };
  obj.id = $('#dlgLine input[name="lineId"]').val();
  obj.lineName = $('#dlgLine input[name="lineName"]').val();
  return obj;
}

function lines_save()
{
  var line = lines_getDlg();
  $('#dlgLine').modal('hide');
  if (!line.id)
  {
    lines_insert(line, function(data) { lines_refreshTbl(); });
  }
  else
  {
    lines_update(line, function(data) { lines_refreshTbl(); });
  }
}

function lines_insert(line, callback)
{
  if (!line)
    return;
  line._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/lines', 'post', line, callback);
}

function lines_update(line, callback)
{
  if (!line)
    return;
  if (!line.id)
    return;
  line._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/lines/' + line.id, 'put', line, callback);
}

function lines_delete(line, callback)
{
  if (!line)
    return;
  if (!line.id)
    return;
  line._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/lines/' + line.id, 'delete', line, callback);
}
// ---------- END OF LINES ----------------

// ------------ MATERIALS -----------------
function materials_initTbl()
{
  rest_ajax_get('/groups/find', function(data) {
    var cmbGroups = $('#formSearchMaterials select[name="groupId"]');
    cmbGroups.empty();
    cmbGroups.append('<option value="">Избери група</option>');
    if (data && (data.length > 0))
    {
      for (var i=0; i<data.length; i++)
      {
        var group = data[i];
        cmbGroups.append('<option value="' + group.id + '">' + group.groupName + '</option>');
      }
    }
  });

  $('#formSearchMaterials').submit(function() {
    materials_refreshTbl();
    return false;
  });

  var tbl = $('#tblMaterials').bootgrid({
    ajax: true,
    ajaxSettings: {
      method: 'GET',
      cache: false
    },
    templates: {
      search: ""
    },
    requestHandler: function (request) {
      request.groupId = $('#formSearchMaterials select[name="groupId"]').val();
      request.materialName = $('#formSearchMaterials input[name="materialName"]').val();
      return request;
    },
    url: "/materials/findgrid",
    caseSensitive: false,
    formatters: {
      'actions' : function(column, row)
        {
          return "<button type='button' class='btn btn-primary btn-choose-material' data-row-id='" + row.id + "'>Избери</button> " + 			 "<button type='button' class='btn btn-primary btn-edit' data-row-id='" + row.id + "'>Редактирай</button> " +
                 "<button type='button' class='btn btn-primary btn-delete' data-row-id='" + row.id + "'>Изтрий</button>";
        }
    }
  }).on('loaded.rs.jquery.bootgrid', function() {
    tbl.find('.btn-choose-material').on('click', function(e) {
      var rowId = $(this).data("row-id");
      materials_findById(rowId, function(material) {
	if (materials_select)
	{
	  materials_select(material);
	};
        $('#dlgMaterials').modal('hide');
      });
    });
    tbl.find('.btn-edit').on('click', function(e) {
      var rowId = $(this).data("row-id");
      materials_findById(rowId, function(material) {
        materials_setDlg(material);
        $('#dlgMaterial').modal('show');
      });
    });
    tbl.find('.btn-delete').on('click', function(e) {
      var rowId = $(this).data("row-id");
      materials_findById(rowId, function(material) {
        if (material)
        {
          bootbox.confirm('Сигурни ли сте, че искате да изтриете материал: <b>' + material.materialName + '</b> ? ' +
		'Съответните доставени и изразходвани количества също ще бъдат изтрити. Желаете ли да направите това ?', 
            function(ok) { 
              if (ok) {
                materials_delete(material, function(data) { materials_refreshTbl(); });
              }
          });
        }
      });
    });
  });
  $('#btnXMaterials').on('click', function() {
    if (materials_deselect)
    {
      materials_deselect();
    };
    $('#dlgMaterials').modal('hide');
  });
}

function materials_refreshTbl()
{
  $('#tblMaterials').bootgrid('reload');
}

function materials_findById(id, callback)
{
  rest_ajax_get('/materials/' + id, callback);
}

function materials_initBtnCreate()
{
  $('#btnCreateMaterial').click(function (e) { 
    var material = {};
    materials_setDlg(material);
    $('#dlgMaterial').modal('show'); 
  });
}

function materials_initDlg()
{
  var funcHide = function (e) { $('#dlgMaterial').modal('hide'); };
  $('#btnXMaterial').on('click', funcHide);
  $('#btnCancelMaterial').on('click', funcHide);
  $('#formDlgMaterial').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      materials_save();
    }
    return false;
  });
  $('#dlgMaterial').on('shown.bs.modal', function() {
    $('#dlgMaterial input[name="materialName"]').focus();
  });
  $('#dlgMaterial #btnChooseGroup').on('click', function() {
    groups_refreshTree();
    groups_select = function(group)
    {
      $('#dlgMaterial input[name="groupId"]').val(group.id);
      $('#dlgMaterial input[name="groupName"]').val(group.groupName);
    };
    groups_deselect = function()
    {
      $('#dlgMaterial input[name="groupId"]').val('');
      $('#dlgMaterial input[name="groupName"]').val('');
    };
    $('#dlgGroups').modal('show');
  });
}

function materials_setDlg(material)
{
  if (material)
  {
    $('#dlgMaterial input[name="materialId"]').val(material.id);
    $('#dlgMaterial input[name="materialName"]').val(material.materialName);
    $('#dlgMaterial input[name="price"]').val(material.price);
    $('#dlgMaterial input[name="groupId"]').val(material.groupId);
    $('#dlgMaterial input[name="groupName"]').val(material.groupName);
  }
}

function materials_getDlg()
{
  var obj = { };
  obj.id = $('#dlgMaterial input[name="materialId"]').val();
  obj.materialName = $('#dlgMaterial input[name="materialName"]').val();
  obj.price = $('#dlgMaterial input[name="price"]').val();
  obj.groupId = $('#dlgMaterial input[name="groupId"]').val();
  obj.groupName = $('#dlgMaterial input[name="groupName"]').val();
  return obj;
}

function materials_save()
{
  var material = materials_getDlg();
  $('#dlgMaterial').modal('hide');
  if (!material.id)
  {
    materials_insert(material, function(data) { materials_refreshTbl(); });
  }
  else
  {
    materials_update(material, function(data) { materials_refreshTbl(); });
  }
}

function materials_insert(material, callback)
{
  if (!material)
    return;
  material._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/materials', 'post', material, callback);
}

function materials_update(material, callback)
{
  if (!material)
    return;
  if (!material.id)
    return;
  material._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/materials/' + material.id, 'put', material, callback);
}

function materials_delete(material, callback)
{
  if (!material)
    return;
  if (!material.id)
    return;
  material._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/materials/' + material.id, 'delete', material, callback);
}
// -------- END OF MATERIALS -----------------

// ------------- GROUPS ---------------------
var groups_select, groups_deselect;

function groups_findNode(parentId, callback)
{
  var url = '/groups/find?parentId=';
  if (!parentId)
  {
    url += '0';
  }
  else
  {
    url += parentId;
  }
  rest_ajax_get_sync(url, callback);
}

function groups_refreshTree()
{
  var depth = 0;
  var result = [];
  var funcRecursive = function (data) {
    if (data && (data.length > 0))
    {
      for (var i=0; i<data.length; i++)
      {
        var group = data[i];
        result.push(group);
        depth++;
        groups_findNode(group.id, funcRecursive);
        depth--;
      }
    }

    if (!depth)
    {
      var tblBody = $('#treeGroups tbody');
      tblBody.empty();
      tblBody.append('<tr class="treegrid-0"><td></td><td>Групи</td><td><button type="button" class="btn btn-primary btn-group-add" data-row-id="0">Създай подгрупа</button></td></tr>');
      for (var i=0; i<result.length; i++)
      {
        var group = result[i];
        var pid = 0;
        if (group.parentId)
        {
          pid = group.parentId;
        }
        var buttons = "<button type='button' class='btn btn-primary btn-choose-group' data-row-id='" + group.id + "'>Избери</button> " +
		"<button type='button' class='btn btn-primary btn-group-add' data-row-id='" + group.id + "'>Създай подгрупа</button> " + 
		"<button type='button' class='btn btn-primary btn-group-edit' data-row-id='" + group.id + "'>Редактирай</button> " +
		"<button type='button' class='btn btn-primary btn-group-delete' data-row-id='" + group.id + "'>Изтрий</button>";
        tblBody.append('<tr class="treegrid-' + group.id + ' treegrid-parent-' + pid + '"><td>' + group.id + '</td>' +
			'<td>' + group.groupName + '</td><td>' + buttons + '</td>');
      }
      $('.btn-choose-group').on('click', function(e) {
        var rowId = $(this).data("row-id");
        groups_findById(rowId, function(group) {
	  if (groups_select)
	  {
	    groups_select(group);
	  };
          $('#dlgGroups').modal('hide');
        });
      });
      $('.btn-group-add').on('click', function() {
        var rowId = $(this).data("row-id");
	groups_findById(rowId, function(parGroup) {
          var group = { parentId: parGroup.id, parentName: parGroup.groupName };
          groups_setDlg(group);
          $('#dlgGroup').modal('show');
        });
      });
      $('.btn-group-edit').on('click', function(e) {
        var rowId = $(this).data("row-id");
        groups_findById(rowId, function(group) {
          groups_setDlg(group);
          $('#dlgGroup').modal('show');
        });
      });
      $('.btn-group-delete').on('click', function(e) {
        var rowId = $(this).data("row-id");
        groups_findById(rowId, function(group) {
          if (group)
          {
            bootbox.confirm('Сигурни ли сте, че искате да изтриете група: <b>' + group.groupName + '</b> ? ' + 
		'Съответните подгрупи, материали, както и прилежащите им доставени и изразходвани количества също ще ' +
		'бъдат изтрити. Желаете ли да направите това ?',  
              function(ok) { 
                if (ok) {
                  groups_delete(group, function(data) { groups_refreshTree(); });
                }
            });
          }
        });
      });
      $('#btnXGroups').on('click', function() {
        if (groups_deselect)
        {
          groups_deselect();
        };
        $('#dlgGroups').modal('hide');
      });

      $('#treeGroups').treegrid();
    }
  };
  groups_findNode(null, funcRecursive);
}

function groups_findById(id, callback)
{
  rest_ajax_get('/groups/' + id, callback);
}

function groups_initDlg()
{
  var funcHide = function (e) { $('#dlgGroup').modal('hide'); };
  $('#btnXGroup').on('click', funcHide);
  $('#btnCancelGroup').on('click', funcHide);
  $('#formDlgGroup').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      groups_save();
    }
    return false;
  });
  $('#dlgGroup').on('shown.bs.modal', function() {
    $('#dlgGroup input[name="groupName"]').focus();
  });
}

function groups_setDlg(group)
{
  if (group)
  {
    $('#dlgGroup input[name="groupId"]').val(group.id);
    $('#dlgGroup input[name="groupName"]').val(group.groupName);
    $('#dlgGroup input[name="parentId"]').val(group.parentId);
    $('#dlgGroup input[name="parentName"]').val(group.parentName);
  }
}

function groups_getDlg()
{
  var obj = { };
  obj.id = $('#dlgGroup input[name="groupId"]').val();
  obj.groupName = $('#dlgGroup input[name="groupName"]').val();
  obj.parentId = $('#dlgGroup input[name="parentId"]').val();
  obj.parentName = $('#dlgGroup input[name="parentName"]').val();
  return obj;
}

function groups_save()
{
  var group = groups_getDlg();
  $('#dlgGroup').modal('hide');
  if (!group.id)
  {
    groups_insert(group, function(data) { groups_refreshTree(); });
  }
  else
  {
    groups_update(group, function(data) { groups_refreshTree(); });
  }
}

function groups_insert(group, callback)
{
  if (!group)
    return;
  group._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/groups', 'post', group, callback);
}

function groups_update(group, callback)
{
  if (!group)
    return;
  if (!group.id)
    return;
  group._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/groups/' + group.id, 'put', group, callback);
}

function groups_delete(group, callback)
{
  if (!group)
    return;
  if (!group.id)
    return;
  group._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/groups/' + group.id, 'delete', group, callback);
}
// --------- END OF GROUPS ------------------

$(document).ready(function() {
  machine_work_initTbl();
  machine_work_initBtnCreate();
  machine_work_initDlg();

  machine_work_items_initTbl();
  machine_work_items_initBtnCreate();
  machine_work_items_initDlg();
  machine_work_items_new_initDlg()

  machines_initTbl();
  machines_initBtnCreate();
  machines_initDlg();

  lines_initTbl();
  lines_initBtnCreate();
  lines_initDlg();

  materials_initTbl();
  materials_initBtnCreate();
  materials_initDlg();

//  groups_refreshTree();
  groups_initDlg();

  fixModalScrollBars();
  initToolTips();
});
</script>
@endsection

