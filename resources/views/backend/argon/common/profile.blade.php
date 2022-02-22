@extends('backend.argon.layouts.app')
@section('page-title',  '설정 및 정보')

@section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-xl-4 order-xl-2">
                <div class="card card-profile">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-circle">
                                    <div class="profile-circle">{{mb_substr($user->username, 0, 1)}}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center">
                                    <div>
                                        <span class="heading">{{number_format($user->balance)}}</span>
                                        <span class="description">현재 보유금</span>
                                    </div>
                                    <div>
                                        <span class="heading">{{number_format($user->childBalanceSum())}}</span>
                                        <span class="description">하부 총 보유금</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <?php
                                $badge_class = [
                                    'badge-default',
                                    'badge-primary',
                                    'badge-danger',
                                    'badge-success',
                                    'badge-info',
                                    'badge-warning',
                                ]
                            ?>
                            <h5 class="h3">
                                {{ $user->username }} 
                            </h5>
                            <div class="h5 font-weight-300">
                                <span class="badge {{$badge_class[$user->role_id-3]}}">{{$user->role->description}}</span>
                            </div>
                            
                        </div>
                        @if (auth()->user()->role_id > $user->role_id)
                        <div class="row" style="padding:1rem 0;">
                            <div class="col justify-content-center text-center">
                                <button type="button" class="btn btn-warning col-8">삭제</button>
                            </div>
                            @if (auth()->user()->hasRole('admin'))
                            <div class="col justify-content-center text-center">
                                <button type="button" class="btn btn-danger col-8">어드민삭제</button>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <!-- Latest Log -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <!-- Title -->
                        <h5 class="h3 mb-0">최근 페이지내역</h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="agentlist">
                        <thead class="thead-light">
                            <tr>
                            <th scope="col">시간</th>
                            <th scope="col">아이피</th>
                            <th scope="col">내역</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($userActivities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at}}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>{{ $activity->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">설정 및 정보</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="#" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">일반설정</h6>

                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">이름</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', auth()->user()->name) }}" required autofocus>

                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">전화번호</label>
                                    <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required>

                                </div>

				                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">롤링%</label>
                                    <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">라이브롤링%</label>
                                    <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">벳윈%</label>
                                    <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">메모</label>
                                    <textarea id="memo" name="memo" class="form-control" rows="5"></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">보관</button>
                                </div>
                            </div>
                        </form>
                        <hr class="my-4" />
                        <form method="post" action="#" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">비밀번호</h6>


                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-current-password">현재 비밀번호</label>
                                    <input type="password" name="old_password" id="input-current-password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Current Password') }}" value="" required>
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">새 비밀번호</label>
                                    <input type="password" name="password" id="input-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" required>

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">비밀번호 확인</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="{{ __('Confirm New Password') }}" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">비번변경</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
