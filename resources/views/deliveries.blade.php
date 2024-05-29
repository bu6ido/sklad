@extends('column2')

@section('title_section') Доставки @endsection

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
      <li role="navigation" class="active"><a href="/deliveries">&nbsp;Доставки</a></li>
      <li role="navigation"><a href="/machine-work">&nbsp;Работа с машини</a></li>
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
    <h1 class="panel-title page-header">Доставки</h1>
  </div>
  <div class="panel-body">

<form id="formSearchDeliveries" method="get" class="form-inline">
  <div class="form-group">
    <label class="control-label">Начална дата:</label>
    <input type="text" class="form-control" name="startDate" />
    <label class="control-label">Крайна дата:</label>
    <input type="text" class="form-control" name="endDate" />
    <button type="submit" class="btn btn-primary">Търси</button>      
  </div>
</form>

<div class="table-responsive">
<table id="tblDeliveries" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="id" data-converter="numeric">№</th>
    <th data-column-id="description">Описание</th>
    <th data-column-id="deliveryDateStr">Дата</th>
    <th data-column-id="actions" data-formatter="actions" data-searchable="false" 
	data-sortable="false">Actions</th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<button id="btnCreateDelivery" type="button" class="btn btn-primary">
  Добави нова доставка
</button>

  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="dlgDelivery" tabindex="-1" role="dialog" aria-labelledby="lblTitleDelivery" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgDelivery" method="post">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXDelivery" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleDelivery">Добави/редактирай доставка:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" class="form-control" name="deliveryId" />
          <label class="control-label">Описание:</label>
          <input type="text" class="form-control" name="description" required data-error="Описанието на доставката е задължително !!!" />
	  <div class="help-block with-errors"></div>
        </div>
	<div class="form-group">
          <label class="control-label">Дата на доставка:</label>
          <input type="text" class="form-control" name="deliveryDate"
		 required pattern="^\d{4}-\d{1,2}-\d{1,2}$" data-error="Датата на доставка е задължителна и в съответния формат !!!" />
	  <div class="help-block with-errors"></div>
        </div>
	<div class="form-group">

<div class="table-responsive">
<table id="tblDelivItems" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="id" data-converter="numeric">№</th>
    <th data-column-id="materialName">Материал</th>
    <th data-column-id="quantity">Количество</th>
    <th data-column-id="actions" data-formatter="actions" data-searchable="false" 
	data-sortable="false">Actions</th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<button id="btnCreateDelivItem" type="button" class="btn btn-primary">
  Добави нов материал
</button>

	</div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveDelivery" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelDelivery" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>

<div class="modal fade" id="dlgDelivItem" tabindex="-1" role="dialog" aria-labelledby="lblTitleDelivItem" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgDelivItem" method="post">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXDelivItem" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleDelivItem">Добави/редактирай доставен материал:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
	  <input type="hidden" class="form-control" name="delivItemId" />
          <input type="hidden" class="form-control" name="deliveryId" />
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
          <label class="control-label">Количество:</label>
          <input type="number" class="form-control" name="quantity" step="any"
		 required data-error="Количеството е задължително и трябва да бъде число !!!" />
	  <div class="help-block with-errors"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveDelivItem" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelDelivItem" type="button" class="btn">Откажи</button>
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
// ----- DELIVERIES ----------
function deliveries_initTbl()
{
  $('#formSearchDeliveries input[name="startDate"], ' +
    '#formSearchDeliveries input[name="endDate"]').datepicker({
    format: "yyyy-mm-dd",
    language: "bg",
    autoclose: true
  });
  var firstDayStr = dateToString(getFirstDayOfMonth(new Date()));
  var lastDayStr = dateToString(getLastDayOfMonth(new Date()));
  $('#formSearchDeliveries input[name="startDate"]').val(firstDayStr);
  $('#formSearchDeliveries input[name="endDate"]').val(lastDayStr);

  $('#formSearchDeliveries').submit(function() {
    deliveries_refreshTbl();
    return false;
  });

  var tbl = $('#tblDeliveries').bootgrid({
    ajax: true,
    ajaxSettings: {
      method: 'GET',
      cache: false
    },
    templates: {
      search: ""
    },
    requestHandler: function (request) {
      request.startDate = $('#formSearchDeliveries input[name="startDate"]').val();
      request.endDate = $('#formSearchDeliveries input[name="endDate"]').val();
      return request;
    },
    url: "/deliveries/findgrid",
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
      deliveries_findById(rowId, function(delivery) {
        deliveries_setDlg(delivery);
        $('#dlgDelivery').modal('show');
      });
    });
    tbl.find('.btn-delete').on('click', function(e) {
      var rowId = $(this).data("row-id");
      deliveries_findById(rowId, function(delivery) {
        if (delivery)
        {
          bootbox.confirm('Сигурни ли сте, че искате да изтриете доставка: <b>' + delivery.description + '</b> ?', 
            function(ok) { 
              if (ok) {
                deliveries_delete(delivery, function(data) { deliveries_refreshTbl(); });
              }
          });
        }
      });
    });
  });
}

function deliveries_refreshTbl()
{
  $('#tblDeliveries').bootgrid('reload');
}

function deliveries_findById(id, callback)
{
  rest_ajax_get('/deliveries/' + id, callback);
}

function deliveries_initBtnCreate()
{
  $('#btnCreateDelivery').click(function (e) { 
    var delivery = {};
    deliveries_setDlg(delivery);
    $('#dlgDelivery').modal('show'); 
  });
}

function deliveries_initDlg()
{
  var funcHide = function (e) { $('#dlgDelivery').modal('hide'); };
  $('#btnXDelivery').on('click', funcHide);
  $('#btnCancelDelivery').on('click', funcHide);
  $('#formDlgDelivery').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      deliveries_save();
    }
    return false;
  });
  $('#dlgDelivery').on('shown.bs.modal', function() {
    $('#dlgDelivery input[name="description"]').focus();
  });
  $('#dlgDelivery input[name="deliveryDate"]').datepicker({
    format: "yyyy-mm-dd",
    language: "bg",
    autoclose: true
  });
}

function deliveries_setDlg(delivery)
{
  if (delivery)
  {
    $('#dlgDelivery input[name="deliveryId"]').val(delivery.id);
    $('#dlgDelivery input[name="deliveryDate"]').val(delivery.deliveryDateStr);
    $('#dlgDelivery input[name="description"]').val(delivery.description);

    items = delivery.items;
    if (!items) items = [];
    delivitems_refreshTbl();
  }
}

function deliveries_getDlg()
{
  var obj = { };
  obj.id = $('#dlgDelivery input[name="deliveryId"]').val();
  obj.deliveryDate = $('#dlgDelivery input[name="deliveryDate"]').val();
  obj.description = $('#dlgDelivery input[name="description"]').val();
  obj.items = items;

  return obj;
}

function deliveries_save()
{
  var delivery = deliveries_getDlg();
  $('#dlgDelivery').modal('hide');
  if (!delivery.id)
  {
    deliveries_insert(delivery, function(data) { deliveries_refreshTbl(); });
  }
  else
  {
    deliveries_update(delivery, function(data) { deliveries_refreshTbl(); });
  }
}

function deliveries_insert(delivery, callback)
{
  if (!delivery)
    return;
  delivery._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/deliveries', 'post', delivery, callback);
}

function deliveries_update(delivery, callback)
{
  if (!delivery)
    return;
  if (!delivery.id)
    return;
  delivery._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/deliveries/' + delivery.id, 'put', delivery, callback);
}

function deliveries_delete(delivery, callback)
{
  if (!delivery)
    return;
  if (!delivery.id)
    return;
  delivery._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/deliveries/' + delivery.id, 'delete', delivery, callback);
}
// --------- END OF DELIVERIES -----------

// --------- DELIVERY ITEMS --------------
var items = [];

function delivitems_initTbl()
{
  var tbl = $('#tblDelivItems').bootgrid({
    ajax: false,
    /*ajaxSettings: {
      method: 'GET',
      cache: false
    },
    url: "/deliv-items/findgrid",*/
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
      delivitems_findById(rowId, function(delivItem) {
        delivitems_setDlg(delivItem);
        $('#dlgDelivItem').modal('show');
      });
    });
    tbl.find('.btn-items-delete').on('click', function(e) {
      var rowId = $(this).data("row-id");
      delivitems_findById(rowId, function(delivItem) {
        if (delivItem)
        {
          bootbox.confirm('Сигурни ли сте, че искате да изтриете доставен материал: <b>' + delivItem.materialName + '</b> ?', 
            function(ok) { 
              if (ok) {
                delivitems_delete(delivItem, function(data) { delivitems_refreshTbl(); });
              }
          });
        }
      });
    });
  });
}

function delivitems_refreshTbl()
{
//  $('#tblDelivItems').bootgrid('reload');
  $('#tblDelivItems').bootgrid('clear');
  $('#tblDelivItems').bootgrid('append', items);
}

function delivitems_findById(id, callback)
{
//  rest_ajax_get('/deliv-items/' + id, callback);
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

function delivitems_initBtnCreate()
{
  $('#btnCreateDelivItem').click(function (e) { 
    var delivItem = { deliveryId : '0' };
    delivitems_setDlg(delivItem);
    $('#dlgDelivItem').modal('show'); 
  });
}

function delivitems_initDlg()
{
  var funcHide = function (e) { $('#dlgDelivItem').modal('hide'); };
  $('#btnXDelivItem').on('click', funcHide);
  $('#btnCancelDelivItem').on('click', funcHide);
  $('#formDlgDelivItem').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      delivitems_save();
    }
    return false;
  });
  $('#dlgDelivItem').on('shown.bs.modal', function() {
    $('#dlgDelivItem input[name="materialName"]').focus();
  });
  $('#dlgDelivItem #btnChooseMaterial').on('click', function() {
    materials_select = function(mat)
    {
      $('#dlgDelivItem input[name="materialId"]').val(mat.id);
      $('#dlgDelivItem input[name="materialName"]').val(mat.materialName);
    };
    materials_deselect = function()
    {
      $('#dlgDelivItem input[name="materialId"]').val('');
      $('#dlgDelivItem input[name="materialName"]').val('');
    };
    $('#dlgMaterials').modal('show');
  });
}

function delivitems_setDlg(delivItem)
{
  if (delivItem)
  {
    $('#dlgDelivItem input[name="delivItemId"]').val(delivItem.id);
    $('#dlgDelivItem input[name="deliveryId"]').val(delivItem.deliveryId);
    $('#dlgDelivItem input[name="materialId"]').val(delivItem.materialId);
    $('#dlgDelivItem input[name="quantity"]').val(delivItem.quantity);
    $('#dlgDelivItem input[name="materialName"]').val(delivItem.materialName);
  }
}

function delivitems_getDlg()
{
  var obj = { };
  obj.id = $('#dlgDelivItem input[name="delivItemId"]').val();
  obj.deliveryId = $('#dlgDelivItem input[name="deliveryId"]').val();
  obj.materialId = $('#dlgDelivItem input[name="materialId"]').val();
  obj.quantity = $('#dlgDelivItem input[name="quantity"]').val();
  obj.materialName = $('#dlgDelivItem input[name="materialName"]').val();

  return obj;
}

function delivitems_save()
{
  var delivItem = delivitems_getDlg();
  $('#dlgDelivItem').modal('hide');
  if (!delivItem.id)
  {
    delivitems_insert(delivItem, function(data) { delivitems_refreshTbl(); });
  }
  else
  {
    delivitems_update(delivItem, function(data) { delivitems_refreshTbl(); });
  }
}

function delivitems_insert(delivItem, callback)
{
  if (!delivItem)
    return;
/*  delivItem._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/deliv-items', 'post', delivItem, callback);
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

  delivItem.id = nextId;
  items.push(delivItem);

  if (callback)
  {
    callback({});
  }
}

function delivitems_update(delivItem, callback)
{
  if (!delivItem)
    return;
  if (!delivItem.id)
    return;
/*  delivItem._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/deliv-items/' + delivItem.id, 'put', delivItem, callback);
*/

  var i;
  for (i=0; i<items.length; i++)
  {
    var w = items[i];
    if (delivItem.id == w.id)
    {
      items[i] = delivItem;
      break;
    }
  }

  if (callback)
  {
    callback({});
  }
}

function delivitems_delete(delivItem, callback)
{
  if (!delivItem)
    return;
  if (!delivItem.id)
    return;
/*  delivItem._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/deliv-items/' + delivItem.id, 'delete', delivItem, callback);
*/

  var i;
  for (i=0; i<items.length; i++)
  {
    var w = items[i];
    if (delivItem.id == w.id)
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
// ------- END OF DELIVERY ITEMS ---------

// ------------ MATERIALS -----------------
var materials_select, materials_deselect;

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
          return "<button type='button' class='btn btn-primary btn-choose-material' data-row-id='" + row.id + "'>Избери</button> " +
		 "<button type='button' class='btn btn-primary btn-edit' data-row-id='" + row.id + "'>Редактирай</button> " +
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
  deliveries_initTbl();
  deliveries_initBtnCreate();
  deliveries_initDlg();

  delivitems_initTbl();
  delivitems_initBtnCreate();
  delivitems_initDlg();

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

