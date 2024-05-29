<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title_section')</title>
<link rel="stylesheet" type="text/css" href="/js/dist/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/js/dist/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="/js/bootgrid/jquery.bootgrid.min.css" />
<link rel="stylesheet" type="text/css" href="/js/treegrid/jquery.treegrid.css" />
<link rel="stylesheet" type="text/css" href="/js/datepicker/css/bootstrap-datepicker3.min.css" />

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="/js/html5shiv.min.js"></script>
  <script src="/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/js/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/bootgrid/jquery.bootgrid.min.js"></script>
<script type="text/javascript" src="/js/bootbox.min.js"></script>
<script type="text/javascript" src="/js/treegrid/jquery.treegrid.js"></script>
<script type="text/javascript" src="/js/treegrid/jquery.treegrid.bootstrap3.js"></script>
<script type="text/javascript" src="/js/datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/js/datepicker/locales/bootstrap-datepicker.bg.min.js"></script>
<script type="text/javascript" src="/js/validator.min.js"></script>
<script type="text/javascript" src="/js/jQuery.print.js"></script>
<script type="text/javascript" src="/js/utils.js"></script>

<style>
  h1.panel-title.page-header
  {
    font-size: 36px;
  }
</style>

@yield('head_section')
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-3">
        @yield('left_section')
      </div>
      <div class="col-xs-12 col-sm-12 col-md-9 hide" id="mainSection">
        @yield('main_section')
      </div>

      <script type="text/javascript">
        $('#mainSection').removeClass('hide');
      </script>
      <noscript>
      <div class="col-xs-12 col-sm-12 col-md-9">
	<div class="panel panel-primary">
	  <div class="panel-heading">
	    <h1 class="panel-title page-header">Склад 1.0 - Javascript е изключен</h1>
	  </div>
	  <div class="panel-body">
	    <h1 class="page-header">Javascript е изключен !!!</h1>
	    <h2 class="page-header">ВНИМАНИЕ !!! Изглежда, че Вашият браузър не поддържа Javascript или е изключен !!! За съжаление 
		"Склад 1.0" не може да работи без Javascript !!! За да изплозвате продукта, моля активирайте Javascript или инсталирайте
		друг web-браузър, ако Вашият не го поддържа. Google Chrome, Mozilla Firefox и Opera са съвременни, напълно безплатни web-браузъри, които поддържат Javascript.</h2>
	  </div>
	</div>
      </div>
      </noscript>

    </div>
  </div>
</body>
</html>
