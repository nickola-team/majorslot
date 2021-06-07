<header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container" style="width:100%;">
        <div class="navbar-header">
          <a href="/backend" class="navbar-brand"><b></b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
          @permission('dashboard')
            @if (auth()->user()->hasRole('admin') && !Session::get('isCashier'))
            <li class="{{ Request::is('backend') ? 'active' : ''  }}">
                <a href="{{ route('backend.dashboard') }}">
                    <i class="fa fa-home"></i>
                    <span>@lang('app.dashboard')</span>
                </a>
            </li>
            @endif
            @endpermission


            @permission('users.tree')
            @if (auth()->user()->hasRole(['admin','comaster','master','agent']))
            <li class="{{ Request::is('backend/tree*') ? 'active' : ''  }}">
                <a href="{{ route('backend.user.tree') }}">
                    <i class="fa fa-users"></i>
                    <span>파트너생성</span>
                </a>
            </li>
            @endif
            @endpermission
            @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster','master','agent', 'distributor']) )
            <li class="dropdown {{ Request::is('backend/shops*') || Request::is('backend/partner*') || Request::is('backend/user*')  ? 'active' : '' }}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-users"></i>
                    <span>파트너리스트</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li class="{{ Request::is('backend/shops') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.shop.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>매장리스트</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/user*') ? 'active' : ''  }}">
                        <a href="{{ route('backend.user.list') }}">
                            <i class="fa fa-users"></i>
                            <span>회원리스트</span>
                        </a>
                    </li>
                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster','master','agent']) )
                    <li class="{{ Request::is('backend/partner/4') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.user.partner', 4) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>총판리스트</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster','master']) )
                    <li class="{{ Request::is('backend/partner/5') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.user.partner', 5) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>부본사리스트</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster']) )
                    <li class="{{ Request::is('backend/partner/6') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.user.partner', 6) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>본사리스트</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin']) )
                    <li class="{{ Request::is('backend/partner/6') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.user.partner', 6) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>총본사리스트</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @else
            <li class="{{ Request::is('backend/user*') ? 'active' : ''  }}">
                        <a href="{{ route('backend.user.list') }}">
                            <i class="fa fa-users"></i>
                            <span>회원리스트</span>
                        </a>
                    </li>
            @endif
            @if ( auth()->check() && auth()->user()->hasRole(['admin']) )
            <li class="dropdown {{Request::is('backend/category*') || Request::is('backend/jpgame*') || Request::is('backend/game*') ? 'active' : '' }}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-users"></i>
                    <span>게임리스트</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu" role="menu">
                @permission('categories.manage')
                @if ( auth()->check() && auth()->user()->hasRole('admin')  && !Session::get('isCashier'))
                <li class="{{ Request::is('backend/category') ? 'active' : ''  }}">
                    <a  href="{{ route('backend.category.list') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>게임카테고리관리</span>
                    </a>
                </li>              
                @endif
                @endpermission      
                
                @permission('jpgame.manage')
                @if ( auth()->check() && auth()->user()->hasRole('admin')  && !Session::get('isCashier'))
                <li class="{{ Request::is('backend/jpgame*') ? 'active' : ''  }}">
                    <a href="{{ route('backend.jpgame.list') }}">
                        <i class="fa  fa-circle-o"></i>
                        <span>잭팟관리</span>
                    </a>
                </li>
                @endif
                @endpermission
                @permission('games.manage')
                @if ( auth()->check() && auth()->user()->hasRole('admin')  && !Session::get('isCashier'))
                <li class="{{ (Request::is('backend/game') || Request::is('backend/game/*')) ? 'active' : ''  }}">
                    <a href="{{ route('backend.game.list') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>게임관리</span>
                    </a>
                </li>
                @endif
                @endpermission

                @if ( auth()->check() && auth()->user()->hasRole('admin')  && !Session::get('isCashier'))
                <li class="{{ (Request::is('backend/gamebank') || Request::is('backend/gamebank/*')) ? 'active' : ''  }}">
                    <a href="{{ route('backend.game.bank') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>환수금관리</span>
                    </a>
                </li>
                @endif
                </ul>
            </li>
            @endif

            @permission('happyhours.manage')
            @if( auth()->user()->hasRole('admin')  && !Session::get('isCashier'))
            <li class="{{ Request::is('backend/happyhours*') ? 'active' : ''  }}">
                <a href="{{ route('backend.happyhour.list') }}">
                    <i class="fa fa-server"></i>
                    <span>@lang('app.happyhours')</span>
                </a>
            </li>
            @endif
            @endpermission

            @if( auth()->user()->hasRole('admin')  && !Session::get('isCashier'))
            <li class="dropdown {{ Request::is('backend/bonus*') ? 'active' : '' }}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-gift"></i>
                    <span>게임제공사 보너스</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li class="{{ Request::is('backend/bonus/pp*') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.bonus.pp') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>프라그메틱 보너스</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/bonus/bng*') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.bonus.bng') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>부웅고 보너스</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="dropdown {{  Request::is('backend/in_out_request*')  || Request::is('backend/in_out_manage*')|| Request::is('backend/in_out_history*')? 'active' : '' }}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-database"></i>
                    <span>충환전관리<sup id="adj_newmark" style="background:blue;font-size:12px;display: none;">&nbsp;N&nbsp;</sup></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    @if(!auth()->user()->hasRole('admin'))
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/in_out_request') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.in_out_request') }}">
                            <i class="fa fa-circle-o"></i>
                            충환전신청
                        </a>
                    </li>
                    @endpermission
                    @endif
                    @if(auth()->user()->hasRole(['admin','comaster','master']))
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/in_out_manage/add') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.in_out_manage','add') }}">
                            <i class="fa fa-circle-o"></i>
                            충전관리<sup id="in_newmark" style="background:green;color:white;font-size:12px;display: none;">&nbsp;N&nbsp;</sup>
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/in_out_manage/out') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.in_out_manage','out') }}">
                            <i class="fa fa-circle-o"></i>
                            환전관리<sup id="out_newmark" style="background:red;font-size:12px;color:white;display: none;">&nbsp;N&nbsp;</sup>
                        </a>
                    </li>
                    @endpermission
                    @endif
                    <li class="{{ Request::is('backend/in_out_history') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.in_out_history') }}">
                            <i class="fa fa-circle-o"></i>
                            충환전내역
                        </a>
                    </li>
                </ul>
            </li>

            <li class="dropdown {{ Request::is('backend/adjustment_partner*') || Request::is('backend/adjustment_game*') 
                || Request::is('backend/adjustment_shift*') || Request::is('backend/adjustment*')? 'active' : '' }}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-database"></i>
                    <span>정산리스트<sup id="adj_newmark" style="background:blue;font-size:12px;display: none;">&nbsp;N&nbsp;</sup></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu" role="menu">
                 

                    @permission('stats.pay')
                    
                    <li class="{{ Request::is('backend/adjustment_partner') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.adjustment_partner') }}">
                            <i class="fa fa-circle-o"></i>
                                실시간정산
                        </a>
                    </li>
                    
                    @endpermission
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/adjustment_game') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.adjustment_game') }}">
                            <i class="fa fa-circle-o"></i>
                            게임별정산
                        </a>
                    </li>
                    @endpermission
                    
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/adjustment_daily') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.adjustment_daily') }}"> 
                            <i class="fa fa-circle-o"></i>
                            일별정산
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/adjustment_monthly') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.adjustment_monthly') }}"> 
                            <i class="fa fa-circle-o"></i>
                            월별정산
                        </a>
                    </li>
                    @endpermission

                </ul>
            </li>

            @permission('pincodes.manage')
            <li class="{{ Request::is('backend/pincodes*') ? 'active' : ''  }}">
                <a href="{{ route('backend.pincode.list') }}">
                    <i class="fa fa-server"></i>
                    <span>@lang('app.pincodes')</span>
                </a>
            </li>
            @endpermission


            @if (
                Auth::user()->hasPermission('stats.live') ||
                Auth::user()->hasPermission('stats.pay') ||
                Auth::user()->hasPermission('stats.game') ||
                Auth::user()->hasPermission('stats.bank') ||
                Auth::user()->hasPermission('stats.shop') ||
                Auth::user()->hasPermission('stats.shift')
            )

            <li class="dropdown {{ Request::is('backend/live*') || Request::is('backend/statistics*') || Request::is('backend/partner_statistics*') || Request::is('backend/stat_game*') || Request::is('backend/shop_stat') || Request::is('backend/shift_stat') || Request::is('backend/bank_stat') || Request::is('backend/deal_stat') ? 'active' : '' }}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-database"></i>
                    <span>통계내역</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu" role="menu">

                    @permission('stats.live')
                    <li class="{{ Request::is('backend/live') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.live_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.live_stats') --}}
                            실시간내역
                        </a>
                    </li>
                    @endpermission

                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/statistics*') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.statistics') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.statistics') --}}
                            회원수동지급내역
                        </a>
                    </li>
                    @endpermission
                    @if(!auth()->user()->hasRole('manager'))
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/partner_statistics*') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.statistics_partner') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.statistics') --}}
                            파트너충환전내역
                        </a>
                    </li>
                    @endpermission
                    @endif

                    @permission('stats.game')
                    <li class="{{ Request::is('backend/stat_game') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.game_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            게임배팅내역
                        </a>
                    </li>
                    @endpermission

                    @permission('games.manage')
                    @if (auth()->user()->hasRole('admin') && !Session::get('isCashier'))
                    <li class="{{ Request::is('backend/bank_stat') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.bank_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.bank_stats') --}}
                            게임뱅크충환전내역
                        </a>
                    </li>
                    @endif
                    @endpermission

                    @permission('stats.shop')
                    <li class="{{ Request::is('backend/shop_stat') ? 'active' : ''  }}">
                        <a href="{{ route('backend.shop_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.shop_stats') --}}
                            매장충환전내역
                        </a>
                    </li>
                    @endpermission

                    @permission('stats.shop')
                    @if (auth()->user()->hasRole(['comaster','master']))
                    @else
                    <li class="{{ Request::is('backend/deal_stat*') ? 'active' : ''  }}">
                        <a  href="{{ route('backend.deal_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            딜비적립내역
                        </a>
                    </li>
                    @endif
                    @endpermission

                    {{-- @permission('stats.shift')
                    <li class="{{ Request::is('backend/shift_stat') ? 'active' : ''  }}">
                        <a href="{{ route('backend.shift_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            @lang('app.shift_stats')
                        </a>
                    </li>
                    @endpermission --}}
                </ul>
            </li>

            @endif
            @if (auth()->user()->hasRole(['admin', 'master']))
            <li class="{{ Request::is('backend/settings/notice') ? 'active' : ''  }}">
                <a href="{{ route('backend.settings.notice') }}">
                    <i class="fa fa-bell"></i>
                    <span>공지사항</span>
                </a>
            </li>
            @endif

            @permission('users.activity')
            <li class="{{ Request::is('backend/activity*') ? 'active' : ''  }}">
                <a href="{{ route('backend.activity.index') }}">
                    <i class="fa fa-server"></i>
                    {{-- <span>@lang('app.activity_log')</span> --}}
                    <span>접속로그</span>
                </a>
            </li>
            @endpermission

            @permission('settings.general')
            @if (auth()->user()->hasRole('admin'))
            <li class="{{ Request::is('backend/settings') ? 'active' : ''  }}">
                <a href="{{ route('backend.settings.general') }}">
                    <i class="fa fa-circle-o"></i>
                    <span>@lang('app.settings')</span>
                </a>
            </li>
            @endif
            @endpermission
          </ul>
        </div>
        <!-- /.navbar-collapse -->
        @if (auth()->user()->hasRole(['admin','comaster']))
        @else
        <form action="{{ route('backend.user.update.address', auth()->user()->id) }}" method="post" class="navbar-form navbar-left">
            <div class="input-group">
                <span class="input-group-btn">
                    <button type="button"class="btn btn-flat" disabled style="cursor:default;">
                    <i class="fa fa-paper-plane"></i>
                    </button>
                </span>
                @if (auth()->user()->hasRole('master'))
                <input type="text" class="form-control" name="address" id="navbar-search-input" placeholder="연락처" value="{{auth()->user()->address}}" >
                <span class="input-group-btn">
                    <button type="submit" name="search"  class="btn btn-flat" style="background:rgba(255,255,255,0.2);">
                    <i class="fa fa-edit"></i>
                    </button>
                </span>
                @else
                <?php
                    $master = auth()->user()->referral;
                    while ($master!=null && !$master->hasRole('master'))
                    {
                        $master = $master->referral;
                    }
                ?>
                <input type="text" class="form-control" name="address" id="navbar-search-input" placeholder="Search" value="{{$master->address}}" style="color:#b8c7ce;cursor:default;" disabled>
                @endif
            </div>
        </form>
        @endif
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
          @if(auth()->user()->hasRole('admin'))
          @else
            <li class="dropdown messages-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span>보유금: 
                    @if( Auth::user()->hasRole(['cashier', 'manager']) )
                        @php
                            $shop = \VanguardLTE\Shop::find( auth()->user()->present()->shop_id );
                            echo $shop?number_format($shop->balance,0):0;
                        @endphp
                        원
                    @else
                        {{ number_format(auth()->user()->present()->balance,0) }}원
                    @endif</span>
              </a>
            </li>
            @if(auth()->user()->hasRole(['comaster','master']))
            @else
            <li class="dropdown messages-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span>수익금:
                    @if( Auth::user()->hasRole(['cashier', 'manager']) )
                        @php
                            $shop = \VanguardLTE\Shop::find( auth()->user()->present()->shop_id );
                            echo $shop?number_format($shop->deal_balance,2):0;
                        @endphp
                        원
                    @else
                        {{ number_format(auth()->user()->present()->deal_balance - auth()->user()->present()->mileage,2) }}
                        원
                    @endif
                </span>
              </a>
            </li>
            @endif
        @endif
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="/back/img/{{ auth()->user()->present()->role_id }}.png" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">{{auth()->user()->username}}[
                @foreach(['8'=>'app.admin','7'=>'app.comaster','6' => 'app.master','5' => 'app.agent', '4' => 'app.distributor', 'shop' => 'app.shop', '3' => 'app.manager', '2' => 'app.cashier'] AS $role_id=>$role_name)
                    @if($role_id == Auth::user()->role_id)
                        @lang($role_name)
                    @endif
                @endforeach
                ]</span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                <img src="/back/img/{{ auth()->user()->present()->role_id }}.png" class="img-circle">
                <p>
                {{ Auth::user()->username }}[
                @foreach(['8'=>'app.admin','7'=>'app.comaster','6' => 'app.master','5' => 'app.agent', '4' => 'app.distributor', 'shop' => 'app.shop', '3' => 'app.manager', '2' => 'app.cashier'] AS $role_id=>$role_name)
                    @if($role_id == Auth::user()->role_id)
                        @lang($role_name)
                    @endif
                @endforeach
                ]님 
                </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="{{ route('backend.user.edit', auth()->user()->present()->id) }}" class="btn btn-default btn-flat">@lang('app.my_profile')</a>
                  </div>
                  <div class="pull-right">
                    <a href="{{ route('backend.auth.logout') }}" class="btn btn-default btn-flat"> @lang('app.logout')</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>