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
      <div class="col-xs-12 col-sm-12 col-md-9">
        @yield('main_section')
      </div>
    </div>
  </div>
</body>
</html>
