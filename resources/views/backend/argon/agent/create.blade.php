@extends('backend.argon.layouts.app')
@section('page-title',  '에이전트 생성')

@section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">에이전트 생성</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{argon_route('argon.common.profile.detail')}}" autocomplete="off">
                            @csrf
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="username">이름</label>
                                    <input type="text" name="username" id="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" value="" >
                                </div>
                                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="phone">레벨</label>
                                    <select class="form-control" id="role" name="role">
                                        @for ($level=3;$level<auth()->user()->role_id;$level++)
                                        <option value="{{$level}}" @if($level==auth()->user()->role_id-1) selected @endif> {{\VanguardLTE\Role::find($level)->description}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="parent">상위에이전트</label>
                                    <input type="text" name="parent" id="parent" class="form-control{{ $errors->has('parent') ? ' is-invalid' : '' }}" value="{{auth()->user()->username}}" >
                                </div>
                                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="phone">전화번호</label>
                                    <input type="text" name="phone" id="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="" >
                                </div>
				                <div class="form-group{{ $errors->has('deal_percent') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="deal_percent">롤링%</label>
                                    <input type="text" name="deal_percent" id="deal_percent" class="form-control{{ $errors->has('deal_percent') ? ' is-invalid' : '' }}" value="">
                                </div>
                                <div class="form-group{{ $errors->has('table_deal_percent') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="table_deal_percent">라이브롤링%</label>
                                    <input type="text" name="table_deal_percent" id="table_deal_percent" class="form-control{{ $errors->has('table_deal_percent') ? ' is-invalid' : '' }}" value="" >
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="password">비밀번호</label>
                                    <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4 col-6">생성</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
