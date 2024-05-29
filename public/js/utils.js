
// asynchronous ajax
function rest_ajax_get(resturl, callbackSuccess)
{
  $.ajax({
    async: true,
    url: resturl,
    type: 'get',
    dataType: 'json',
    data: {},
    success: function(result)
    {
      if (callbackSuccess !== null)
        callbackSuccess(result);
    },
    error: function (xhr, textStatus, errorThrown) {
      var resp = $($.parseHTML(xhr.responseText));
      alert("Server error: \n" + resp.find('.exception_message').html());
    }
  });
}

function rest_ajax_submit(resturl, method, selForm, callbackSuccess)
{
  var postData = $(selForm).serialize();
  $.ajax({
    async: true,
    url: resturl,
    type: method,
    dataType: 'json',
    data: postData,
    success: function(result)
    {
      if (callbackSuccess !== null)
        callbackSuccess(result);
    },
    error: function (xhr, textStatus, errorThrown) {
      var resp = $($.parseHTML(xhr.responseText));
      alert("Server error: \n" + resp.find('.exception_message').html());
    }
  });
}

function rest_ajax_submit_object(resturl, method, obj, callbackSuccess)
{
  var postData = JSON.stringify(obj);  // $.param(obj);
  $.ajax({
    async: true,
    url: resturl,
    type: method,
    dataType: 'json',
    contentType: 'application/json; charset=utf-8',
    data: postData,
    success: function(result)
    {
      if (callbackSuccess !== null)
        callbackSuccess(result);
    },
    error: function (xhr, textStatus, errorThrown) {
      var resp = $($.parseHTML(xhr.responseText));
      alert("Server error: \n" + resp.find('.exception_message').html());
    }
  });
}


// synchronous ajax
function rest_ajax_get_sync(resturl, callbackSuccess)
{
  $.ajax({
    async: false,
    url: resturl,
    type: 'get',
    dataType: 'json',
    data: {},
    success: function(result)
    {
      if (callbackSuccess !== null)
        callbackSuccess(result);
    },
    error: function (xhr, textStatus, errorThrown) {
      var resp = $($.parseHTML(xhr.responseText));
      alert("Server error: \n" + resp.find('.exception_message').html());
    }
  });
}

function rest_ajax_submit_sync(resturl, method, selForm, callbackSuccess)
{
  var postData = $(selForm).serialize();
  $.ajax({
    async: false,
    url: resturl,
    type: method,
    dataType: 'json',
    data: postData,
    success: function(result)
    {
      if (callbackSuccess !== null)
        callbackSuccess(result);
    },
    error: function (xhr, textStatus, errorThrown) {
      var resp = $($.parseHTML(xhr.responseText));
      alert("Server error: \n" + resp.find('.exception_message').html());
    }
  });
}

function rest_ajax_submit_object_sync(resturl, method, obj, callbackSuccess)
{
  var postData = JSON.stringify(obj); // $.param(obj);
  $.ajax({
    async: false,
    url: resturl,
    type: method,
    dataType: 'json',
    contentType: 'application/json; charset=utf-8',
    data: postData,
    success: function(result)
    {
      if (callbackSuccess !== null)
        callbackSuccess(result);
    },
    error: function (xhr, textStatus, errorThrown) {
      var resp = $($.parseHTML(xhr.responseText));
      alert("Server error: \n" + resp.find('.exception_message').html());
    }
  });
}

// bootstrap fix Bootstrap modal scrollbars
function fixModalScrollBars()
{
  $('.modal').on('hidden.bs.modal', function() {
    if ($('.modal:visible').length > 0)
    {
      $('body').addClass('modal-open');
    }
  });
}

// bootstrap init tooltips
function initToolTips()
{
  $('[data-toggle="tooltip"]').tooltip({ container: 'body' });
}

// converts DateTime object to string
function dateToString(value)
{
  if (!value)
  {
    value = new Date();
  }
  var d = new Date(value);
  var year = d.getFullYear().toString();
  var month = (d.getMonth() + 1).toString();
  if (d.getMonth() < 9) month = '0' + month;
  var day = d.getDate().toString();
  if (d.getDate() < 10) day = '0' + day;
  var result = year + '-' + month + '-' + day;
  return result;
}

// gets the first day of the month in date
function getFirstDayOfMonth(date)
{
  if (!date)
  {
    return new Date();
  }
  var d = new Date(date);
  var res = new Date(d.getFullYear(), date.getMonth(), 1);
  return res;
}

// gets the last day of the month in date
function getLastDayOfMonth(date)
{
  if (!date)
  {
    return new Date();
  }
  var d = new Date(date);
  var res = new Date(d.getFullYear(), date.getMonth() + 1, 0);
  return res;
}

