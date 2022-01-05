@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '블랙리스트수정')
@section('page-heading', '블랙리스트수정')

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">블랙리스트수정</h3>
            </div>
			{!! Form::open(['route' => array($admurl.'.black.update', $user->id),  'id' => 'black-form']) !!}
            <div class="box-body">
				@include('backend.Default.user.partials.blackbase', ['edit'=>true])
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">
						수정
					</button>
                    <a href="{{ route($admurl.'.black.remove', $user->id) }}"
                        class="btn btn-danger"
                        data-method="DELETE"
                        data-confirm-title="@lang('app.please_confirm')"
                        data-confirm-text="블랙정보를 삭제하시겠습니까?"
                        data-confirm-delete="@lang('app.yes_delete_him')">
                            삭제
                    </a>
           		 </div>
            </div>
			{{ Form::close() }}
        </div>
    </section>

@stop
