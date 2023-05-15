<!DOCTYPE html>
<html lang="ko">
<head>
    <title>안내</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta name="keywords" content="">
    <meta name="description" content="">
</head>
<style>
    body{
        font-family:'SpoqaHanSansNeo','sans-serif';
        min-width: 320px;
        margin: 0;
        padding: 0;
    }
    #wrap {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        flex-grow: 1;
        min-height: 100%;
    }
    #container {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        flex-grow: 1;
        width: 100%;
        overflow: hidden;
        position: relative;
        padding-top: 130px;
        background: #fff;
        -webkit-transform-origin: top;
        transform-origin: top;
        -webkit-transition: transform 0.3s 0.3s;
        transition: transform 0.3s 0.3s;
        overflow: hidden;
    }
    .contents {
        flex-grow: 1;
        display: block;
        position: relative;
        width: 100%;
        padding: 0 0 100px;
    }
    .ly_inner {
        display: block;
        position: relative;
        width: 980px;
        margin: 0 auto;
        padding: 0;
    }
    #container .complete_body.img05::before {
        content:'';
        overflow:hidden;
        display:block;
        position:absolute;
        top:80px !important;
        left:50%;
        width:66px !important;
        height:66px !important;
        margin:0 0 0 -33px !important;
        background:url('/frontend/Default/img/dxicon03.png') no-repeat center top / 66px !important;
        animation:none !important;
    }
    .complete_body
        {position:relative;background:none !important;padding-top:190px !important;}
    .complete_body .title {
        font-size: 28px;
        font-weight: 700;
        text-align: center;
        line-height: 1.3;
        color: #111;
    }

    .complete_body .sub_title {
        padding-top: 18px;
        font-size: 16px;
        text-align: center;
        color: #111;
    }

    .complete_body .sub_title strong {
        font-weight: 700;
        color: #333;
    }
    .gap50 {
        margin-bottom: 50px !important;
    }
    [class*=btn-cover]{margin-top:60px;text-align:center;display:block;white-space:normal;}
    [class*=c-btn]{cursor:pointer;vertical-align:middle;text-align:center;display:inline-block;overflow:hidden;white-space:nowrap;position:relative;transition:all .4s;}
    [class*=c-btn1]{display: inline-block;position: relative;height: 56px;padding: 0 48px;border: 1px solid transparent;border-radius: 56px;text-align: center;vertical-align: middle;cursor: pointer;line-height: 54px;font-weight: 700;font-size: 18px;white-space: nowrap;min-width: 200px;}
    [class*=c-btn1-a]{background:#476eff;border-color:#476eff;border-radius:4px;border: 1px solid #4868e1;background:#4868e1;color: #fff;}
    @media only screen and (max-width: 1100px) {
        #container{padding-top:56px;}
        .ly_inner {
            width: 100%;
            padding: 0 20px
        }
        .complete_body{
            padding-top:182px !important;margin-bottom: 30px;
        }
        .complete_body .title {
            font-size: 20px !important;
        }

        .complete_body .sub_title {
            font-size: 16px !important;
        }
    }
</style>
<body>
    <div id="wrap">
        <div id="container">
            <section class="contents complete_page type_error">
            <div class="ly_inner">
                <div class="complete_body ani_ico img05">
                    <p class="title">이용에 불편을 드려 죄송합니다.</p>
                    <p class="sub_title gap50">
                                해당 게임사는 현재 점검중입니다.
                    </p>
                    <div class="btn-cover1 col">
                            <a href="#"  class="c-btn1-a" onclick="closeMe();"><span>닫  기</span> </a>
                    </div>
                </div>

            </div>
            </section>
        </div>
    </div>
</body>
</html>

<script>
    function closeMe()
    {
        window.opener = self;
        window.close();
    }
    </script>