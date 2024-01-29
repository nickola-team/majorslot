@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '쪽지관리')
@section('page-heading', '쪽지관리')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">쪽지관리</h3>
				<div class="pull-right box-tools">
					<a href="{{ route($admurl.'.msg.create') }}" class="btn btn-block btn-primary btn-sm">보내기</a>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>회원아이디</th>
						<th>제목</th>
						<th>등록날짜</th>
						<th>읽은날짜</th>
                        @if (auth()->user()->hasRole('admin'))
                        <th>작성자</th>
                        @endif
						<th>보기</th>
                        <th>삭제</th>
					</tr>
					</thead>
					<tbody>
					@if (count($msgs))
						@foreach ($msgs as $msg)
							@include('backend.Default.messages.partials.row')
						@endforeach
					@else
						<tr><td colspan="6">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>회원아이디</th>
						<th>제목</th>
						<th>등록날짜</th>
						<th>읽은날짜</th>
                        @if (auth()->user()->hasRole('admin'))
                        <th>작성자</th>
                        @endif
						<th>보기</th>
                        <th>삭제</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</section>
	<div class="modal fade" id="openMsgModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">쪽지내용</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<span id="content"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary"  data-dismiss="modal">확인</button>
					</div>
			</div>
		</div>
	</div>
@stop

@section('scripts')
<script>
	$(function() {
		$('.viewMsg').click(function(event){
			if( $(event.target).is('.newMsg') ){
				var content = $(event.target).attr('data-msg');
			}else{
				var content = $(event.target).parents('.newMsg').attr('data-msg');
			}
			$('#content').html(content);
		});
	});
</script>
@stop