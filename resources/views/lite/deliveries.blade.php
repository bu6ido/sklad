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

@if (!$showForm && !$showDelForm && !$showItemForm && !$showDelItemForm)

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
    <th>Описание</th>
    <th>Дата на доставка</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  @foreach($deliveries as $deliv)
  <tr>
    <td>{!! $deliv->getId() !!}</td>
    <td>{!! $deliv->getDescription() !!}</td>
    <td>{!! $deliv->deliveryDateStr !!}</td>
    <td>
    @if ($delivChooseUrl)
      {!! Form::open(array('url' => URL::action('lite\\DeliveryController@choose', array('id' => $deliv->getId() )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Избери', array('class' => 'btn btn-primary btn-choose')) !!}
      </div>
      {!! Form::close() !!}
    @endif

      {!! Form::open(array('url' => URL::action('lite\\DeliveryController@edit', array('id' => $deliv->getId() )), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
        {!! Form::submit('Редактирай', array('class' => 'btn btn-primary btn-edit')) !!}
      </div>
      {!! Form::close() !!}

      {!! Form::open(array('url' => URL::action('lite\\DeliveryController@predelete', array('id' => $deliv->getId() )), 
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
{!! $deliveries->appends(Input::except('current'))->render() !!}
</div>

{!! Form::open(array('url' => URL::action('lite\\DeliveryController@add'), 
                    'method' => 'post')) !!}
<div class="form-group">
  {!! Form::submit('Добави нова доставка', array('class' => 'btn btn-primary')) !!}
</div>
{!! Form::close() !!}
@endif


@if ($showForm)
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

{!! Form::open(array('url' => URL::action('lite\\DeliveryController@save'), 
                     'method' => 'post', 'class' => 'floatLeft')) !!}
<div class="form-group">
  {!! Form::hidden('id', $selDelivery->getId()) !!}
  <label class="control-label">Описание:</label>
  {!! Form::text('description', Input::old('description', $selDelivery->getDescription()), array('class' => 'form-control') ) !!}
</div>
<div class="form-group">
  <label class="control-label">Дата на доставка:</label>
  {!! Form::text('deliveryDate', Input::old('deliveryDate', $selDelivery->deliveryDateStr), array('class' => 'form-control') ) !!}
</div>
<div class="form-group">
  {!! Form::submit('Запиши', array('name' => 'save', 'class' => 'btn btn-primary btn-default')) !!}
  {!! Form::submit('Откажи', array('name' => 'cancel', 'class' => 'btn btn-primary')) !!}
</div>
{!! Form::close() !!}

@if (!$showItemForm && !$showDelItemForm)
<div class="table-responsive">
<table id="tblDelivItems" class="table table-condensed table-hover table-striped">
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
  @foreach($delivItems as $di)
  <tr>
    <td>{!! $index !!}</td>
    <td>{!! $di->getDeliveryId() !!}</td>
    <td>{!! $di->getMaterialId() !!}</td>
    <td>{!! $di->getQuantity() !!}</td>
    <td>
      {!! Form::open(array('url' => URL::action('lite\\DeliveryController@di_edit', array('index' => $index)), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
  	{!! Form::hidden('deliveryId', $selDelivery->getId()) !!}

        {!! Form::submit('Редактирай', array('class' => 'btn btn-primary btn-edit')) !!}
      </div>
      {!! Form::close() !!}

      {!! Form::open(array('url' => URL::action('lite\\DeliveryController@di_predelete', array('id' => $index)), 
                          'method' => 'post', 'style' => 'display: inline-block')) !!}
      <div class="form-group">
  	{!! Form::hidden('deliveryId', $selDelivery->getId()) !!}
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

{!! Form::open(array('url' => URL::action('lite\\DeliveryController@di_add'), 
                    'method' => 'post')) !!}
<div class="form-group">
  {!! Form::hidden('deliveryId', $selDelivery->getId()) !!}
  {!! Form::submit('Добави нов материал', array('class' => 'btn btn-primary')) !!}
</div>
{!! Form::close() !!}
@endif

  </div>
</div>
@endif

@if ($showDelForm)
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Изтриване на доставка</h3>
  </div>
  <div class="panel-body">

{!! Form::open(array('url' => URL::action('lite\\DeliveryController@delete'), 
                    'method' => 'post', 'class' => 'floatLeft')) !!}
  <div class="form-group">
    {!! Form::hidden('id', $delDelivery->getId()) !!}
    Сигурни ли сте, че искате да изтриете доставка: <b>{!! Form::label($delDelivery->getDescription()) !!}</b> ?
  </div>
  <div class="form-group">
    {!! Form::submit('Да', array('name' => 'yes', 'class' => 'btn btn-primary btn-default')) !!}
    {!! Form::submit('Не', array('name' => 'no', 'class' => 'btn btn-primary')) !!}
  </div>
{!! Form::close() !!}
  </div>
</div>
@endif

@if ($showItemForm)
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

{!! Form::open(array('url' => URL::action('lite\\DeliveryController@di_save'), 
                     'method' => 'post', 'class' => 'floatLeft')) !!}
  {!! Form::hidden('deliveryId', $selDelivery->getId()) !!}

  {!! Form::hidden('index', $delivItemIndex) !!}
  {!! Form::hidden('id', $selDelivItem->getId()) !!}
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
    <h3 class="panel-title">Изтриване на материал</h3>
  </div>
  <div class="panel-body">

{!! Form::open(array('url' => URL::action('lite\\DeliveryController@di_delete'), 
                    'method' => 'post', 'class' => 'floatLeft')) !!}
  <div class="form-group">
    {!! Form::hidden('deliveryId', $selDelivery->getId()) !!}

    {!! Form::hidden('index', $delivItemIndex) !!}
    {!! Form::hidden('id', $delDelivItem->getId()) !!}
    Сигурни ли сте, че искате да изтриете материал: <b>#{!! $delivItemIndex !!}</b> ?
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

