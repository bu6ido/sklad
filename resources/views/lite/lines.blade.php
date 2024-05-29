@extends('column2')

@section('title_section') Линии @endsection

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
      <li role="navigation" class="active"><a href="#">&nbsp;Линии</a></li>
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
    <h1 class="panel-title page-header">Поточни линии</h1>
  </div>
  <div class="panel-body">

@if (!$showForm && !$showDelForm)

{!! Form::open(array('url' => Request::path(), 'method' => 'get', 'class' => 'form-inline')) !!}
<div class="form-group">
  <label class="control-label">Име на линия</label>
  {!! Form::text('lineName', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group">
  {!! Form::submit('Търси', array('class' => 'btn btn-primary btn-default')) !!}
</div>
{!! Form::close() !!}

<div class="table-responsive">
<table id="tblLines" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th>№</th>
    <th>Име на линия</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  @foreach($lines as $li)
  <tr>
    <td>{!! $li->getId() !!}</td>
    <td>{!! $li->getLineName() !!}</td>
    <td>
    @if ($linChooseUrl)
      {!! Form::open(array('url' => URL::action('lite\\LineController@choose', array('id' => $li->getId() )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Избери', array('class' => 'btn btn-primary btn-choose')) !!}
      </div>
      {!! Form::close() !!}
    @endif

      {!! Form::open(array('url' => URL::action('lite\\LineController@edit', array('id' => $li->getId() )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Редактирай', array('class' => 'btn btn-primary btn-edit')) !!}
      </div>
      {!! Form::close() !!}

      {!! Form::open(array('url' => URL::action('lite\\LineController@predelete', array('id' => $li->getId() )), 
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
{!! $lines->appends(Input::except('current'))->render() !!}
</div>

{!! Form::open(array('url' => URL::action('lite\\LineController@add'), 
                    'method' => 'post')) !!}
<div class="form-group">
  {!! Form::submit('Добави нова линия', array('class' => 'btn btn-primary')) !!}
</div>
{!! Form::close() !!}
@endif


@if ($showForm)
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Добави/редактирай линия</h3>
  </div>
  <div class="panel-body">

@if (!empty($errors->all()))
  <div class="alert alert-danger" role="alert">
    {!! HTML::ul($errors->all()) !!}
  </div>
@endif

{!! Form::open(array('url' => URL::action('lite\\LineController@save'), 
                     'method' => 'post', 'class' => 'floatLeft')) !!}
<div class="form-group">
  {!! Form::hidden('id', $selLine->getId()) !!}
  <label class="control-label">Име на линия:</label>
  {!! Form::text('lineName', Input::old('lineName', $selLine->getLineName()), array('class' => 'form-control') ) !!}
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
    <h3 class="panel-title">Изтриване на линия</h3>
  </div>
  <div class="panel-body">

{!! Form::open(array('url' => URL::action('lite\\LineController@delete'), 
                    'method' => 'post', 'class' => 'floatLeft')) !!}
  <div class="form-group">
    {!! Form::hidden('id', $delLine->getId()) !!}
    Сигурни ли сте, че искате да изтриете линия: <b>{!! Form::label($delLine->getLineName()) !!}</b> ?
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

