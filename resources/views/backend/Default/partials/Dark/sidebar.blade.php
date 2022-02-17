<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="padding:0px 5px;">
        <!-- Sidebar user panel -->

        <div class="user-panel" style="display: grid;">
            @if(auth()->user()->hasRole('admin'))
            @else
            <div class="pull-left info" style="left:20px;">
			<p>보유금:
                    @if( Auth::user()->hasRole(['cashier', 'manager']) )
                        @php
                            $shop = \VanguardLTE\Shop::find( auth()->user()->present()->shop_id );
                            echo $shop?number_format($shop->balance,0):0;
                        @endphp
                        원
                    @else
                        {{ number_format(auth()->user()->present()->balance,0) }}원
                    @endif
			</p>
            </div>
            @endif
        </div>
        <!-- search form -->
        @if (auth()->user()->hasRole(['admin']))
        @else
        <form action="{{ route($admurl.'.user.update.address', auth()->user()->id) }}" method="post" class="sidebar-form">
            <div class="input-group">
                <span class="input-group-btn">
                    <button type="button"class="btn btn-flat" disabled style="cursor:default;">
                    <i class="fa fa-paper-plane"></i>
                    </button>
                </span>
                @if (auth()->user()->isInoutPartner())
                <input type="text" name="address" class="form-control" value="{{auth()->user()->address}}" style="color:#b8c7ce;" placeholder="연락처">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                    <i class="fa fa-edit"></i>
                    </button>
                </span>
                @else
                <?php
                    $master = auth()->user()->referral;
                    while ($master!=null && !$master->isInoutPartner())
                    {
                        $master = $master->referral;
                    }
                ?>
                <input type="text" name="q" class="form-control" value="{{$master->address}}" style="color:#b8c7ce;cursor:default;" disabled>
                @endif
            </div>
        </form>
        @endif

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
                <?php  
					$available_roles = Auth::user()->available_roles( true );
					$available_roles_trans = [];
					foreach ($available_roles as $key=>$role)
					{
						$role = \VanguardLTE\Role::find($key)->description;
						$available_roles_trans[$key] = $role;
					}
				?>
            <li class="header" style="text-align: center; color:#b8c7ce;"><span style="color:red;">{{ Auth::user()->username }}</span>[ {{$available_roles_trans[auth()->user()->role_id]}} ]님 안녕하세요 </li> 

            <li class="{{ Request::is('slot') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.dashboard') }}">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>

            @permission('users.tree')
            @if (auth()->user()->hasRole(['admin','comaster', 'master','agent']))
            <li class="{{ Request::is('slot/tree*') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.user.tree') }}">
                    <i class="fa fa-users"></i>
                    <span>파트너추가</span>
                </a>
            </li>
            @endif
            @endpermission
            @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster', 'master','agent', 'distributor']) )
            <li class="treeview {{ Request::is('slot/shops*') || Request::is('slot/partner/*') || Request::is('slot/user*') || Request::is('slot/join*') || Request::is('slot/black*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>파트너목록 <sup id="user_newmark" style="background:blue;font-size:12px;display: none;">&nbsp;N&nbsp;</sup></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    @if (auth()->user()->isInoutPartner())
                    <li class="{{ Request::is('slot/join') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.join') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>VIP회원<sup id="join_newmark" style="background:green;color:white;font-size:12px;display: none;">&nbsp;N&nbsp;</sup></span>
                        </a>
                    </li>
                    @endif
                    
                    
                    <li class="{{ Request::is('slot/user*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','user')->first()->description}}</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('slot/shops') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.shop.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>매장</span>
                        </a>
                    </li>
                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster', 'master','agent']) )
                    <li class="{{ Request::is('slot/partner/4') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.partner', 4) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','distributor')->first()->description}}</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster', 'master']) )
                    <li class="{{ Request::is('slot/partner/5') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.partner', 5) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','agent')->first()->description}}</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster']) )
                    <li class="{{ Request::is('slot/partner/6') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.partner', 6) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','master')->first()->description}}</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin']) )
                    <li class="{{ Request::is('slot/partner/7') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.partner', 7) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','comaster')->first()->description}}</span>
                        </a>
                    </li>

                    <li class="{{ Request::is('slot/black*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.black.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>블랙리스트</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @else
            <li class="{{ Request::is('slot/user*') ? 'active' : ''  }}">
                <a  href="{{ route($admurl.'.user.list') }}">
                    <i class="fa fa-lg fa-user-circle-o"></i>
                    <span>회원</span>
                </a>
            </li>
            @endif
            @if ( auth()->check() && auth()->user()->hasRole('admin') )
            <li class="treeview {{  Request::is('slot/category*') || Request::is('slot/jpgame*') || Request::is('slot/game*') || Request::is('slot/happyhours*') || Request::is('slot/bonusbank*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>게임관리</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    @permission('categories.manage')
                    <li class="{{ Request::is('slot/category') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.category.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>게임제공사관리</span>
                        </a>
                    </li>              
                    @endpermission      
                    @permission('jpgame.manage')
                    <li class="{{ Request::is('slot/jpgame*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.jpgame.list') }}">
                            <i class="fa  fa-circle-o"></i>
                            <span>잭팟관리</span>
                        </a>
                    </li>
                    @endpermission
                    @permission('games.manage')
                    <li class="{{ (Request::is('slot/game') || Request::is('slot/game/*')) ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.game.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>게임관리</span>
                        </a>
                    </li>
                    @endpermission

                    <li class="{{ (Request::is('slot/gamebank') || Request::is('slot/gamebank/*') || Request::is('slot/bonusbank*')) ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.game.bank') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>환수금관리</span>
                        </a>
                    </li>

                    @permission('happyhours.manage')
                    @if( auth()->user()->hasRole('admin') )
                    <li class="{{ Request::is('slot/happyhours*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.happyhour.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>@lang('app.happyhours')</span>
                        </a>
                    </li>
                    @endif
                    @endpermission

                </ul>
            </li>
            @endif


            <li class="treeview {{ Request::is('slot/in_out_request*')  || Request::is('slot/in_out_manage*') || Request::is('slot/in_out_history') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-btc fa-lg"></i>
                    <span>충환전<sup id="adj_newmark" style="background:blue;font-size:12px;display: none;">&nbsp;N&nbsp;</sup></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    @if(auth()->user()->isInoutPartner())
                    @else
                    @permission('stats.pay')
                    <li class="{{ Request::is('slot/in_out_request') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.in_out_request') }}">
                            <i class="fa fa-circle-o"></i>
                            신청
                        </a>
                    </li>
                    @endpermission
                    @endif
                    @if(auth()->user()->isInoutPartner() )
                    @permission('stats.pay')
                    <li class="{{ Request::is('slot/in_out_manage/add') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.in_out_manage','add') }}">
                            <i class="fa fa-circle-o"></i>
                            충전<sup id="in_newmark" style="background:green;color:white;font-size:12px;display: none;">&nbsp;N&nbsp;</sup>
                        </a>
                    </li>
                    <li class="{{ Request::is('slot/in_out_manage/out') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.in_out_manage','out') }}">
                            <i class="fa fa-circle-o"></i>
                            환전<sup id="out_newmark" style="background:red;font-size:12px;color:white;display: none;">&nbsp;N&nbsp;</sup>
                        </a>
                    </li>
                    @endpermission
                    @endif
                    <li class="{{ Request::is('slot/in_out_history') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.in_out_history') }}">
                            <i class="fa fa-circle-o"></i>
                            내역
                        </a>
                    </li>
                    
                </ul>
            </li>

            <li class="treeview {{ Request::is('slot/adjustment_partner*') || Request::is('slot/adjustment_game*') 
                || Request::is('slot/adjustment_ggr*') || Request::is('slot/adjustment*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-calendar fa-lg"></i>
                    <span>정산</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    @permission('stats.pay')
                    
                    <li class="{{ Request::is('slot/adjustment_partner') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_partner') }}">
                            <i class="fa fa-circle-o"></i>
                                오늘
                        </a>
                    </li>
                    @if (auth()->user()->isInoutPartner())
                    <li class="{{ Request::is('slot/adjustment_ggr') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_ggr') }}">
                            <i class="fa fa-circle-o"></i>
                                죽장
                        </a>
                    </li>
                    @endif
                    
                    @endpermission
                    @permission('stats.pay')
                    <li class="{{ Request::is('slot/adjustment_game') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_game') }}">
                            <i class="fa fa-circle-o"></i>
                            게임별
                        </a>
                    </li>
                    @endpermission
                    
                    @permission('stats.pay')
                    <li class="{{ Request::is('slot/adjustment_daily') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_daily') }}"> 
                            <i class="fa fa-circle-o"></i>
                            일별
                        </a>
                    </li>
                    <li class="{{ Request::is('slot/adjustment_monthly') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_monthly') }}"> 
                            <i class="fa fa-circle-o"></i>
                            월별
                        </a>
                    </li>
                    @endpermission
                </ul>
            </li>


            @if (
                Auth::user()->hasPermission('stats.live') ||
                Auth::user()->hasPermission('stats.pay') ||
                Auth::user()->hasPermission('stats.game') ||
                Auth::user()->hasPermission('stats.bank') ||
                Auth::user()->hasPermission('stats.shop') ||
                Auth::user()->hasPermission('stats.shift')
            )

            <li class="treeview {{ Request::is('slot/statistics*') || Request::is('slot/partner_statistics*') ||Request::is('slot/shop_stat') ||  Request::is('slot/bank_stat') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-database"></i>
                    <span>머니이동내역</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    @permission('stats.pay')
                    <li class="{{ Request::is('slot/statistics*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.statistics') }}">
                            <i class="fa fa-circle-o"></i>
                            회원
                        </a>
                    </li>
                    @permission('stats.shop')
                    <li class="{{ Request::is('slot/shop_stat') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.shop_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.shop_stats') --}}
                            매장
                        </a>
                    </li>
                    @endpermission
                    @endpermission
                    @if(!auth()->user()->hasRole('manager'))
                    @permission('stats.pay')
                    <li class="{{ Request::is('slot/partner_statistics*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.statistics_partner') }}">
                            <i class="fa fa-circle-o"></i>
                            파트너
                        </a>
                    </li>
                    @endpermission
                    @endif
                </ul>
            </li>

            <li class="treeview {{ Request::is('slot/stat_game*') ||  Request::is('slot/bank_stat') || Request::is('slot/deal_stat') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-database"></i>
                    <span>게임내역</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">

                    @permission('stats.game')
                    <li class="{{ Request::is('slot/stat_game') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.game_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            게임배팅
                        </a>
                    </li>
                    @endpermission

                    @permission('games.manage')
                    @if (auth()->user()->hasRole('admin'))
                    <li class="{{ Request::is('slot/bank_stat') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.bank_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.bank_stats') --}}
                            게임뱅크
                        </a>
                    </li>
                    @endif
                    @endpermission

                    @permission('stats.shop')
                    <li class="{{ Request::is('slot/deal_stat*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.deal_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            딜비적립
                        </a>
                    </li>
                    @endpermission

                </ul>
            </li>
            @endif
            @if (auth()->user()->hasRole('admin'))
            <li class="treeview {{ Request::is('slot/websites*') || Request::is('slot/settings*') 
                || Request::is('slot/activity*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-calendar fa-lg"></i>
                    <span>시스템설정</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    
                <li class="{{ Request::is('slot/websites*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.website.list') }}">
                            <i class="fa fa-circle-o"></i>
                                도메인관리
                        </a>
                    </li>
                    
                    <li class="{{ Request::is('slot/settings') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.settings.general') }}">
                            <i class="fa fa-circle-o"></i>
                            페이지설정
                        </a>
                    </li>
                    
                    <li class="{{ Request::is('slot/activity*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.activity.index') }}">
                            <i class="fa fa-circle-o"></i>
                            접속로그
                        </a>
                    </li>
                    
                </ul>
            </li>
            @endif
            @if (auth()->user()->isInoutPartner())
            <li class="treeview {{ Request::is('slot/notices*') || Request::is('slot/messages*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-headphones fa-lg"></i>
                    <span>고객센터</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    <li class="{{ Request::is('slot/notices*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.notice.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>공지</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('slot/messages*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.msg.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>쪽지함</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </section>
</aside>

<div class="modal fade" id="openChangeModal"  role="dialog" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<form action="{{ route($admurl.'.profile.setshop') }}" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('app.shops')</h4>
                </div>
				<div class="modal-body">
					<div class="form-group">
						{!! Form::select('shop_id',
                            (Auth::user()->hasRole(['admin','comaster','master','agent']) ? [0 => __('app.no_shop')] : [])
                            +
                            Auth::user()->shops_array(), Auth::user()->shop_id, ['class' => 'form-control select2', 'style' => 'width: 100%;']) !!}
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
					<button type="submit" class="btn btn-primary">@lang('app.change')</button>
				</div>
			</form>
        </div>
    </div>
</div>

