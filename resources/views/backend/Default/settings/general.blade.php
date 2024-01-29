@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.general_settings'))
@section('page-heading', trans('app.general_settings'))

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">

        <div class="box box-default">
            {!! Form::open(['route' => $admurl.'.settings.general.update', 'id' => 'general-settings-form']) !!}
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.general_settings')</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('app.name')</label>
                            <input type="text" class="form-control" id="app_name" name="app_name" value="{{ settings('app_name') }}">
                        </div>

                    @include('backend.Default.settings.partials.auth', ['directories' => $directories])

                    </div>
                    <div class="col-md-6">
                    @include('backend.Default.settings.partials.throttling')
                    @include('backend.Default.settings.partials.registration')
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    @lang('app.edit_settings')
                </button>


                <button type="button" class="btn btn-primary" id="generate-freespin" onclick="check_pp();">
                    프라그메틱 체크
                </button>
            </div>
            <div class="row" id="pplog">

            </div>

            
            {{ Form::close() }}
        </div>
    </section>

@stop

@section('scripts')
    <script>
        function check_pp() {
            
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: 'api/generateFreespin',
                data: { _token: _token },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        //alert(data.msg);
                        $("#pplog").html(data.msg);
                        return;
                    }
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }
    </script>
@stop