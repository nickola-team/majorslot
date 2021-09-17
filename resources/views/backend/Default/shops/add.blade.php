@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.add_shop'))
@section('page-heading', trans('app.add_shop'))

@section('content')

<section class="content-header">
@include('backend.Default.partials.messages')
</section>

    <section class="content">
            {!! Form::open(['route' => $admurl.'.shop.store', 'files' => true, 'id' => 'user-form']) !!}
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">@lang('app.add_shop')</h3>
        </div>

        <div class="box-body">

            @include('backend.Default.shops.partials.base', ['edit' => false, 'profile' => false])
            <hr>
            <h4>매장관리자</h4>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label>@lang('app.username')</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="이름을 입력하지 않으면 매장이름과 같은 관리자를 생성합니다.">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label>{{ trans('app.password') }}</label>
                      <input type="password" class="form-control" id="password" name="password">
                  </div>
              </div>
            </div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">
                @lang('app.add_shop')
            </button>
        </div>
      </div>
            {!! Form::close() !!}
    </section>

@stop

@section('scripts')
    {!! JsValidator::formRequest('VanguardLTE\Http\Requests\User\CreateUserRequest', '#user-form') !!}
@stop    