@extends('column2')

@section('title_section') Доставки @endsection

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
      <li role="navigation" class="active"><a href="#">&nbsp;Доставки</a></li>
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
    <h1 class="panel-title page-header">Доставки</h1>
  </div>
  <div class="panel-body">

@if (!$showItemForm && !$showDelItemForm)

{!! Form::open(array('url' => Request::path(), 'method' => 'get', 'class' => 'form-inline')) !!}
<div class="form-group">
  <label class="control-label">Описание на доставка</label>
  {!! Form::text('description', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group">
  {!! Form::submit('Търси', array('class' => 'btn btn-primary btn-default')) !!}
</div>
{!! Form::close() !!}

<div class="table-responsive">
<table id="tblDeliveries" class="table table-condensed table-hover table-striped">
  <thead>
  <tr>
    <th>№</th>
    <th>Delivery Id</th>
    <th>Material Id</th>
    <th>Quantity</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php $index = 0; ?>
  @foreach($delivItems as $li)
  <tr>
    <td>{!! $index !!}</td>
    <td>{!! $li->getDeliveryId() !!}</td>
    <td>{!! $li->getMaterialId() !!}</td>
    <td>{!! $li->getQuantity() !!}</td>
    <td>
      {!! Form::open(array('url' => URL::action('lite\\DeliveryItemController@edit', array('id' => $index )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Редактирай', array('class' => 'btn btn-primary btn-edit')) !!}
      </div>
      {!! Form::close() !!}

      {!! Form::open(array('url' => URL::action('lite\\DeliveryItemController@predelete', array('id' => $index )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Изтрий', array('class' => 'btn btn-primary btn-delete')) !!}
      </div>
      {!! Form::close() !!}
    </td>
  </tr>
  <?php $index++; ?>
  @endforeach
  </tbody>
</table>
</div>

{!! Form::open(array('url' => URL::action('lite\\DeliveryItemController@add'), 
                    'method' => 'post')) !!}
<div class="form-group">
  {!! Form::submit('Добави нова доставка', array('class' => 'btn btn-primary')) !!}
</div>
{!! Form::close() !!}
@endif


@if ($showItemForm)
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Добави/редактирай доставка</h3>
  </div>
  <div class="panel-body">

@if (!empty($errors->all()))
  <div class="alert alert-danger" role="alert">
    {!! HTML::ul($errors->all()) !!}
  </div>
@endif

{!! Form::open(array('url' => URL::action('lite\\DeliveryItemController@save'), 
                     'method' => 'post', 'class' => 'floatLeft')) !!}
<div class="form-group">
  {!! Form::hidden('index', $delivItemIndex) !!}
  {!! Form::hidden('id', $selDelivItem->getId()) !!}
  <label class="control-label">Delivery Id:</label>
  {!! Form::text('deliveryId', Input::old('deliveryId', $selDelivItem->getDeliveryId()), array('class' => 'form-control') ) !!}
</div>
<div class="form-group">
  <label class="control-label">Material Id:</label>
  {!! Form::text('materialId', Input::old('materialId', $selDelivItem->getMaterialId()), array('class' => 'form-control') ) !!}
</div>
<div class="form-group">
  <label class="control-label">Quantity:</label>
  {!! Form::text('quantity', Input::old('quantity', $selDelivItem->getQuantity()), array('class' => 'form-control') ) !!}
</div>
<div class="form-group">
  {!! Form::submit('Запиши', array('name' => 'save', 'class' => 'btn btn-primary btn-default')) !!}
  {!! Form::submit('Откажи', array('name' => 'cancel', 'class' => 'btn btn-primary')) !!}
</div>
{!! Form::close() !!}
  </div>
</div>
@endif

@if ($showDelItemForm)
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Изтриване на доставка</h3>
  </div>
  <div class="panel-body">

{!! Form::open(array('url' => URL::action('lite\\DeliveryItemController@delete'), 
                    'method' => 'post', 'class' => 'floatLeft')) !!}
  <div class="form-group">
    {!! Form::hidden('index', $delivItemIndex) !!}
    {!! Form::hidden('id', $delDelivItem->getId()) !!}
    Сигурни ли сте, че искате да изтриете доставка: <b>#{!! $delivItemIndex !!}</b> ?
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

