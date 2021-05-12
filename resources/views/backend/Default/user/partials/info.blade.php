
<div class="col-md-3">

    <div class="box box-primary">
        <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle" src="/back/img/{{ $user->present()->role_id }}.png" alt="{{ $user->present()->username }}">
            <h4 class="profile-username text-center"><small><i class="fa fa-circle text-{{ $user->present()->labelClass }}"></i></small> {{ $user->present()->username }}</h4>
            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b>@lang('app.balance')</b> <a class="pull-right">{{ number_format($user->present()->balance,2) }}</a>
                </li>

                @if( $user->hasRole('user') )
                <li class="list-group-item">
                    <b>@lang('app.in')</b> <a class="pull-right">{{ number_format($user->present()->total_in,2) }}</a>
                </li>
                <li class="list-group-item">
                    <b>@lang('app.out')</b> <a class="pull-right">{{ number_format($user->present()->total_out,2) }}</a>
                </li>
                <li class="list-group-item">
                    <b>@lang('app.total')</b> <a class="pull-right">{{ number_format($user->present()->total_in - $user->present()->total_out,2) }}</a>
                </li>
                @endif

            </ul>

            @if( $user->id != Auth::id() )
                @if(auth()->user()->hasRole(['admin','master']) || 
                     (auth()->user()->hasRole('master') && $user->hasRole('agent'))
                    || (auth()->user()->hasRole('agent') && $user->hasRole('distributor'))
                    || (auth()->user()->hasRole('distributor') && $user->hasRole('manager'))
                    || (auth()->user()->hasRole('manager') && $user->hasRole('user'))
                )
                @permission('users.delete')
                <a href="{{ route('backend.user.delete', $user->id) }}"
                   class="btn btn-danger btn-block"
                   data-method="DELETE"
                @if ($user->hasRole('user'))
                   data-confirm-title="회원 삭제"
                @else
                    data-confirm-title="파트너 삭제"
                @endif
                   data-confirm-text="정말 삭제하시겠습니까?"
                   data-confirm-delete="확인">
                    <b>@lang('app.delete')</b></a>
                @endpermission
                @endif

                @if(auth()->user()->hasRole('admin') && $user->hasRole(['master', 'agent', 'distributor']) )
                    <a href="{{ route('backend.user.hard_delete', $user->id) }}"
                        class="btn btn-danger btn-block"
                        data-method="DELETE"
                        data-confirm-title="파트너 삭제"
                        data-confirm-text="정말 삭제하시겠습니까?"
                        data-confirm-delete="확인">
                        <b>파트너삭제</b></a>
                @endif

            @endif
        </div>
    </div>


</div>