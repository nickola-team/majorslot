@if (count($users) > 0)
    @foreach ($users as $user)
        <tr data-tt-id="{{$user->id}}" data-tt-parent-id="{{$user->parent_id}}" data-tt-branch="{{($user->role_id>3 && $user->status == \VanguardLTE\Support\Enum\UserStatus::ACTIVE)?'true':'false'}}">
            @include('backend.argon.agent.partials.row')
        </tr>
    @endforeach
@else
    <tr  data-tt-id="-10" data-tt-parent-id="{{isset($child_id)?$child_id:0}}" >
        <td colspan='9'>No Data</td>
    </tr>
@endif