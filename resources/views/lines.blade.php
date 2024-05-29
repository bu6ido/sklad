@extends('column2')

@section('title_section') Поточни линии @endsection

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
      <li role="navigation" class="active"><a href="/lines">&nbsp;Линии(бригади)</a></li>
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
    <h1 class="panel-title page-header">Поточни линии</h1>
  </div>
  <div class="panel-body">

<form id="formSearchLines" method="get" class="form-inline">
  <div class="form-group">
    <label class="control-label">Име на линия:</label>
    <input type="text" class="form-control" name="lineName" />
    <button type="submit" class="btn btn-primary">Търси</button>      
  </div>
</form>

<div class="table-responsive">
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

<!-- Modal -->
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
          <input type="text" class="form-control" name="lineName" required data-error="Името на линия е задължително !!!" />
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

<script type="text/javascript">
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
          return "<button type='button' class='btn btn-primary btn-edit' data-row-id='" + row.id + "'>Редактирай</button> " +
                 "<button type='button' class='btn btn-primary btn-delete' data-row-id='" + row.id + "'>Изтрий</button>";
        }
    }
  }).on('loaded.rs.jquery.bootgrid', function() {
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

$(document).ready(function() {
  lines_initTbl();
  lines_initBtnCreate();
  lines_initDlg();

  fixModalScrollBars();
});
</script>
@endsection

