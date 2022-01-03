@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '블랙리스트추가')
@section('page-heading', '블랙리스트추가')

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">블랙리스트 추가</h3>
            </div>
			{!! Form::open(['route' => $admurl.'.black.store', 'id' => 'csv-upload-form']) !!}

            <div class="box-body">
				<div class="row">
					<div class="col-md-6">
					<div class="form-group">
                        <label>이름</label>
                        <input type="text" class="form-control" name="name" placeholder="이름">
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
                        <label>폰번호</label>
                        <input type="text" class="form-control" name="phone" placeholder="">
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
                        <label>은행</label>
                        <input type="text" class="form-control" name="account_bank" placeholder="">
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
                        <label>예금주</label>
                        <input type="text" class="form-control" name="account_name" placeholder="">
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
                        <label>계좌번호</label>
                        <input type="text" class="form-control" name="account_number" placeholder="">
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
                        <label>설명</label>
                        <input type="text" class="form-control" name="memo" placeholder="">
					</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">
						추가
					</button>
           		 </div>
            </div>
			{{ Form::close() }}
        </div>
    </section>

@stop
