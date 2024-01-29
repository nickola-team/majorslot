
<div class="col-md-3">

    <div class="box box-primary">
        <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle" src="/back/img/{{ $user->present()->role_id }}.png" alt="{{ $user->present()->username }}">
            <h4 class="profile-username text-center"><small><i class="fa fa-circle text-{{ $user->present()->labelClass }}"></i></small> {{ $user->present()->username }}</h4>
            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b>@lang('app.balance')</b> <a class="pull-right">{{ number_format($user->present()->balance,0) }}</a>
                </li>

                @if( $user->hasRole('user') )
                <li class="list-group-item">
                    <b>@lang('app.in')</b> <a class="pull-right">{{ number_format($user->present()->total_in,0) }}</a>
                </li>
                <li class="list-group-item">
                    <b>@lang('app.out')</b> <a class="pull-right">{{ number_format($user->present()->total_out,0) }}</a>
                </li>
                <li class="list-group-item">
                    <b>@lang('app.total')</b> <a class="pull-right">{{ number_format($user->present()->total_in - $user->present()->total_out,0) }}</a>
                </li>
                @endif

            </ul>

            @if( $user->id != Auth::id() )
                @permission('users.delete')
                <a href="{{ route($admurl.'.user.delete', $user->id) }}"
                   class="btn btn-danger btn-block"
                   data-method="DELETE"
                   data-confirm-title="회원 삭제"
                   data-confirm-text="정말 삭제하시겠습니까?"
                   data-confirm-delete="확인">
                    <b>@lang('app.delete')</b></a>
                @endpermission

                @if(auth()->user()->hasRole('admin') )
                    <a href="{{ route($admurl.'.user.hard_delete', $user->id) }}"
                        class="btn btn-danger btn-block"
                        data-method="DELETE"
                        data-confirm-title="어드민 삭제"
                        data-confirm-text="정말 삭제하시겠습니까?"
                        data-confirm-delete="확인">
                        <b>어드민삭제</b></a>
                @endif

            @endif
        </div>
    </div>


</div>