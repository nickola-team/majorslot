@foreach ($categories as $category)
    @if ($loop->index > 0)
	<tr>
	@endif
    <td>
        {{$category->title}}
    <td>{{$category->position}}</td>
    @if (auth()->user()->hasRole('admin'))
    <td>{{$category->provider??'자체버전'}}</td>
    @endif
    @if (auth()->user()->hasRole('admin'))
    <td>
        @if ($category->view == 1)
        <span class="text-green">사용</span>
        @else
        <span class="text-red">내림</span>
        @endif
    </td>
    @else
    <td>
        @if (isset(auth()->user()->sessiondata()['gameOn']) && auth()->user()->sessiondata()['gameOn'] == 1)
        @if ($category->title =='Pragmatic Play')
            @if ($category->provider != null)
                공식사
                <a class="btn btn-success  btn-sm" href="{{argon_route('argon.game.domain.provider', ['cat_id'=>$category->id, 'provider'=>0])}}">대행사이용</a>
            @else
                대행사
                <a class="btn btn-warning  btn-sm" href="{{argon_route('argon.game.domain.provider', ['cat_id'=>$category->id, 'provider'=>1])}}">공식사이용</a>
            @endif
        @else
            공식사
        @endif
        @else
        공식사
        @endif 
    </td>
    @endif
    <td>
        @if ($category->status == 1)
        <span class="text-green">운영</span>
        @else
        <span class="text-red">점검</span>
        @endif
    </td>
    <td class="text-right">
        @if (auth()->user()->hasRole('admin'))
        <a class="btn btn-success  btn-sm" href="{{argon_route('argon.game.domain.status', ['cat_id'=>$category->id, 'view'=>1])}}">사용</a>
        <a  class="btn btn-warning btn-sm" href="{{argon_route('argon.game.domain.status', ['cat_id'=>$category->id, 'view'=>0])}}">내림</a>
        @endif
        <a class="btn btn-success  btn-sm" href="{{argon_route('argon.game.domain.status', ['cat_id'=>$category->id, 'status'=>1])}}">운영</a>
        <a  class="btn btn-warning btn-sm" href="{{argon_route('argon.game.domain.status', ['cat_id'=>$category->id, 'status'=>0])}}">점검</a>
    </td>
    @if ($loop->index > 0)
	</tr>
	@endif
@endforeach
