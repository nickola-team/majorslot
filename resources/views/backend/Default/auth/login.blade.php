@extends('backend.Default.layouts.'.$layout.'.auth')

@section('page-title', trans('app.login'))

@section('content')

  <div class="login-box">
    <div class="login-logo">
    <a href="{{ route('backend.dashboard') }}">
    @if (isset($site) && $layout == 'Top')
      <img src="{{ url('/back/img/' . $site->frontend . '_logo.png') }}" style="width:100%;"></img>
    @else
      <span class="logo-lg"><b>{{ $title }} 로그인</span>
    @endif
    </a>
    </div>

              @include('backend.Default.partials.messages')

    <div class="login-box-body" style={{ $layout == 'Top'?'background-color:lightblue;':''}}>

      <form role="form" action="<?= route('backend.auth.login.post') ?>" method="POST" id="login-form" autocomplete="off">

        <input type="hidden" value="<?= csrf_token() ?>" name="_token">

        <div class="form-group has-feedback">
          <input type="text" name="username" id="username" class="form-control" placeholder="@lang('app.username')">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input type="password" name="password" id="password" class="form-control" placeholder="@lang('app.password')">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

          <div class="form-group" style="display: none">
              {!! Form::select('lang', $directories, 'ko', ['class' => 'form-control']) !!}
          </div>

        <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn {{ $layout == 'Top'?'btn-success':'btn-primary'}} btn-block btn-flat" id="btn-login">
              @lang('app.log_in')
            </button>

          </div>
        </div>
      </form>

    </div>
    <div class="alert alert-warning" style="margin-top:10px;display:none;" id="browsercheck">
        <h4>크롬브라우저 권장</h4>
        <p>크롬 브라우저가 아닌 브라우저를 이용하시는 경우 예기지 않은 문제가 발생할수 있습니다.</p>
    </div>
    <div class="alert alert-error" style="margin-top:10px;display:none;" id="browsernotsupport">
        <h4>브라우저 오류</h4>
        <p>이 페이지는 Internet Explorer를 지원하지 않습니다. 크롬 브라우저를 이용하세요.</p>
    </div>
  </div>

  <script src="/back/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="/back/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="/back/plugins/iCheck/icheck.min.js"></script>
  <script>
      $( document ).ready(function() {
        let userAgentString = navigator.userAgent.toLowerCase();
        if (userAgentString.indexOf('MSIE') > -1 || userAgentString.indexOf("rv:") > -1) {
          $("#browsernotsupport").show();
        }
        else if (userAgentString.indexOf('chrome') < 0) {
          $("#browsercheck").show();
        }
        
      });
  </script>
@stop

@section('scripts')
  {!! JsValidator::formRequest('VanguardLTE\Http\Requests\Auth\LoginRequest', '#login-form') !!}

@stop
