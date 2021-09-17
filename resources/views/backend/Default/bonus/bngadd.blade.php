@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '보너스추가')
@section('page-heading', '보너스추가')

@section('content')

  <section class="content-header">
    @include('backend.Default.partials.messages')
  </section>

  <section class="content">
    <div class="box box-default">
      {!! Form::open(['route' => $admurl.'.bonus.bngstore']) !!}
      <div class="box-header with-border">
        <h3 class="box-title">보너스추가</h3>
      </div>

      <div class="box-body">
        <div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>회원아이디</label>
				@php
					$users = auth()->user()->hierarchyUserNamesOnly();
				@endphp
				{!! Form::select('player_id', $users, '', ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>게임</label>
				@php
					$games = array_column($gamelist, 'title', 'gamecode');
				@endphp
				{!! Form::select('game_id', $games, '', ['class' => 'form-control']) !!}
			</div>
		</div>
		</div>
		<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>보너스금</label>
				<input type="text" class="form-control" name="total_bet" placeholder="10000" required>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>만료기일</label>
				@php
					$times = ['1일후','2일후','3일후','4일후','5일후','6일후','7일후'];
				@endphp
				{!! Form::select('end_date', $times, '', ['class' => 'form-control']) !!}
			</div>
		</div>
        </div>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary">
          추가
        </button>
      </div>
      {!! Form::close() !!}
    </div>
  </section>

@stop