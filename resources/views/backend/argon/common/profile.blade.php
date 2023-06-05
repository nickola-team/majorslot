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
                                        <span class="heading">{{$user->hasRole('manager')?number_format($user->shop->balance):number_format($user->balance)}}</span>
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
                                $status_class = \VanguardLTE\Support\Enum\UserStatus::bgclass(); 
                                $badge_class = \VanguardLTE\User::badgeclass();
                                $statuses = \VanguardLTE\Support\Enum\UserStatus::lists();
                                if (!$deluser)
                                {
                                    unset($statuses[\VanguardLTE\Support\Enum\UserStatus::DELETED]);
                                }
                            ?>
                            <h5 class="h3">
                                {{ $user->username }} , {{$user->id}}
                            </h5>
                            <div class="h5 font-weight-300">
                                <span class="badge {{$badge_class[$user->role_id]}}">{{$user->role->description}}</span>&nbsp; <span class="badge badge-dot mr-4"><i class="{{$status_class[$user->status]}}"></i><span class="status">{{$statuses[$user->status]}}</span></span>
                            </div>
                            <div class="h5 font-weight-300">
                                {{$user->created_at}}
                            </div>
                            <div class="h5">
                                {{$user->parents(auth()->user()->role_id)}}
                            </div>
                        </div>
                        @if (auth()->user()->role_id > $user->role_id)
                        <div class="row" style="padding:1rem 0;">
                            @if ($deluser)
                            <div class="col justify-content-center text-center">
                            <a href="{{argon_route('argon.common.profile.delete',['id'=>$user->id])}}"
                                class="btn btn-warning col-8"
                                data-method="DELETE"
                                data-confirm-title="확인"
                                data-confirm-text="유저를 삭제하시겠습니까?"
                                data-confirm-delete="확인"
                                data-confirm-cancel="취소">
                                삭제
                            </a>
                            </div>
                            @endif
                            @if (auth()->user()->hasRole('admin'))
                            <div class="col justify-content-center text-center">
                                <a href="{{argon_route('argon.common.profile.delete',['id'=>$user->id, 'hard'=>1])}}"
                                    class="btn btn-warning col-8"
                                    data-method="DELETE"
                                    data-confirm-title="확인"
                                    data-confirm-text="유저를 삭제하시겠습니까?"
                                    data-confirm-delete="확인"
                                    data-confirm-cancel="취소">
                                    어드민삭제
                                </a>
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
                            @if (auth()->user()->isInoutPartner())
                            <th scope="col">아이피</th>
                            @endif
                            <th scope="col">내역</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($userActivities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at}}</td>
                                    @if (auth()->user()->isInoutPartner())
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
                        <form method="post" action="{{argon_route('argon.common.profile.detail')}}" autocomplete="off">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">일반설정</h6>
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="pl-lg-4">
                                @if ($user->isInoutPartner())
                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="address">텔레연락처</label>
                                    <input type="text" name="address" id="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address', $user->address) }}">
                                </div>
                                @endif
                                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="phone">폰연락처</label>
                                    <input type="text" name="phone" id="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone', $user->phone) }}" >
                                </div>
                                @if ($user->id == auth()->user()->id || auth()->user()->isInoutPartner())
                                <div class="form-group{{ $errors->has('bank_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="bank_name">은행</label>
                                    @php
                                        $banks = array_combine(\VanguardLTE\User::$values['banks'], \VanguardLTE\User::$values['banks']);
                                    @endphp
                                    {!! Form::select('bank_name', $banks, $user->bank_name ? $user->bank_name : '', ['class' => 'form-control', 'id' => 'bank_name', 'disabled' => !auth()->user()->isInoutPartner()]) !!}		
                                </div>
                                <?php
                                    $accno = $user->account_no;
                                    $recommender = $user->recommender;
                                    if (!$user->isInOutPartner() && $user->id == auth()->user()->id)
                                    {
                                        if ($accno != '')
                                        {
                                            $maxlen = strlen($accno)>1?2:1;
                                            $accno = '******' . substr($accno, -$maxlen);
                                        }
                                        if ($recommender != '')
                                        {
                                            $recommender = mb_substr($recommender, 0, 1) . '***';
                                        }
                                    }
                                ?>
                                <div class="form-group{{ $errors->has('account_no') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="account_no">계좌번호</label>
                                    <input type="text" name="account_no" id="account_no" class="form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" value="{{ $accno }}"  {{auth()->user()->isInoutPartner()?'':'disabled'}}>
                                </div>
                                <div class="form-group{{ $errors->has('recommender') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="recommender">예금주명</label>
                                    <input type="text" name="recommender" id="recommender" class="form-control{{ $errors->has('recommender') ? ' is-invalid' : '' }}" value="{{ $recommender }}"  {{auth()->user()->isInoutPartner()?'':'disabled'}}>
                                </div>
                                @endif

				                <div class="form-group table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <tr>
                                            <th>슬롯롤링%</th>
                                            <th>라이브롤링%</th>
                                            <th style="width:25%"></th>
                                            <th style="width:25%"></th>
                                        </tr>
                                        <tr>
                                            <td style="padding:3px;"><input type="text" name="deal_percent" id="deal_percent" class="form-control" value="{{$user->deal_percent??0}}" {{$user->id==auth()->user()->id?'disabled':''}}></td>
                                            <td style="padding:3px;"><input type="text" name="table_deal_percent" id="table_deal_percent" class="form-control" value="{{$user->table_deal_percent??0}}" {{$user->id==auth()->user()->id?'disabled':''}}></td>
                                            <td style="padding:3px;"></td>
                                            <td style="padding:3px;"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <tr>
                                            <th>슬롯죽장%</th>
                                            <th>라이브죽장%</th>
                                            <th style="width:25%"></th>
                                            <th style="width:25%"></th>
                                        </tr>
                                        <tr>
                                            <td style="padding:3px;"><input type="text" name="ggr_percent" id="ggr_percent" class="form-control" value="{{$user->ggr_percent??0}}" {{$user->id==auth()->user()->id?'disabled':''}}></td>
                                            <td style="padding:3px;"><input type="text" name="table_ggr_percent" id="table_ggr_percent" class="form-control" value="{{$user->table_ggr_percent??0}}" {{$user->id==auth()->user()->id?'disabled':''}}></td>
                                            <td style="padding:3px;"></td>
                                            <td style="padding:3px;"></td>
                                        </tr>
                                    </table>
                                </div>
                                @if (auth()->user()->isInOutPartner())
                                <div class="form-group">
                                    <label class="form-control-label">상태</label>
                                    {!! Form::select('status', $statuses, $user->status,
                                        ['class' => 'form-control', 'id' => 'status', 'disabled' => ($user->hasRole(['admin']) || $user->id == auth()->user()->id) ? true: false]) !!}
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">메모</label>
                                    @if ($user->memo)
                                    <h6 class="heading-small text-muted">작성날짜 : {{$user->memo->created_at}} 저장날짜 : {{$user->memo->updated_at}}</h6>
                                    @endif
                                    <textarea id="memo" name="memo" class="form-control" rows="5">{{$user->memo?$user->memo->memo:''}}</textarea>
                                </div>
                                @if (auth()->user()->hasRole('admin') && $user->isInOutPartner())
                                    <div class="form-group table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <tr>
                                                <th>게임관리권한</th>
                                                <th>파트너간 머니이동</th>
                                                <th>유저삭제기능</th>
                                                <th>유저가입 승인</th>
                                            </tr>
                                            <tr>
                                                <td><label class="custom-toggle"><input type="checkbox" name="gameOn" {{isset($user->sessiondata()['gameOn']) && $user->sessiondata()['gameOn']==1?'checked':''}}><span class="custom-toggle-slider rounded-circle" data-label-off="없음" data-label-on="있음"></span></label></td>
                                                <td><label class="custom-toggle"><input type="checkbox" name="moneyperm" {{isset($user->sessiondata()['moneyperm']) && $user->sessiondata()['moneyperm']==1?'checked':''}}><span class="custom-toggle-slider rounded-circle" data-label-off="불가능" data-label-on="가능"></span></label></td>
                                                <td><label class="custom-toggle"><input type="checkbox" name="deluser" {{empty($user->sessiondata()['deluser']) || $user->sessiondata()['deluser']==1?'checked':''}}><span class="custom-toggle-slider rounded-circle" data-label-off="불가능" data-label-on="가능"></span></label></td>
                                                <td><label class="custom-toggle"><input type="checkbox" name="manualjoin" {{isset($user->sessiondata()['manualjoin']) && $user->sessiondata()['manualjoin']==1?'checked':''}}><span class="custom-toggle-slider rounded-circle" data-label-off="자동" data-label-on="수동"></span></label></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="input-gameOn">게임환수율%</label>
                                        <input type="text" name="gamertp" id="gamertp" class="form-control{{ $errors->has('gamertp') ? ' is-invalid' : '' }}" value="{{ number_format($rtppercent,2) }}" >
                                    </div>
                                @endif
                                @endif
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">보관</button>
                                </div>
                            </div>
                        </form>
                        @if ($user->role_id >= 3 && auth()->user()->role_id>$user->role_id && auth()->user()->hasRole(['admin','comaster']))
                        <hr class="my-4" />
                        <form method="post" action="{{argon_route('argon.common.profile.accessrule')}}" autocomplete="off">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">접근설정</h6>
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('ip_address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="ip_address">접근아이피 <span class="text-red"> ***아이피가 여러개이면 반점(,)으로 구분해주세요***</span></label>
                                    <textarea id="ip_address" name="ip_address" class="form-control" rows="5">{{$user->accessrule?$user->accessrule->ip_address:''}}</textarea>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="form-control-label" for="user_agent">브라우저</label>
                                    <textarea id="user_agent" name="user_agent" class="form-control" rows="5"></textarea>
                                </div> -->
                                <div class="form-group">
                                    <label class="form-control-label" for="allow_ipv6">IPv6 허용</label>
                                    <div>
                                    <label class="custom-toggle"><input type="checkbox" name="allow_ipv6" {{$user->accessrule==null || $user->accessrule->allow_ipv6==1?'checked':''}}><span class="custom-toggle-slider rounded-circle" data-label-off="안함" data-label-on="허용"></span></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="check_cloudflare">클플아이피 검사</label>
                                    <div>
                                    <label class="custom-toggle"><input type="checkbox" name="check_cloudflare" {{$user->accessrule && $user->accessrule->check_cloudflare==1?'checked':''}}><span class="custom-toggle-slider rounded-circle" data-label-off="안함" data-label-on="적용"></span></label>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">적용</button>
                                </div>
                            </div>
                        </form>
                        @endif

                        @if ($user->id == auth()->user()->id || auth()->user()->isInoutPartner() || (auth()->user()->hasRole('manager') && $user->hasRole('user')))
                        <hr class="my-4" />
                        <form method="post" action="{{argon_route('argon.common.profile.password')}}" autocomplete="off">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">비밀번호</h6>
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="password">새 비밀번호</label>
                                    <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="" value="" required>

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="password_confirmation">비밀번호 확인</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="" value="" required>
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
                                <a href="{{argon_route('argon.common.profile.resetdwpass', ['id'=>$user->id])}}"><button type="submit" class="btn btn-warning mt-4">환전비번리셋</button></a>
                            </div>
                        </div>
                        @elseif ($user->id == auth()->user()->id)
                        <hr class="my-4" />
                        <form method="post" action="{{argon_route('argon.common.profile.dwpass')}}" autocomplete="off">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">환전비밀번호</h6>
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('confirmation_token') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="old_confirmation_token">기존 비밀번호</label>
                                    <input type="password" name="old_confirmation_token" id="old_confirmation_token" class="form-control{{ $errors->has('old_confirmation_token') ? ' is-invalid' : '' }}" placeholder="" value="">

                                </div>
                                <div class="form-group{{ $errors->has('confirmation_token') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="confirmation_token">새 비밀번호</label>
                                    <input type="password" name="confirmation_token" id="confirmation_token" class="form-control{{ $errors->has('confirmation_token') ? ' is-invalid' : '' }}" placeholder="" value="" required>

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="confirmation_token_confirmation">비밀번호 확인</label>
                                    <input type="password" name="confirmation_token_confirmation" id="confirmation_token_confirmation" class="form-control" placeholder="" value="" required>
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
