@extends('backend.argon.layouts.app', ['class' => 'bg-white'])
@section('page-title',  '로그인' )
@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/login.css?v=2.0.0" rel="stylesheet">
@endpush
@section('content')
    <section class="h-100">     
      <div class="container col h-100">
        <div class="row h-100">
          <div class="col-xl-6 col-lg-6 col-md-6 mx-lg-0 m-auto d-flex flex-column">
            <div class="card card-plain m-auto" style="box-shadow:none!important; max-width:640px">
              <div class="card-body">
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
                        로그인
                      </span>

                    <div class="validate-input mb-4" data-validate="Type user name">
                      <input id="first-name" class="input100" type="text" name="username" placeholder="아이디">
                      <span class="focus-input100"></span>
                    </div>
                    <div class="validate-input mb-4" data-validate="Type password">
                      <input class="input100" type="password" name="password" placeholder="비밀번호">
                      <span class="focus-input100"></span>
                    </div>
                    <div class="container-login100-form-btn">
                      <button class="login100-form-btn">
                        로그인
                      </button>
                    </div>
                    
                  </form>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 mx-lg-0 mx-auto p-0 d-lg-flex d-none flex-column">
          <!-- <div class="video_bg"><video autoplay=""><source src="/back/argon/img/theme/universe.e89b0470b8bb1c0f6059.mp4"></video></div> -->
          <div class="position-relative h-100 border-radius-lg justify-content-center overflow-hidden m-0 p-0" style="background-image: url('/back/argon/img/theme/bg-worldsl.jpg');
            background-size: cover;"> 
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
