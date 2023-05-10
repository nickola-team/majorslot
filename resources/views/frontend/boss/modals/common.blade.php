<div id="onofOpenPopup" class="overlay hidemsgs">
<div class="onofalertin">
    <div class="smgcontent">
    <div>
        <div class="form-ms-group">
        <span class="mtts">게임이용이 중지되었습니다. <br>관리자에게 문의해 주세요. <br>
        </span>
        </div>
        <div class="form-ms-group">
        <a href="javascript:closeMaintancePopup();" class="btnonofcl">확인</a>
        </div>
    </div>
    </div>
</div>
</div>

<style type="text/css">
        .hidemsgs {
          display: none;
        }

        #onofOpenPopup.overlay {
          position: absolute;
          top: 0;
          bottom: 0;
          left: 0;
          right: 0;
          z-index: 99999;
        }

        #onofOpenPopup.overlay.show {
          display: block;
        }

        .onofalertin {
          margin: 20% auto;
          width: 14.5%;
          min-width: 300px;
          position: relative;
          transition: all 5s ease-in-out;
          font-family: Dotum, sans-serif;
          font-size: 12px;
          border-radius: 4px;
          background: #284969;
          color: #FFFFFF;
          padding: 2px 2px 12px 2px;
          margin-left: 38%;
        }

        .onofalertin .mtts {
          text-align: center;
          display: block;
          font-size: 1.17em;
          margin-block-start: 1em;
          margin-block-end: 1em;
          margin-inline-start: 0px;
          margin-inline-end: 0px;
          font-weight: bold;
        }

        .onofalertin .smgcontent {
          max-height: 30%;
          overflow: auto;
          padding: 18px;
          color: #dfeeff;
        }

        @media screen and (max-width: 720px) {
          .onofalertin {
            width: 65%;
            margin: 60% auto;
          }

          .onofalertin .form-ms-group .form-control {
            width: 28%;
          }

          .onofalertin .smgcontent {
            padding-left: 5px;
            padding-right: 5px;
          }
        }

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 1) {
          .onofalertin {
            width: 60%;
          }
        }

        .btnonofcl {
          display: block;
          box-sizing: border-box;
          margin: 0 auto;
          padding: 8px;
          width: 40%;
          max-width: 200px;
          background: #fff;
          background: rgba(255, 255, 255, 0.5);
          border-radius: 8px;
          color: #fff;
          text-align: center;
          text-decoration: none;
          letter-spacing: 1px;
          transition: all 0.3s ease-out;
          font-size: 14px;
          cursor: pointer !important;
          font-weight: bold;
        }
      </style>

<script>
    function closeMaintancePopup() {
        $('#onofOpenPopup').removeClass('show');
        $('#onofOpenPopup').addClass('hidemsgs');
    }

    function underMaintance() {
        $('#onofOpenPopup').addClass('show');
        $('#onofOpenPopup').removeClass('hidemsgs');
    }
</script>
    