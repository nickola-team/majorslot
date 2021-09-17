@extends('backend.Default.layouts.'.$layout.'.app')
@if( Auth::user()->hasRole('cashier') || Auth::user()->hasRole('manager'))
@section('page-title', '회원추가')
@section('page-heading', '회원추가')
@else
@section('page-title', '파트너추가')
@section('page-heading', '파트너추가')
@endif
@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">

        @if( Auth::user()->hasRole('cashier') )


            @if($happyhour && auth()->user()->hasRole('cashier'))
                <div class="alert alert-success">
                    <h4>@lang('app.happyhours')</h4>
                    <p> @lang('app.all_player_deposits') {{ $happyhour->multiplier }}</p>
                </div>
            @endif


            {!! Form::open(['route' => $admurl.'.user.massadd', 'files' => true, 'id' => 'mass-user-form']) !!}
            <div class="box box-default">
                <div class="box-header with-border">
                    @if( Auth::user()->hasRole('cashier') || Auth::user()->hasRole('manager'))
                    <h3 class="box-title">회원추가</h3>
                    @else
                    <h3 class="box-title">파트너추가</h3>
                    @endif
                </div>

                <div class="box-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('app.count')</label>
                                <select name="count" class="form-control">
                                    <option value="1">1</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('app.balance')</label>
                                <input type="text" class="form-control" id="title" name="balance" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        @if( Auth::user()->hasRole('cashier') || Auth::user()->hasRole('manager'))
                        회원추가
                        @else
                        파트너추가
                        @endif
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        @endif

        {!! Form::open(['route' => $admurl.'.user.store', 'files' => true, 'id' => 'user-form']) !!}

        <div class="box box-default">
            <div class="box-header with-border">
                @if( Auth::user()->hasRole('cashier') || Auth::user()->hasRole('manager'))
                <h3 class="box-title">회원추가</h3>
                @else
                <h3 class="box-title">파트너추가</h3>
                @endif
                
            </div>

            <div class="box-body">
                <div class="row">

                    @include('backend.Default.user.partials.create')

                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    확인
                </button>
            </div>
        </div>

        {!! Form::close() !!}

    </section>
@stop

@section('scripts')
    {!! JsValidator::formRequest('VanguardLTE\Http\Requests\User\CreateUserRequest', '#user-form') !!}

    <script>

        $("#role_id").change(function (event) {
            var role_id = parseInt($('#role_id').val());
            $("#parent > option").each(function() {
                var id = parseInt($(this).attr('role'));
                if( (id - role_id) != 1 ){
                    $(this).attr('hidden', true);
                } else{
                    $(this).attr('hidden', false);
                }
                $(this).attr('selected', false);
            });
            $('#parent option[value=""]').attr('selected', true);
        });

        $("#role_id").trigger('change');

    </script>
@stop