@extends('column2')

@section('title_section') Настройки @endsection

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
      <li role="navigation" class="active"><a href="/settings">&nbsp;Общи настройки</a></li>
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
    <h1 class="panel-title page-header">Настройки</h1>
  </div>
  <div class="panel-body">

<div class="panel-group" id="accSettings" role="tablist" aria-multiselectable="true">
  <div class="panel panel-info">
    <div class="panel-heading" role="tab" id="headingLabourPrice">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapseLabourPrice" aria-expanded="true" aria-controls="collapseLabourPrice">
          Цена на труд(лв.)
        </a>
      </h4>
    </div>
    <div id="collapseLabourPrice" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLabourPrice">
      <div class="panel-body">
      </div>
    </div>
  </div>
</div>

<button id="btnCreateSettings" type="button" class="btn btn-primary">
  Създай нови настройки
</button>

<button id="btnEditSettings" type="button" class="btn btn-primary">
   Редактирай настройки
</button>

<button id="btnDeleteSettings" type="button" class="btn btn-primary">
   Изтрий настройки
</button>

  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="dlgSettings" tabindex="-1" role="dialog" aria-labelledby="lblTitleSettings" aria-hidden="true"
	data-backdrop="static" data-keyboard="false">
<form id="formDlgSettings" method="post">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnXSettings" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="lblTitleSettings">Добави/Редактирай настройки:</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" class="form-control" name="settingsId" />
	<div class="form-group">
          <label class="control-label">Цена на труд(лв.):</label>
          <input type="number" class="form-control" name="labourPrice" step="any"
		 required data-error="Цената на труда е задължителна и трябва да бъде число !!!" />
	  <div class="help-block with-errors"></div>
	</div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveSettings" type="submit" data-toggle="modal" class="btn btn-primary btn-default">Запиши</button>
        <button id="btnCancelSettings" type="button" class="btn">Откажи</button>
      </div>
    </div>
  </div>
</form>
</div>

<script type="text/javascript">

var settingsId = '';

function settings_refreshAcc()
{
  rest_ajax_get('/settings/find', function (data) { 
    if (data && (data.length > 0))
    {
      var settings = data[0];
      settingsId = settings.id;

      $('#accSettings #collapseLabourPrice .panel-body').html(settings.labourPrice);

      $('#btnCreateSettings').hide();
      $('#btnEditSettings').show();
      $('#btnDeleteSettings').show();
    }
    else
    {
      settingsId = '';
      $('#accSettings #collapseLabourPrice .panel-body').html('');

      $('#btnCreateSettings').show();
      $('#btnEditSettings').hide();
      $('#btnDeleteSettings').hide();
    }
  });
}

function settings_findById(id, callback)
{
  rest_ajax_get('/settings/' + id, callback);
}

function settings_initBtns()
{
  $('#btnCreateSettings').click(function (e) { 
    var settings = { };
    settings_setDlg(settings);
    $('#dlgSettings').modal('show'); 
  });
  
  $('#btnEditSettings').click(function(e) {
    settings_findById(settingsId, function(settings) {
      settings_setDlg(settings);
      $('#dlgSettings').modal('show');
    });
  });

  $('#btnDeleteSettings').click(function(e) {
    settings_findById(settingsId, function(settings) {
      if (settings)
      {
        bootbox.confirm('Сигурни ли сте, че искате да изтриете настройките ?', 
          function(ok) { 
            if (ok) {
              settings_delete(settings, function(data) { settings_refreshAcc(); });
            }
        });
      }
    });
  });
}

function settings_initDlg()
{
  var funcHide = function (e) { $('#dlgSettings').modal('hide'); };
  $('#btnXSettings').on('click', funcHide);
  $('#btnCancelSettings').on('click', funcHide);
  $('#formDlgSettings').validator({ disable: false }).submit(function(e) {
    if (!e.isDefaultPrevented())
    {
      settings_save();
    }
    return false;
  });
  $('#dlgSettings').on('shown.bs.modal', function() {
    $('#dlgSettings input[name="labourPrice"]').focus();
  });
}

function settings_setDlg(settings)
{
  if (settings)
  {
    $('#dlgSettings input[name="settingsId"]').val(settings.id);
    $('#dlgSettings input[name="labourPrice"]').val(settings.labourPrice);
  }
}

function settings_getDlg()
{
  var obj = { };
  obj.id = $('#dlgSettings input[name="settingsId"]').val();
  obj.labourPrice = $('#dlgSettings input[name="labourPrice"]').val();
  return obj;
}

function settings_save()
{
  var settings = settings_getDlg();
  $('#dlgSettings').modal('hide');
  if (!settings.id)
  {
    settings_insert(settings, function(data) { settings_refreshAcc(); });
  }
  else
  {
    settings_update(settings, function(data) { settings_refreshAcc(); });
  }
}

function settings_insert(settings, callback)
{
  if (!settings)
    return;
  settings._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/settings', 'post', settings, callback);
}

function settings_update(settings, callback)
{
  if (!settings)
    return;
  if (!settings.id)
    return;
  settings._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/settings/' + settings.id, 'put', settings, callback);
}

function settings_delete(settings, callback)
{
  if (!settings)
    return;
  if (!settings.id)
    return;
  settings._token = '{{ csrf_token() }}';
  rest_ajax_submit_object('/settings/' + settings.id, 'delete', settings, callback);
}

$(document).ready(function() {
  settings_refreshAcc();
  settings_initBtns();
  settings_initDlg();

  fixModalScrollBars();
});
</script>
@endsection

