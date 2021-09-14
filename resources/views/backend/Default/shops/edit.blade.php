@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.edit_shop'))
@section('page-heading', $shop->title)

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">

        {!! Form::open(['route' => array($admurl.'.shop.update', $shop->id), 'files' => true, 'id' => 'user-form']) !!}
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.edit_shop')</h3>
            </div>

            <div class="box-body">

                    @include('backend.Default.shops.partials.base', ['edit' => true])

            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    확인
                </button>
                @if(!Auth::user()->hasRole('manager') )
                <a href="{{ route($admurl.'.shop.delete', $shop->id) }}"
                   class="btn btn-danger"
                   data-method="DELETE"
                   data-confirm-title="@lang('app.please_confirm')"
                   data-confirm-text="@lang('app.are_you_sure_delete_shop')"
                   data-confirm-delete="@lang('app.yes_delete_him')">
                    @lang('app.delete_shop')
                </a>
                @endif


            </div>
        </div>
        {!! Form::close() !!}

    </section>

@stop
