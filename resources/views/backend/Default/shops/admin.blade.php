@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.add_shop'))
@section('page-heading', trans('app.add_shop'))

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
        {!! Form::open(['route' => $admurl.'.shop.admin_store', 'files' => true, 'id' => 'user-form']) !!}

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.add_shop')</h3>
            </div>

            <div class="box-body">
                {{-- @foreach(['5' => 'agent', '4' => 'distributor', 'shop' => 'shop', '3' => 'manager', '2' => 'cashier'] AS $role_id=>$role_name) --}}
                @foreach(['5' => 'agent', '4' => 'distributor', 'shop' => 'shop', '3' => 'manager'] AS $role_id=>$role_name)

                    @if($role_id == 'shop')

                        <h4>@lang('app.shop')</h4>

                        @include('backend.Default.shops.partials.base', ['edit' => false, 'profile' => false])

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.balance') }}</label>
                                    <input type="text" class="form-control" name="balance" value="{{ old('balance')?:0 }}">
                                </div>
                            </div>
                        </div>

                    @else

                        {{-- <h4>{{ strtoupper($role_name) }}</h4> --}}
                        @if($role_name == 'agent')
                            <h4>본사</h4>
                        @elseif($role_name == 'distributor')
                            <h4>총판</h4>
                        @else
                            <h4>매장관리자</h4>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('app.username')</label>
                                    <input type="text" class="form-control" id="username" name="{{ $role_name }}[username]" placeholder="(@lang('app.optional'))" value="{{ old($role_name)['username'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('app.status')</label>
                                    {!! Form::select($role_name.'[status]', $statuses, old($role_name)['status'] , ['class' => 'form-control', 'id' => 'status', '']) !!}
                                </div>
                            </div>
                            @if($role_name != 'cashier' && $role_name != 'manager')
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.balance') }}</label>
                                    <input type="text" class="form-control" id="balance" name="{{ $role_name }}[balance]" value="{{ old($role_name)['balance']?:0 }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label>딜비%</label>
                                <input type="number" class="form-control" id="deal_percent" name="deal_percent" value="0">
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.password') }}</label>
                                    <input type="password" class="form-control" id="password" name="{{ $role_name }}[password]" value="{{ old($role_name)['password'] }}">
                                </div>
                            </div>
                        </div>

                    @endif

                    <hr>

                @endforeach


                <h4>회원생성</h4>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>인원수</label>
                            {!! Form::select('users[count]', [1=>1,5=>5,10=>10,25=>25,50=>50,100=>100], old('users')['count'] , ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('app.balance')</label>
                            <input type="text" class="form-control" id="title" name="users[balance]" value="{{ old('users')['balance']?:0 }}">
                        </div>
                    </div>

                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label>@lang('app.returns')</label>
                            {!! Form::select('users[return]', [0=>0,1=>1,5=>5,10=>10,20=>20,30=>30,40=>40,50=>50], old('users')['return'] , ['class' => 'form-control']) !!}
                        </div>
                    </div> --}}

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        @lang('app.add_shop')
                    </button>
                </div>

            </div>



        </div>

        {!! Form::close() !!}
    </section>

@stop