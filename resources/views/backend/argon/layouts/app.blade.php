<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('page-title')</title>
        <!-- Favicon -->
        <link href="{{ asset('back/argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300&display=swap" rel="stylesheet">

        <!-- Extra details for Live View on GitHub Pages -->

        <!-- Icons -->
        <link href="{{ asset('back/argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
        <link href="{{ asset('back/argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
        <!-- Argon CSS -->
        <link type="text/css" href="{{ asset('back/argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
        @stack('css')
        <link type="text/css" href="{{ asset('back/argon') }}/css/custom.css?v=1.0.0" rel="stylesheet">
        <link type="text/css" href="{{ asset('back/argon') }}/css/{{$layout}}.css?v=1.0.0" rel="stylesheet">

    </head>
    <body class="{{ $class ?? '' }}">
        @auth()
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
            $superadminId = \VanguardLTE\User::where('role_id',9)->first()->id;
            $notices = \VanguardLTE\Notice::where(['active' => 1])->whereIn('type', ['all','partner'])->whereIn('user_id',$user_id)->get(); //for admin's popup
            $msgtype = 0;
            if (auth()->user()->hasRole('admin'))
            {
                $unreadmsgs = [];
            }
            else
            {
                $unreadmsgs = \VanguardLTE\Message::where('user_id', auth()->user()->id)->whereNull('read_at')->get(); //unread message
                if (count($unreadmsgs) > 0)
                {
                    $msgtype = $unreadmsgs->first()->type;
                }
            }
        ?>
        @if (count($notices)>0)
            @foreach ($notices as $notice)
            <div class="noticeBar" style="left:{{$loop->index * 100}}px;top:{{40+$loop->index * 50}}px; display:none;" id="notification{{$notice->id}}">
                <div >
                    <button type="button" class="btn btn-primary" id="noticeDaily" onclick="closeNotification('notification{{$notice->id}}', false);">하루동안 보지 않기</button>
                    <button type="button" class="btn btn-default" id="noticeOnce" onclick="closeNotification('notification{{$notice->id}}', true);">닫기</button>
                </div>
                <hr class="my-1">
                <div class="content">
                    <?php echo $notice->content  ?>
                </div>
            </div>
            @endforeach
        @endif
            <form id="logout-form" action="#" method="POST" style="display: none;">
                @csrf
            </form>
            @include('backend.argon.layouts.navbars.sidebar')
        @endauth
        
        <div class="main-content">
            @include('backend.argon.layouts.navbars.navbar')
            @auth()
                @include('backend.argon.layouts.headers.auth')
            @endauth
            @yield('content')
            @if (Auth::check() )
            <!-- for message dialog -->
            <div class="modal fade" id="openMsgModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">쪽지내용</h4>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table align-items-left table-flush">
                                <tbody>
                                    <tr>
                                        <td>발신자</td>
                                        <td><span id="msgWriter"></span></td>
                                    </tr>
                                    <tr>
                                        <td>상위파트너</td>
                                        <td><span id="msgWriterParent"></span></td>
                                    </tr>
                                    <tr>
                                        <td>제목</td>
                                        <td><span id="msgTitle"></span></td>
                                    </tr>
                                    <tr>
                                        <td>내용</td>
                                        <td><span id="msgContent"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"  data-dismiss="modal">확인</button>
                        </div>
                </div>
            </div>
            </div>
            @endif
        </div>

        @include('backend.argon.layouts.footers.guest')

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="{{ asset('back/argon') }}/vendor/js-cookie/js.cookie.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/lavalamp/js/jquery.lavalamp.min.js"></script>
        <!-- Optional JS -->
        <script src="{{ asset('back/argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
        <script src="{{ asset('back/argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>

        <script src="{{ asset('back/argon') }}/js/delete.handler.js"></script>
        <script src="/back/bower_components/sweetalert2/sweetalert2.js"></script>
        @stack('js')
 
        <!-- Argon JS -->
        
        <script src="{{ asset('back/argon') }}/js/argon.js?v=1.0.0"></script>
        <script>
        @if (Auth::check() )
        $( document ).ready(function() {
            @if (count($notices)>0)
            @foreach ($notices as $notice)
            var prevTime = localStorage.getItem("hidenotification" + {{$notice->id}});
            if (prevTime && Date.now() - prevTime < 24 * 3600 * 1000) {
                $("#notification{{$notice->id}}").hide();
            }
            else{
                $("#notification{{$notice->id}}").show();
            }
            @endforeach
            @endif
            @if (count($unreadmsgs) > 0)
                $("#msgbutton").click();
            @endif
            @if (!auth()->user()->hasRole('admin') && auth()->user()->isInoutPartner())
            var updateTime = 3000;
            var apiUrl="/api/inoutlist.json";
            var timeout;
            var lastRequest = 0;
            var audio_in = new Audio("{{ url('/frontend/Major/major/audio/door-bell.mp3')}}");
            var audio_out = new Audio("{{ url('/frontend/Major/major/audio/camera-beep.mp3')}}");
            var user_join = new Audio("{{ url('/frontend/Major/major/audio/user-join.mp3')}}");
            var new_msg = new Audio("{{ url('/frontend/Major/major/audio/new-message.mp3')}}");
            $("#in_newmark").hide();
            $("#out_newmark").hide();
            $("#join_newmark").hide();
            var updateInOutRequest = function (callback) {
                if (true) {
                    var timestamp = + new Date();
                    $.ajax({
                        url: apiUrl,
                        type: "GET",
                        data: {'ts':timestamp,
                            'last':lastRequest, 
                            'id': 
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
                                $("#in_newmark").text('('+inouts['add']+'건)');
                                $("#in_newmark").show();
                            }
                            if (inouts['out'] > 0)
                            {
                                if (inouts['rating'] > 0)
                                {
                                    audio_out.play();
                                }
                                $("#out_newmark").text('('+inouts['out']+'건)');
                                $("#out_newmark").show();
                            }
                            if (inouts['join'] > 0)
                            {
                                if (inouts['rating'] > 0)
                                {
                                    user_join.play();
                                }
                                $("#user_newmark").show();
                                $("#join_newmark").text('('+inouts['join']+'건)');
                                $("#join_newmark").show();
                            }
                            else
                            {
                                $("#user_newmark").hide();
                                $("#join_newmark").hide();
                            }


                            if (inouts['add'] == 0 && inouts['out'] == 0)
                            {
                                $("#in_newmark").hide();
                                $("#out_newmark").hide();
                            }

                            if (inouts['rating'] == 0)
                            {
                                $('#rateText').text('X');
                            }
                            else
                            {
                                $('#rateText').text('');
                            }

                            if (inouts['msg'] > 0)
                            {
                                if (inouts['rating'] > 0)
                                {
                                    new_msg.play();
                                }
                                $("#msgbutton").click();
                            }
                            $("#unreadmsgcount").text(inouts['msg']);
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
            @endif
            
        });

        $('#ratingOn').click(function (event) {
			var rating = 0;
			if($('#rateText').text() == 'X'){
				rating = 1;
			}
			$.ajax({
					url: "/api/inoutlist.json",
					type: "GET",
					data: {'rating': rating },
					dataType: 'json',
					success: function (data) {
                        if (rating == 0)
                        {
                            $('#rateText').text('X');
                        }
                        else
                        {
                            $('#rateText').text('');
                        }
                    }
				});
		});

        function closeNotification(notice,onlyOnce) {
            if (onlyOnce) {
                
            }
            else {
                localStorage.setItem("hide" + notice, Date.now());
            }

            $("#" + notice).hide();
        }

        function viewMsg(obj) {
                var content = obj.getAttribute('data-msg');
                var writer = obj.getAttribute('data-writer');
                var parent = obj.getAttribute('data-parent');
                var title = obj.getAttribute('data-title');

                var idx = obj.getAttribute('data-id');
                $.ajax({
					url: "/api/readMsg",
					type: "POST",
					data: {id: idx },
					success: function (data) {
                    }
				});
                $('#msgContent').html(content);
                $('#msgWriter').html(writer);
                $('#msgWriterParent').html(parent);
                $('#msgTitle').html(title);
        }
        @endif



    </script>
    </body>
</html>