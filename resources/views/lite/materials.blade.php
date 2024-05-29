@extends('column2')

@section('title_section') Материали @endsection

@section('left_section')
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Left navigation</h3>
  </div>
  <div class="panel-body">

<ul class="nav nav-pills nav-stacked text-left">
  <li role="navigation">
    <ul class="nav nav-pills">
      <li role="navigation"><a href="#admin">Admin menu</a></li>
      <li role="navigation"><a href="#" data-toggle="collapse" data-target="#adminMenu"><span class="caret"></span></a></li>
    </ul>
    <ul id="adminMenu" class="nav nav-pills nav-stacked in">
      <li role="navigation"><a href="#">&nbsp;Линии</a></li>
      <li role="navigation" class="active"><a href="#">&nbsp;Материали</a></li>
      <li role="navigation"><a href="#">&nbsp;Areas</a></li>
      <li role="navigation"><a href="#">&nbsp;Cities</a></li>
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

@if (!$showForm && !$showDelForm)

{!! Form::open(array('url' => Request::path(), 'method' => 'get', 'class' => 'form-inline')) !!}
<div class="form-group">
  <label class="control-label">Име на материал</label>
  {!! Form::text('materialName', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group">
  {!! Form::submit('Търси', array('class' => 'btn btn-primary btn-default')) !!}
</div>
{!! Form::close() !!}

<div class="table-responsive">
<table id="tblMaterials" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th>№</th>
    <th>Име на материал</th>
    <th>Цена</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  @foreach($materials as $mat)
  <tr>
    <td>{!! $mat->getId() !!}</td>
    <td>{!! $mat->getMaterialName() !!}</td>
    <td>{!! $mat->getPrice() !!}</td>
    <td>
    @if ($matChooseUrl)
      {!! Form::open(array('url' => URL::action('lite\\MaterialController@choose', array('id' => $mat->getId() )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Избери', array('class' => 'btn btn-primary btn-choose')) !!}
      </div>
      {!! Form::close() !!}
    @endif

      {!! Form::open(array('url' => URL::action('lite\\MaterialController@edit', array('id' => $mat->getId() )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Редактирай', array('class' => 'btn btn-primary btn-edit')) !!}
      </div>
      {!! Form::close() !!}

      {!! Form::open(array('url' => URL::action('lite\\MaterialController@predelete', array('id' => $mat->getId() )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Изтрий', array('class' => 'btn btn-primary btn-delete')) !!}
      </div>
      {!! Form::close() !!}
    </td>
  </tr>
  @endforeach
  </tbody>
</table>
{!! $materials->appends(Input::except('current'))->render() !!}
</div>

{!! Form::open(array('url' => URL::action('lite\\MaterialController@add'), 
                    'method' => 'post')) !!}
<div class="form-group">
  {!! Form::submit('Добави нов материал', array('class' => 'btn btn-primary')) !!}
</div>
{!! Form::close() !!}
@endif


@if ($showForm)
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Добави/редактирай материал</h3>
  </div>
  <div class="panel-body">

@if (!empty($errors->all()))
  <div class="alert alert-danger" role="alert">
    {!! HTML::ul($errors->all()) !!}
  </div>
@endif

{!! Form::open(array('url' => URL::action('lite\\MaterialController@save'), 
                     'method' => 'post', 'class' => 'floatLeft')) !!}
<div class="form-group">
  {!! Form::hidden('id', $selMaterial->getId()) !!}
  {!! Form::hidden('groupId', null) /* TODO fix groupId */ !!}
  <label class="control-label">Име на материал:</label>
  {!! Form::text('materialName', Input::old('materialName', $selMaterial->getMaterialName()), array('class' => 'form-control') ) !!}
</div>
<div class="form-group">
  <label class="control-label">Себестойност:</label>
  {!! Form::text('price', Input::old('price', $selMaterial->getPrice()), array('class' => 'form-control') ) !!}
</div>
<div class="form-group">
  {!! Form::submit('Запиши', array('name' => 'save', 'class' => 'btn btn-primary btn-default')) !!}
  {!! Form::submit('Откажи', array('name' => 'cancel', 'class' => 'btn btn-primary')) !!}
</div>
{!! Form::close() !!}
  </div>
</div>
@endif

@if ($showDelForm)
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Изтриване на материал</h3>
  </div>
  <div class="panel-body">

{!! Form::open(array('url' => URL::action('lite\\MaterialController@delete'), 
                    'method' => 'post', 'class' => 'floatLeft')) !!}
  <div class="form-group">
    {!! Form::hidden('id', $delMaterial->getId()) !!}
    Сигурни ли сте, че искате да изтриете материал: <b>{!! Form::label($delMaterial->getMaterialName()) !!}</b> ?
  </div>
  <div class="form-group">
    {!! Form::submit('Да', array('name' => 'yes', 'class' => 'btn btn-primary btn-default')) !!}
    {!! Form::submit('Не', array('name' => 'no', 'class' => 'btn btn-primary')) !!}
  </div>
{!! Form::close() !!}
  </div>
</div>
@endif

  </div>
</div>

@endsection

