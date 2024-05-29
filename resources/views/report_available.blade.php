@extends('column2')

@section('title_section') Налични материали @endsection

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
      <li role="navigation"><a href="/report-work">&nbsp;Изразходвани материали и труд</a></li>
      <li role="navigation" class="active"><a href="/report-available">&nbsp;Налични материали</a></li>
    </ul>
  </li>
</ul>

  </div>
</div>
@endsection

@section('main_section')
<div class="panel panel-primary">
  <div class="panel-heading">
    <h1 class="panel-title page-header">Справка - Налични материали</h1>
  </div>
  <div class="panel-body">

<form id="formSearchAvailable" method="get" class="form-horizontal">
  <div class="form-group">
    <label class="control-label col-sm-4 col-md-3">Към дата:</label>
    <div class="col-sm-8 col-md-3">
      <input type="text" class="form-control" name="toDate" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-4 col-md-3">Материал:</label>
    <div class="col-sm-8 col-md-3">
      <select class="form-control" name="materialId"></select>
    </div>
    <label class="control-label col-sm-4 col-md-3">Група:</label>
    <div class="col-sm-12 col-md-3">
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
<table id="tblAvailable" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th data-column-id="material_name">Материал</th>
    <th data-column-id="group_name">Група</th>
    <th data-column-id="price">Цена(лв.)</th>
    <th data-column-id="mquantity">Налично к-во</th>
    <th data-column-id="sum">Стойност(лв.)</th>
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
function report_available_initTbl()
{
  $('#formSearchAvailable input[name="toDate"]').datepicker({
    format: "yyyy-mm-dd",
    language: "bg",
    autoclose: true
  });
  var toDateStr = dateToString(new Date());
  $('#formSearchAvailable input[name="toDate"]').val(toDateStr);

  rest_ajax_get('/materials/find', function(data) {
    var cmbMaterials = $('#formSearchAvailable select[name="materialId"]');
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
    var cmbGroups = $('#formSearchAvailable select[name="groupId"]');
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

  $('#formSearchAvailable').submit(function() {
    report_available_refreshTbl();
    return false;
  });

  $('#btnExport').click(function() {
      toDate = $('#formSearchAvailable input[name="toDate"]').val();
      materialId = $('#formSearchAvailable select[name="materialId"]').val();
      groupId = $('#formSearchAvailable select[name="groupId"]').val();
      
      var url = '/report-available/export?toDate=' + '&materialId=' + materialId + '&groupId=' + groupId;
      window.location.replace(url);
  });

  var tbl = $('#tblAvailable').bootgrid({
    ajax: true,
    ajaxSettings: {
      method: 'GET',
      cache: false
    },
    templates: {
      search: ""
    },
    requestHandler: function (request) {
      request.toDate = $('#formSearchAvailable input[name="toDate"]').val();
      request.materialId = $('#formSearchAvailable select[name="materialId"]').val();
      request.groupId = $('#formSearchAvailable select[name="groupId"]').val();
      return request;
    },
    url: "/report-available/find",
    caseSensitive: false,
    rowCount: -1,
    statusMapping: {
      'inf' : 'info'
    }
  });
}

function report_available_refreshTbl()
{
  $('#tblAvailable').bootgrid('reload');
}

$(document).ready(function() {
  report_available_initTbl();
});
</script>
@endsection
