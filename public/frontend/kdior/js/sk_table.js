var totalTicker = 0,
  tickerTimer = {};
(function ($) {
  $.fn.tableTicker = function (options) {

    var settings = $.extend({
      row: 1,
      speed: 1000,
      interval: 3000,
      buffer: 0,
      complete: null
    }, options);

    function rollTicker(element) {
      if (!$(element).is(':animated')) {
        var table = element;
        var rows = table.rows;
        var w = $(element).outerWidth();
        var h = $(element).outerHeight();
        var mTop = (h / rows.length * settings.row) + settings.buffer;

        $(element).animate({
            'marginTop': '-' + mTop + 'px'
          },
          settings.speed, "linear",
          function () {
            $(rows).slice(0, settings.row).each(function (index, value) {
              table.tBodies[0].appendChild(value);
            });
            $(element).css("marginTop", "-" + settings.buffer + "px");
            if ($.isFunction(settings.complete)) {
              settings.complete.call(element);
            }
            this.rolled += 1;
            if (!this.paused) {
              tickerTimer[($(element).parent().prop('id'))] = setTimeout(function () {
                rollTicker(element);
              }, settings.interval);
            }
          });
      }
    }

    function startTicker(element) {
      if (element.paused) {
        element.paused = false;
        if (!$(element).is(':animated')) {
          tickerTimer[($(element).parent().prop('id'))] = setTimeout(function () {
            rollTicker(element);
          }, settings.interval);
        }
      }
    }

    function stopTicker(element) {
      clearTimeout(tickerTimer[($(element).parent().prop('id'))]);
      tickerTimer[($(element).parent().prop('id'))] = null;
      element.paused = true;
    }

    return this.each(function (index, value) {
      if (value && value.nodeName.toLowerCase() == "table") {
        if (value.parentNode && value.parentNode.id.indexOf('tableTicker_wrap') < 0) {
          totalTicker++;
          var table = value;
          var rows = table.rows;
          var w = $(table).outerWidth();
          var h = $(table).outerHeight();

          var wrap = $("<div />");
          wrap.prop('id', "tableTicker_wrap" + totalTicker);
          wrap.css({
            "width": '100%',
            "height": "150px",
            "position": "relative",
            "overflow": "hidden" ,
            "padding": "0 0 0 5px"						
            // ysk 紐⑤컮�쇰쾭�� %濡� 援ы쁽�좊븣 "padding": "0 10px 0 10px"			
          });
          $(table).hover(
            function () {
              stopTicker(table);
            },
            function () {
              startTicker(table);
            }
          );
          $(table).css({
            "position": "absolute",
            "top": 0
          })
          $(table).wrap(wrap);
          table.paused = false;
          table.rolled = 0;
          tickerTimer[(wrap.prop('id'))] = setTimeout(function () {
            rollTicker(table);
          }, settings.interval);
        }
      }
    });
  };
}(jQuery));


// �� 源뚯�媛� �뚮윭洹몄씤 �뚯뒪
// �꾨옒遺��� �댁슜踰�
$(function () {

  $(".myTable").tableTicker({
    row: 1, //紐뉗쨪 濡ㅻ쭅?
    speed: 1000, //濡ㅻ쭅 �띾룄 1000 = 1珥덉뿉 �좊땲硫붿씠�� 醫낅즺
    interval: 4000, //�뺤� 湲곌컙 (1000 = 1珥�, 0 = �쇱뒪��)
    buffer: 0, // �대룞�� 諛� �믪씠 蹂댁젙�� 異붽� �쎌� (留덉씠�덉뒪 媛믪� �낅젰 遺덇�)
    complete: null // 留� 濡ㅻ쭅 �� �몄텧�섎뒗 callback function
  });

  $("#myTable3").tableTicker({
    row: 2,
    speed: 2000,
    interval: 2000,
    buffer: 0,
    complete: function () {
      $('#cnt').html(this.rolled);
    }
  });

})