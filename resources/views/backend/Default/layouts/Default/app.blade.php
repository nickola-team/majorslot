<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title')</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/back/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/back/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/back/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/back/dist/css/AdminLTE.min.css">

    <link rel="stylesheet" href="/back/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="/back/bower_components/morris.js/morris.css">
    <link rel="stylesheet" href="/back/bower_components/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="/back/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/back/bower_components/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/back/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/back/bower_components/croppie/croppie.css">
    <link rel="stylesheet" href="/back/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="/back/bower_components/select2/dist/css/select2.css">
    <link rel="stylesheet" href="/back/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/back/plugins/iCheck/all.css">

    <link rel="stylesheet" href="/back/dist/css/new.css">
    <link rel="stylesheet" href="/back/css/backend.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .toolbar {
            float: left;
            width: 75%;
        }
    </style>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-black sidebar-mini @if(isset($_COOKIE['sidebar-collapse']) && $_COOKIE['sidebar-collapse'] == 'true') sidebar-collapse @endif">
<div class="wrapper">

    @include('backend.Default.partials.Default.navbar')
    @include('backend.Default.partials.Default.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>ver.</b> 7.1
        </div>
        Multi Shop System
    </footer>
    <?php
        $user = auth()->user();
        $user_id = [];
        while ($user)
        {
            if ($user->isInoutPartner())
            {
                $user_id[] = $user->id;
            }
            $user = $user->referral;
        }
        $superadminId = \VanguardLTE\User::where('role_id',8)->first()->id;
        $notices = \VanguardLTE\Notice::where(['active' => 1])->whereIn('type', ['all','partner'])->whereIn('user_id',$user_id)->get(); //for admin's popup    ?>
    @if (count($notices)>0)
    <aside class="control-sidebar control-sidebar-dark control-sidebar-open" style="">
    <!-- Create the tabs -->
        <div class="pop01_popup1 draggable02" style="display: none;" id="notification">
            <div class="pop01_popup_wrap">
                <div class="pop01_popup_btn_wrap">
                    <ul>
                        <li><a href="#" onclick="closeNotification(false);"><span class="pop01_popup_btn">8시간동안 창을 열지 않음</span></a></li>
                        <li><a href="#" onclick="closeNotification(true);"><span class="pop01_popup_btn">닫기 X</span></a></li>
                    </ul>
                </div>
                <div class="pop01_popup_box">
                    <div class="pop01_popup_text" style="padding: 30px;">
                    @foreach ($notices as $notice)
                    <span class="pop01_popup_font1" style="border-bottom: 2px solid rgb(255, 255, 255); margin-bottom: 15px;"></span>
                    <span class="pop01_popup_font2">
                        <div>
                            <?php echo $notice->content  ?>
                        </div>
                    </span>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </aside>
    @endif
</div>
<!-- ./wrapper -->

<script src="/back/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/back/bower_components/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="/back/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/back/bower_components/raphael/raphael.min.js"></script>
<script src="/back/bower_components/morris.js/morris.min.js"></script>
<script src="/back/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script src="/back/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/back/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="/back/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<script src="/back/bower_components/moment/min/moment.min.js"></script>
<script src="/back/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/back/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/back/bower_components/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="/back/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="/back/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/back/bower_components/fastclick/lib/fastclick.js"></script>
<script src="/back/bower_components/croppie/croppie.js"></script>
<script src="/back/bower_components/select2/dist/js/select2.js"></script>
<script src="/back/dist/js/adminlte.js"></script>
<!--<script src="/back/js/sweetalert.min.js"></script>-->
<script src="/back/js/delete.handler.js"></script>
<script src="/back/bower_components/jquery-validation/jquery.validate.min.js"></script>
<script src="/back/bower_components/jquery-validation/additional-methods.min.js"></script>
<script src="/back/plugins/jquery-cookie/jquery.cookie.min.js"></script>

<link href="/back/bower_components/sweetalert2/bootstrap-4.css" rel="stylesheet">
<link rel="stylesheet" href="/back/dist/css/additional.css">
<script src="/back/bower_components/sweetalert2/sweetalert2.js"></script>

<!-- DataTables -->
<script src="/back/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/back/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<!-- iCheck 1.0.1 -->
<script src="/back/plugins/iCheck/icheck.min.js"></script>

<!-- InputMask -->
<script src="/back/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/back/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/back/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<script src="/back/dist/js/demo.js"></script>

@yield('scripts')

<script>
        $( document ).ready(function() {
            var prevTime = localStorage.getItem("hide_notification");
            if (prevTime && Date.now() - prevTime < 8 * 3600 * 1000) {
                $("#notification").hide();
            }
            else{
                $("#notification").show();
            }

            var updateTime = 3000;
            var apiUrl="/api/inoutlist.json";
            var timeout;
            var lastRequest = 0;
            var audio_in = new Audio("{{ url('/frontend/Major/major/audio/door-bell.mp3')}}");
            var audio_out = new Audio("{{ url('/frontend/Major/major/audio/camera-beep.mp3')}}");
            $("#adj_newmark").hide();
            $("#in_newmark").hide();
            $("#out_newmark").hide();
            $("#user_newmark").hide();
            $("#join_newmark").hide();
            var updateInOutRequest = function (callback) {
                if (true) {
                    $.ajax({
                        url: apiUrl,
                        type: "GET",
                        data: {'last':lastRequest, 'id': 
                            @if (Auth::check())
                                {{auth()->user()->id}} },
                            @else
                            0},
                            @endif
                        dataType: 'json',
                        success: function (data) {
                            var inouts=data;
                            lastRequest = inouts['now'];
                            if (inouts['add'] > 0)
                            {
                                if (inouts['rating'] > 0)
                                {
                                    audio_in.play();
                                }
                                $("#adj_newmark").show();
                                $("#in_newmark").show();
                            }
                            if (inouts['out'] > 0)
                            {
                                if (inouts['rating'] > 0)
                                {
                                    audio_out.play();
                                }
                                $("#adj_newmark").show();
                                $("#out_newmark").show();
                            }
                            if (inouts['join'] > 0)
                            {
                                $("#user_newmark").show();
                                $("#join_newmark").show();
                            }
                            else
                            {
                                $("#user_newmark").hide();
                                $("#join_newmark").hide();
                            }


                            if (inouts['add'] == 0 && inouts['out'] == 0)
                            {
                                $("#adj_newmark").hide();
                                $("#in_newmark").hide();
                                $("#out_newmark").hide();
                            }
                            timeout = setTimeout(updateInOutRequest, updateTime);
                            if (callback != null) callback();
                        },
                        error: function () {
                            timeout = setTimeout(updateInOutRequest, updateTime);
                            if (callback != null) callback();
                        }
                    });
                } else {
                    clearTimeout(timeout);
                }
            };

            timeout = setTimeout(updateInOutRequest, updateTime);

            
        });

        function closeNotification(onlyOnce) {
            if (onlyOnce) {
                
            }
            else {
                localStorage.setItem("hide_notification", Date.now());
            }

            $("#notification").hide();
        }

    </script>
</body>
</html>