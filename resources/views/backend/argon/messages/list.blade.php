@extends('backend.argon.layouts.app')

@section('page-title',  '쪽지')

@section('content')
<div class="container-fluid">
	<div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header border-0">
                    <div class="pull-right">
						<a href="{{ argon_route('argon.msg.create') }}" class="btn btn-primary box-button">보내기</a>
					</div>
                    <h3 class="mb-0">쪽지</h3>
                </div>
                <div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
						<tr>
							<th scope="col">회원아이디</th>
							<th scope="col">제목</th>
							<th scope="col">등록날짜</th>
							<th scope="col">읽은날짜</th>
							@if (auth()->user()->hasRole('admin'))
							<th scope="col">작성자</th>
							@endif
							<th scope="col"></th>
						</tr>
						</thead>
						<tbody>
						@if (count($msgs))
							@foreach ($msgs as $msg)
								@include('backend.argon.messages.partials.row')
							@endforeach
						@else
							<tr><td colspan='9'>No Data</td></tr>
						@endif
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>
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

@push('js')
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
@endpush