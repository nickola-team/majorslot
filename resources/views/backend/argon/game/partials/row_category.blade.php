@foreach ($categories as $category)
    @if ($loop->index > 0)
	<tr>
	@endif
    <td>
        @if ($category->provider != '')
        {{$category->title}}
        @else
        <a href="{{argon_route('argon.game.game', ['user_id'=>$user->id, 'cat_id'=>$category->original_id])}}">{{$category->title}}</a></td>
        @endif
    <td>{{$category->position}}</td>
    <td>
        @if ($category->provider != '')
        @php
            $games = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($category->provider) . 'Controller::getgamelist', $category->href);
        @endphp
            {{$games!=null?count($games):0}}
        @else
        {{$category->games->count()}}
        @endif
    </td>
    <td>{{$category->provider??'자체버전'}}</td>
    @php
        $shops = $user->shops(true);
        $disableCount = \VanguardLTE\Category::where(['view'=>0, 'original_id' => $category->original_id])->whereIn('shop_id', $shops)->count();
        $activeCount = \VanguardLTE\Category::where(['view'=>1, 'original_id' => $category->original_id])->whereIn('shop_id', $shops)->count();
    @endphp
    <td><span class="text-green">{{$activeCount}}</span></td>
    <td><span class="text-red">{{$disableCount}}</span></td>
    <td class="text-right">
        <a class="btn btn-success  btn-sm" href="{{argon_route('argon.game.category.status', ['user_id'=>$user->id, 'cat_id'=>$category->original_id, 'status'=>1])}}">활성</a>
        <a  class="btn btn-warning btn-sm" href="{{argon_route('argon.game.category.status', ['user_id'=>$user->id, 'cat_id'=>$category->original_id, 'status'=>0])}}">비활성</a>
    </td>
    @if ($loop->index > 0)
	</tr>
	@endif
@endforeach
