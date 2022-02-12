@if (count($users) > 0)
    @foreach ($users as $user)
        <tr data-tt-id="{{$user->id}}" data-tt-parent-id="{{$user->parent_id}}" data-tt-branch="{{$user->role_id>3?'true':'false'}}">
            @include('backend.argon.agent.partials.row')
        </tr>
    @endforeach
@endif