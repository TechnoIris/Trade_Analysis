$(document).ready(function(e){
  // alert(8);
  let arr = [];
  $('.lists').on('mouseenter', function() {
    if (arr.length==0) {
      var select = $('.lists');
      arr = JSON.parse(localStorage.getItem("dropLists"));
      for (i=1; i<arr.length;i++) {
        var opt = arr[i];
        select.append(new Option(opt, opt));
      }
      // select.append(new Option('all', 'all'))
    }
  });
  $("#form").on('submit', (function(e){
    e.preventDefault();
    var message = '';
    // alert(1);
    $.ajax({
      url: 'upload.php',
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      datatype: "json",
      beforeSend: function()
      {
        $('#message').fadeOut();
      },
      success: function(response){
        var result = $.parseJSON(response);
        $('#message').html(result[0]).fadeIn();
        setTimeout(function(){
          $('#message').html(result[0]).fadeOut();
        }, 1300);
        $('#form')[0].reset();
        if (result[1]==1) {
          arr = result[2];
          localStorage.setItem("dropLists", JSON.stringify(arr));
          var select = $('.lists');
          for (i=1; i<arr.length;i++) {
            var opt = arr[i];
            select.append(new Option(opt, opt));
          }
            select.append(new Option('all', 'all'))
        }
      },
      error: function(e)
      {
        $("#message").html(e).fadeIn()
      }
    });
  }));

  var from = new dtsel.DTS('input[name="dateTimePickerFrom"]',  {
  paddingX: 5,
  paddingY: 5,
  dateFormat: "dd-mm-yyyy",
  direction: 'BOTTOM'
  });
  var to = new dtsel.DTS('input[name="dateTimePickerTo"]',  {
    paddingX: 5,
    paddingY: 5,
    dateFormat: "dd-mm-yyyy",
    direction: 'BOTTOM'
  });

  $(".chartContainer").CanvasJSChart({
    zoomEnabled: true,
    title: {
      text: "Stock Chart"
    },
    axisY: {
      title: "Price",
      includeZero: false
    },
    axisX: {
      title: "At Time",
      interval: 1
    },
    data: [
    {
      type: "line", //try changing to column, area
      toolTipContent: "{label}: {y}",
      dataPoints: [
      ]
    }
    ]
  });

  var chart = $(".chartContainer").CanvasJSChart();
  // chart.options.title.text += ": Updated";
  chart.render();

  $("#stockval").on('submit', (function(e){
    // chart.render();
    // chart.data[0].remove();
    var stckname = $('#stocks').find(":selected").text();
    var to = $('to').val();
    var from = $('from').val();
    e.preventDefault();
    // alert("sending");
    $.ajax({
      url: 'findStock.php',
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      datatype: "json",
      beforeSend: function(){
        $('#output').fadeOut();
      },
      success: function(response){
        var result1 = $.parseJSON(response);

        alert(result1[3]);
        var tradearr = result1[3];
        var profit = result1[4];
        var mc = result1[5];
        var sd = result1[6];
        $('#profit').html(profit);
        $('#mean').html(mc);
        $('#sd').html(sd);

        for (var i = 0; i < tradearr.length; i++) {
          var price = tradearr[i][1];
          var action = tradearr[i][2];
          var shares = tradearr[i][3];
          $('#tradeview')
            .append(
              ($('<div/>')
                .attr("id", "kanban")
            ).append(
              ($('<span/>'))
                .attr("id", "price")
                .text(price)
            ).append(
              ($('<hr/>'))
            ).append(
              ($('<span/>'))
                .attr("id", "action")
                .text(action)
            ).append(
              ($('<hr/>'))
            ).append(
              ($('<span/>'))
                .attr("id", "sharecount")
                .text(shares)
            )
          );
        }

        $('#output').html(result1[0]).fadeIn();
        setTimeout(function(){
          $('#output').html('').fadeOut();
        }, 1300);
        // $('#form')[0].reset();
        for (var i = 0; i < result1[1].length; i++) {
          chart.options.data[0].dataPoints.push({ label: result1[1][i], y: result1[2][i]});
        }
        chart.options.title.text += '';
        chart.render();
      },
      error: function(e){
        $("#output").html(e).fadeIn()
      }
    });
  }));


});
