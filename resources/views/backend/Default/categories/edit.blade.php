@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.edit_category'))
@section('page-heading', $category->title)

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
        <div class="box box-danger">
            {!! Form::open(['route' => array($admurl.'.category.update', $category->id), 'files' => true, 'id' => 'user-form']) !!}
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.edit_category')</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    @include('backend.Default.categories.partials.base', ['edit' => true])
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    @lang('app.edit_category')
                </button>
                @permission('categories.delete')
                <a href="{{ route($admurl.'.category.delete', $category->id) }}"
                   class="btn btn-danger"
                   data-method="DELETE"
                   data-confirm-title="@lang('app.please_confirm')"
                   data-confirm-text="@lang('app.are_you_sure_delete_category')"
                   data-confirm-delete="@lang('app.yes_delete_him')">
                    Delete Category
                </a>
                @endpermission
            </div>
            {!! Form::close() !!}
        </div>
    </section>
@stop

