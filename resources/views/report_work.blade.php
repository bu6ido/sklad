@extends('column2')

@section('title_section') Изразходвани материали и труд @endsection

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
      <li role="navigation"><a href="/machine-work">&nbsp;Работа с машини</a></li>
    </ul>
  </li>

  <li role="navigation">
    <ul class="nav nav-pills">
      <li role="navigation"><a href="#" data-toggle="collapse" data-target="#reportsMenu"><b>Отчети</b> <span class="caret"></span></a></li>
    </ul>
    <ul id="reportsMenu" class="nav nav-pills nav-stacked in">
      <li role="navigation"><a href="/report-delivered">&nbsp;Доставени материали</a></li>
      <li role="navigation" class="active"><a href="/report-work">&nbsp;Изразходвани материали и труд</a></li>
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
    <h1 class="panel-title page-header">Справка - Изразходвани материали и труд</h1>
  </div>
  <div class="panel-body">

<form id="formSearchWork" method="get" class="form-horizontal">
  <div class="form-group">
    <label class="control-label col-sm-4 col-md-3">Начална дата:</label>
    <div class="col-sm-8 col-md-3">
      <input type="text" class="form-control" name="startDate" />
    </div>
    <label class="control-label col-sm-4 col-md-3">Крайна дата:</label>
    <div class="col-sm-8 col-md-3">
      <input type="text" class="form-control" name="endDate" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-4 col-md-3">Линия:</label>
    <div class="col-sm-8 col-md-3">
      <select class="form-control" name="lineId"></select>
    </div>
    <label class="control-label col-sm-4 col-md-3">Машина:</label>
    <div class="col-sm-8 col-md-3">
      <select class="form-control" name="machineId"></select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-4 col-md-3">Материал:</label>
    <div class="col-sm-8 col-md-3">
      <select class="form-control" name="materialId"></select>
    </div>
    <label class="control-label col-sm-4 col-md-3">Група:</label>
    <div class="col-sm-8 col-md-3">
      <select class="form-control" name="groupId"></select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-12 col-md-6 col-md-push-3">
      <button type="submit" class="btn btn-primary">Търси</button>      
      <button type="button" class="btn btn-primary" id="btnExport">Експорт към Excel</button>
    </div>
  </div>
</form>

<div class="table-responsive">
<table id="tblWork" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="work_date">Дата</th>
    <th data-column-id="machine_model">Машина</th>
    <th data-column-id="machine_type" data-visible="false">Тип на машина</th>
    <th data-column-id="machine_fabric_number" data-visible="false">Фабричен №</th>
    <th data-column-id="machine_inv_number" data-visible="false">Инвентарен №</th>
    <th data-column-id="line_name">Линия</th>
    <th data-column-id="material_name">Материал</th>
    <th data-column-id="material_price">Цена(лв.)</th>
    <th data-column-id="used_quantity">Количество</th>
    <th data-column-id="sum">Стойност(лв.)</th>
    <th data-column-id="labour_hours">Труд(ч)</th>
    <th data-column-id="labour_sum">Труд(лв.)</th>
    <th data-column-id="status" data-type="numeric" data-visible="false">Status</th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

  </div>
</div>

<!-- Modal -->
<script type="text/javascript">
function report_work_initTbl()
{
  $('#formSearchWork input[name="startDate"], ' +
    '#formSearchWork input[name="endDate"]').datepicker({
    format: "yyyy-mm-dd",
    language: "bg",
    autoclose: true
  });
  var firstDayStr = dateToString(getFirstDayOfMonth(new Date()));
  var lastDayStr = dateToString(getLastDayOfMonth(new Date()));
  $('#formSearchWork input[name="startDate"]').val(firstDayStr);
  $('#formSearchWork input[name="endDate"]').val(lastDayStr);

  rest_ajax_get('/lines/find', function(data) {
    var cmbLines = $('#formSearchWork select[name="lineId"]');
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

  rest_ajax_get('/machines/find', function(data) {
    var cmbMachines = $('#formSearchWork select[name="machineId"]');
    cmbMachines.empty();
    cmbMachines.append('<option value="">Избери машина</option>');
    if (data && (data.length > 0))
    {
      for (var i=0; i<data.length; i++)
      {
        var mach = data[i];
        cmbMachines.append('<option value="' + mach.id + '">' + mach.model + '</option>');
      }
    }
  });

  rest_ajax_get('/materials/find', function(data) {
    var cmbMaterials = $('#formSearchWork select[name="materialId"]');
    cmbMaterials.empty();
    cmbMaterials.append('<option value="">Избери материал</option>');
    if (data && (data.length > 0))
    {
      for (var i=0; i<data.length; i++)
      {
        var mat = data[i];
        cmbMaterials.append('<option value="' + mat.id + '">' + mat.materialName + '</option>');
      }
    }
  });

  rest_ajax_get('/groups/find', function(data) {
    var cmbGroups = $('#formSearchWork select[name="groupId"]');
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

  $('#formSearchWork').submit(function() {
    report_work_refreshTbl();
    return false;
  });

  $('#btnExport').click(function() {
      startDate = $('#formSearchWork input[name="startDate"]').val();
      endDate = $('#formSearchWork input[name="endDate"]').val();
      lineId = $('#formSearchWork select[name="lineId"]').val();
      machineId = $('#formSearchWork select[name="machineId"]').val();
      materialId = $('#formSearchWork select[name="materialId"]').val();
      groupId = $('#formSearchWork select[name="groupId"]').val();
      
      var url = '/report-work/export?startDate=' + startDate + '&endDate=' + endDate + 
		'&lineId=' + lineId + '&machineId=' + machineId +
		'&materialId=' + materialId + '&groupId=' + groupId;
      window.location.replace(url);
  });

  var tbl = $('#tblWork').bootgrid({
    ajax: true,
    ajaxSettings: {
      method: 'GET',
      cache: false
    },
    templates: {
      search: ""
    },
    requestHandler: function (request) {
      request.startDate = $('#formSearchWork input[name="startDate"]').val();
      request.endDate = $('#formSearchWork input[name="endDate"]').val();
      request.lineId = $('#formSearchWork select[name="lineId"]').val();
      request.machineId = $('#formSearchWork select[name="machineId"]').val();
      request.materialId = $('#formSearchWork select[name="materialId"]').val();
      request.groupId = $('#formSearchWork select[name="groupId"]').val();
      return request;
    },
    url: "/report-work/find",
    caseSensitive: false,
    rowCount: -1,
    statusMapping: {
      'inf' : 'info'
    }
  });
}

function report_work_refreshTbl()
{
  $('#tblWork').bootgrid('reload');
}

$(document).ready(function() {
  report_work_initTbl();
});
</script>
@endsection

