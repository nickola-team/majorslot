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
                                    @if (!$user->hasRole('user'))
                                    <div>
                                        <span class="heading">{{number_format($user->childBalanceSum())}}</span>
                                        <span class="description">하부 총 보유금</span>
                                    </div>
                                    @else
                                    <div>
                                        <span class="heading">{{number_format($user->total_in)}}</span>
                                        <span class="description">총 충전금</span>
                                    </div>
                                    <div>
                                        <span class="heading">{{number_format($user->total_out)}}</span>
                                        <span class="description">총 환전금</span>
                                    </div>
                                    @endif
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
                                {{ $user->username }} , {{$user->id}}
                            </h5>
                            <div class="h5 font-weight-300">
                                <span class="badge {{$badge_class[$user->role_id-3]}}">{{$user->role->description}}</span>
                            </div>
                            <div class="h5 font-weight-300">
                                {{$user->created_at}}
                            </div>
                            <div class="h5">
                                {{auth()->user()->isInOutPartner()?$user->parents():''}}
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
                            @if (auth()->user()->isInOutPartner())
                            <th scope="col">아이피</th>
                            @endif
                            <th scope="col">내역</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($userActivities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at}}</td>
                                    @if (auth()->user()->isInOutPartner())
                                        <td>{{ $activity->ip_address }}</td>
                                    @endif
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
                        <form method="post" action="#" autocomplete="off">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">일반설정</h6>

                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="phone">전화번호</label>
                                    <input type="text" name="phone" id="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone', $user->phone) }}" {{$user->id != auth()->user()->id?'disabled':''}}>
                                </div>
                                @if ($user->id == auth()->user()->id)
                                <div class="form-group{{ $errors->has('bank_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="bank_name">은행</label>
                                    @php
                                        $banks = array_combine(\VanguardLTE\User::$values['banks'], \VanguardLTE\User::$values['banks']);
                                    @endphp
                                    {!! Form::select('bank_name', $banks, $user->bank_name ? auth()->user()->bank_name : '', ['class' => 'form-control', 'id' => 'bank_name']) !!}		
                                </div>
                                <div class="form-group{{ $errors->has('account_no') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="account_no">계좌번호</label>
                                    <input type="text" name="account_no" id="account_no" class="form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" value="{{ old('account_no', $user->account_no) }}">
                                </div>
                                <div class="form-group{{ $errors->has('recommender') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="recommender">예금주명</label>
                                    <input type="text" name="recommender" id="recommender" class="form-control{{ $errors->has('recommender') ? ' is-invalid' : '' }}" value="{{ old('recommender', $user->recommender) }}">
                                </div>
                                @endif

				                <div class="form-group{{ $errors->has('deal_percent') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="deal_percent">롤링%</label>
                                    <input type="text" name="deal_percent" id="deal_percent" class="form-control{{ $errors->has('deal_percent') ? ' is-invalid' : '' }}" value="{{ old('deal_percent', $user->deal_percent) }}" {{$user->id == auth()->user()->id?'disabled':''}}>
                                </div>
                                <div class="form-group{{ $errors->has('table_deal_percent') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="table_deal_percent">라이브롤링%</label>
                                    <input type="text" name="table_deal_percent" id="table_deal_percent" class="form-control{{ $errors->has('table_deal_percent') ? ' is-invalid' : '' }}" value="{{ old('table_deal_percent',$user->table_deal_percent) }}" {{$user->id == auth()->user()->id?'disabled':''}}>
                                </div>
                                @if (auth()->user()->isInOutPartner())
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">메모</label>
                                    @if ($user->memo)
                                    <p> 작성날짜 : {{$user->memo->created_at}} 저장날짜 : {{$user->memo->updated_at}}</p>
                                    @endif
                                    <textarea id="memo" name="memo" class="form-control" rows="5">{{$user->memo?$user->memo->memo:''}}</textarea>
                                </div>
                                @endif
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">보관</button>
                                </div>
                            </div>
                        </form>
                        @if (auth()->user()->isInOutPartner() || $user->id == auth()->user()->id)
                        <hr class="my-4" />
                        <form method="post" action="#" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">비밀번호</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">새 비밀번호</label>
                                    <input type="password" name="password" id="input-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="" value="" required>

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">비밀번호 확인</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">비번변경</button>
                                </div>
                            </div>
                        </form>
                        @endif
                        @if (auth()->user()->isInOutPartner())
                        <hr class="my-4" />
                        <h6 class="heading-small text-muted mb-4">환전비밀번호</h6>
                        <div class="pl-lg-4">
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning mt-4">환전비번리셋</button>
                            </div>
                        </div>
                        @elseif ($user->id == auth()->user()->id)
                        <hr class="my-4" />
                        <form method="post" action="#" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">환전비밀번호</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">기존 비밀번호</label>
                                    <input type="password" name="password" id="input-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="" value="" required>

                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">새 비밀번호</label>
                                    <input type="password" name="password" id="input-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="" value="" required>

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">비밀번호 확인</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-warning mt-4">환전비번 변경</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
