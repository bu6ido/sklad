@extends('column2')

@section('title_section') Материали @endsection

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
      <li role="navigation" class="active"><a href="/materials">&nbsp;Материали</a></li>
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
    <h1 class="panel-title page-header">Материали</h1>
  </div>
  <div class="panel-body">

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

<!-- Modal -->
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
          return "<button type='button' class='btn btn-primary btn-edit' data-row-id='" + row.id + "'>Редактирай</button> " +
                 "<button type='button' class='btn btn-primary btn-delete' data-row-id='" + row.id + "'>Изтрий</button>";
        }
    }
  }).on('loaded.rs.jquery.bootgrid', function() {
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

