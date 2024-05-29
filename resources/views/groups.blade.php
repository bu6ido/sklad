@extends('column2')

@section('title_section') Групи @endsection

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
      <li role="navigation" class="active"><a href="/groups">&nbsp;Групи</a></li>
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
    <h1 class="panel-title page-header">Групи</h1>
  </div>
  <div class="panel-body">

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

<!-- Modal -->
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
          <input type="text" class="form-control" name="groupName" required data-error="Името на групата е задължително !!!"/>
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
        var buttons = "<button type='button' class='btn btn-primary btn-group-add' data-row-id='" + group.id + "'>Създай подгрупа</button> " + 
		      "<button type='button' class='btn btn-primary btn-group-edit' data-row-id='" + group.id + "'>Редактирай</button> " +
		      "<button type='button' class='btn btn-primary btn-group-delete' data-row-id='" + group.id + "'>Изтрий</button>";
        tblBody.append('<tr class="treegrid-' + group.id + ' treegrid-parent-' + pid + '"><td>' + group.id + '</td>' +
			'<td>' + group.groupName + '</td><td>' + buttons + '</td>');
      }
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

$(document).ready(function() {
  groups_refreshTree();
  groups_initDlg();

  fixModalScrollBars();
});
</script>
@endsection

