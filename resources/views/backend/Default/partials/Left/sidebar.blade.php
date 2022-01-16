<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->

        <div class="user-panel">
            <div class="pull-left image">
                <img src="/back/img/{{ auth()->user()->present()->role_id }}.png" class="img-circle">
            </div>
            @if(auth()->user()->hasRole('admin'))
            @else
            <div class="pull-left info">
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

            @permission('dashboard')
            @if (auth()->user()->hasRole('admin'))
            <li class="{{ Request::is('backend') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.dashboard') }}">
                    <i class="fa fa-home"></i>
                    <span>@lang('app.dashboard')</span>
                </a>
            </li>
            @endif
            @endpermission

            @permission('users.tree')
            @if (auth()->user()->hasRole(['admin','comaster', 'master','agent']))
            <li class="{{ Request::is('backend/tree*') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.user.tree') }}">
                    <i class="fa fa-users"></i>
                    <span>파트너생성</span>
                </a>
            </li>
            @endif
            @endpermission
            @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster', 'master','agent', 'distributor']) )
            <li class="treeview {{ Request::is('backend/shops*') || Request::is('backend/partner*') || Request::is('backend/user*') || Request::is('backend/join*') || Request::is('backend/black*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>파트너관리 <sup id="user_newmark" style="background:blue;font-size:12px;display: none;">&nbsp;N&nbsp;</sup></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    <li class="{{ Request::is('backend/shops') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.shop.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>매장관리</span>
                        </a>
                    </li>
                    @if (auth()->user()->isInoutPartner())
                    <li class="{{ Request::is('backend/join') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.join') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>가입신청관리<sup id="join_newmark" style="background:green;color:white;font-size:12px;display: none;">&nbsp;N&nbsp;</sup></span>
                        </a>
                    </li>
                    @endif
                    <li class="{{ Request::is('backend/user*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','user')->first()->description}}관리</span>
                        </a>
                    </li>

                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster', 'master','agent']) )
                    <li class="{{ Request::is('backend/partner/4') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.partner', 4) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','distributor')->first()->description}}관리</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster', 'master']) )
                    <li class="{{ Request::is('backend/partner/5') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.partner', 5) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','agent')->first()->description}}관리</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin','comaster']) )
                    <li class="{{ Request::is('backend/partner/6') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.partner', 6) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','master')->first()->description}}관리</span>
                        </a>
                    </li>
                    @endif
                    @if ( auth()->check() && auth()->user()->hasRole(['admin']) )
                    <li class="{{ Request::is('backend/partner/7') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.user.partner', 7) }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{\VanguardLTE\Role::where('slug','comaster')->first()->description}}관리</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/black*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.black.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>블랙리스트</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @else
            <li class="{{ Request::is('backend/user*') ? 'active' : ''  }}">
                <a  href="{{ route($admurl.'.user.list') }}">
                    <i class="fa fa-circle-o"></i>
                    <span>회원관리</span>
                </a>
            </li>
            @endif
            @if ( auth()->check() && auth()->user()->hasRole(['admin']) )
            <li class="treeview {{  Request::is('backend/category*') || Request::is('backend/jpgame*') || Request::is('backend/game*') || Request::is('backend/bonusbank*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>게임관리</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    @permission('categories.manage')
                    <li class="{{ Request::is('backend/category') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.category.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>게임카테고리관리</span>
                        </a>
                    </li>              
                    @endpermission      
                    
                    @permission('jpgame.manage')
                    <li class="{{ Request::is('backend/jpgame*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.jpgame.list') }}">
                            <i class="fa  fa-circle-o"></i>
                            <span>잭팟관리</span>
                        </a>
                    </li>
                    @endpermission
                    @permission('games.manage')
                    <li class="{{ (Request::is('backend/game') || Request::is('backend/game/*')) ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.game.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>게임관리</span>
                        </a>
                    </li>
                    @endpermission

                    <li class="{{ (Request::is('backend/gamebank') || Request::is('backend/gamebank/*') || Request::is('backend/bonusbank*')) ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.game.bank') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>환수금관리</span>
                        </a>
                    </li>

                </ul>
            </li>
            @endif

            @permission('happyhours.manage')
            @if( auth()->user()->hasRole('admin') )
            <li class="{{ Request::is('backend/happyhours*') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.happyhour.list') }}">
                    <i class="fa fa-server"></i>
                    <span>@lang('app.happyhours')</span>
                </a>
            </li>
            @endif
            @endpermission

            @if( auth()->user()->hasRole('admin') )
            <li class="treeview {{ Request::is('backend/bonus*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-gift"></i>
                    <span>게임제공사 보너스</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    <li class="{{ Request::is('backend/bonus/pp*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.bonus.pp') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>프라그메틱 보너스</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/bonus/bng*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.bonus.bng') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>부웅고 보너스</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif


            <li class="treeview {{ Request::is('backend/in_out_request*')  || Request::is('backend/in_out_manage*') || Request::is('backend/in_out_history') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-database"></i>
                    <span>충환전관리<sup id="adj_newmark" style="background:blue;font-size:12px;display: none;">&nbsp;N&nbsp;</sup></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    @if(auth()->user()->isInoutPartner())
                    @else
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/in_out_request') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.in_out_request') }}">
                            <i class="fa fa-circle-o"></i>
                            충환전신청
                        </a>
                    </li>
                    @endpermission
                    @endif
                    @if(auth()->user()->isInoutPartner() )
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/in_out_manage/add') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.in_out_manage','add') }}">
                            <i class="fa fa-circle-o"></i>
                            충전관리<sup id="in_newmark" style="background:green;color:white;font-size:12px;display: none;">&nbsp;N&nbsp;</sup>
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/in_out_manage/out') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.in_out_manage','out') }}">
                            <i class="fa fa-circle-o"></i>
                            환전관리<sup id="out_newmark" style="background:red;font-size:12px;color:white;display: none;">&nbsp;N&nbsp;</sup>
                        </a>
                    </li>
                    @endpermission
                    @endif
                    <li class="{{ Request::is('backend/in_out_history') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.in_out_history') }}">
                            <i class="fa fa-circle-o"></i>
                            충환전내역
                        </a>
                    </li>
                    
                </ul>
            </li>

            <li class="treeview {{ Request::is('backend/adjustment_partner*') || Request::is('backend/adjustment_game*') 
                || Request::is('backend/adjustment_ggr*') || Request::is('backend/adjustment*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-database"></i>
                    <span>정산관리</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">

                    

                    @permission('stats.pay')
                    
                    <li class="{{ Request::is('backend/adjustment_partner') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_partner') }}">
                            <i class="fa fa-circle-o"></i>
                                실시간정산
                        </a>
                    </li>

                    @if (auth()->user()->isInoutPartner())
                    <li class="{{ Request::is('backend/adjustment_ggr') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_ggr') }}">
                            <i class="fa fa-circle-o"></i>
                                죽장정산
                        </a>
                    </li>
                    @endif
                    
                    @endpermission
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/adjustment_game') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_game') }}">
                            <i class="fa fa-circle-o"></i>
                            게임별정산
                        </a>
                    </li>
                    @endpermission
                    
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/adjustment_daily') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_daily') }}"> 
                            <i class="fa fa-circle-o"></i>
                            일별정산
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/adjustment_monthly') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.adjustment_monthly') }}"> 
                            <i class="fa fa-circle-o"></i>
                            월별정산
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

            <li class="treeview {{ Request::is('backend/live*') || Request::is('backend/statistics*') || Request::is('backend/partner_statistics*') || Request::is('backend/stat_game*') || Request::is('backend/shop_stat') || Request::is('backend/shift_stat') || Request::is('backend/bank_stat') || Request::is('backend/deal_stat') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-database"></i>
                    {{-- <span>Stats</span> --}}
                    <span>통계내역</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">


                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/statistics*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.statistics') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.statistics') --}}
                            회원수동지급내역
                        </a>
                    </li>
                    @endpermission
                    @if(!auth()->user()->hasRole('manager'))
                    @permission('stats.pay')
                    <li class="{{ Request::is('backend/partner_statistics*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.statistics_partner') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.statistics') --}}
                            파트너충환전내역
                        </a>
                    </li>
                    @endpermission
                    @endif

                    @permission('stats.game')
                    <li class="{{ Request::is('backend/stat_game') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.game_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            게임배팅내역
                        </a>
                    </li>
                    @endpermission

                    @permission('games.manage')
                    @if (auth()->user()->hasRole('admin'))
                    <li class="{{ Request::is('backend/bank_stat') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.bank_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.bank_stats') --}}
                            게임뱅크충환전내역
                        </a>
                    </li>
                    @endif
                    @endpermission

                    @permission('stats.shop')
                    <li class="{{ Request::is('backend/shop_stat') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.shop_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            {{-- @lang('app.shop_stats') --}}
                            매장충환전내역
                        </a>
                    </li>
                    @endpermission

                    @permission('stats.shop')
                    <li class="{{ Request::is('backend/deal_stat*') ? 'active' : ''  }}">
                        <a  href="{{ route($admurl.'.deal_stat') }}">
                            <i class="fa fa-circle-o"></i>
                            딜비적립내역
                        </a>
                    </li>
                    @endpermission

                </ul>
            </li>

            @endif
            @if (auth()->user()->isInoutPartner())
            <li class="treeview {{ Request::is('backend/notices*') || Request::is('backend/messages*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-headphones fa-lg"></i>
                    <span>고객센터</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class=" treeview-menu" id="stats-dropdown">
                    <li class="{{ Request::is('backend/notices*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.notice.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>공지관리</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('backend/messages*') ? 'active' : ''  }}">
                        <a href="{{ route($admurl.'.msg.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>쪽지관리</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if (auth()->user()->hasRole('admin'))
            <li class="{{ Request::is('backend/websites*') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.website.list') }}">
                    <i class="fa fa-chrome"></i>
                    <span>도메인관리</span>
                </a>
            </li>
            @endif

            @if (auth()->user()->isInoutPartner())
            <li class="{{ Request::is('backend/activity*') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.activity.index') }}">
                    <i class="fa fa-server"></i>
                    {{-- <span>@lang('app.activity_log')</span> --}}
                    <span>접속로그</span>
                </a>
            </li>
            @endif
            
            {{--
            @permission('permissions.manage')
            @if (auth()->user()->hasRole('admin') )
            <li  class="{{ Request::is('backend/permission*') ? 'active' : '' }}">
                <a href="{{ route($admurl.'.permission.index') }}">
                    <i class="fa fa-circle-o"></i>
                    <span>@lang('app.permissions')</span>
                </a>
            </li>
            @endif
            @endpermission
            --}}
            {{-- @permission('settings.generator')
            <li class="{{ Request::is('backend/generator*') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.settings.generator') }}">
                    <i class="fa fa-server"></i>
                    <span>@lang('app.api_generator')</span>
                </a>
            </li>
            @endpermission

            @permission('api.manage')
            <li class="{{ Request::is('backend/api*') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.api.list') }}">
                    <i class="fa fa-circle-o"></i>
                    <span>@lang('app.api_keys')</span>
                </a>
            </li>
            @endpermission --}}

            @permission('settings.general')
            @if (auth()->user()->hasRole('admin') )
            <li class="{{ Request::is('backend/settings') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.settings.general') }}">
                    <i class="fa fa-circle-o"></i>
                    <span>@lang('app.settings')</span>
                </a>
            </li>
            @endif
            @endpermission

            {{-- @permission('helpers.manage')
            <li class="{{ Request::is('backend/info*') ? 'active' : ''  }}">
                <a href="{{ route($admurl.'.info.list') }}">
                    <i class="fa fa-circle-o"></i>
                    <span>@lang('app.info')</span>
                </a>
            </li>
            @endpermission --}}

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

