@extends('backend.argon.layouts.app', ['class' => 'bg-light'])
@section('page-title',  '로그인' )
@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/login.css?v=1.0.0" rel="stylesheet">
@endpush
@section('content')
    @include('backend.argon.layouts.headers.guest')

    <div class="container mt--9 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
              <div class="container-login100">
                <div class="wrap-login100">
                  <form class="login100-form validate-form" role="form" method="POST" action="{{argon_route('argon.auth.login.post')}}">
                      @csrf
                      @if ($errors->count()>0)
                        <span class="login100-form-error">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                        </span>
                      @endif
                      <span class="login100-form-title pb-5">
                        ACCOUNT LOGIN
                      </span>

                    <div class="wrap-input100 rs1-wrap-input100 validate-input mb-4" data-validate="Type user name">
                      <input id="first-name" class="input100" type="text" name="username" placeholder="아이디">
                      <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 rs2-wrap-input100 validate-input mb-4" data-validate="Type password">
                      <input class="input100" type="password" name="password" placeholder="비밀번호">
                      <span class="focus-input100"></span>
                    </div>
                    <div class="container-login100-form-btn">
                      <button class="login100-form-btn">
                        로그인
                      </button>
                    </div>
                    
                  </form>
                  <div class="login100-more login_bg"></div>
                </div>
            </div>
          </div>
        </div>
    </div>
@endsection
