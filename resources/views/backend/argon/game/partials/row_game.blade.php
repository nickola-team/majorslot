@foreach ($games as $game)
    @if ($loop->index > 0)
	<tr>
	@endif
    <td>{{$game->game->name}}</td>
    @php
        $shops = $user->shops(true);
        $disableCount = \VanguardLTE\Game::where(['view'=>0, 'original_id' => $game->game->original_id])->whereIn('shop_id', $shops)->count();
        $activeCount = \VanguardLTE\Game::where(['view'=>1, 'original_id' => $game->game->original_id])->whereIn('shop_id', $shops)->count();
    @endphp
    <td><span class="text-green">{{$activeCount}}</span></td>
    <td><span class="text-red">{{$disableCount}}</span></td>
    <td>
        @if ($game->game->rezerv>0)
            <span class="text-green">적용중</span>
            <a  class="btn btn-warning btn-sm" href="{{argon_route('argon.game.game.status', ['user_id'=>$user->id, 'game_id'=>$game->game->original_id, 'value'=>0,'field'=>'rezerv'])}}">금지</a>
        @else
            <span class="text-red">금지됨</span>
            <a class="btn btn-success  btn-sm" href="{{argon_route('argon.game.game.status', ['user_id'=>$user->id, 'game_id'=>$game->game->original_id, 'value'=>1,'field'=>'rezerv'])}}">적용</a>
        @endif
    </td>
    <td class="text-right">
        <a class="btn btn-success  btn-sm" href="{{argon_route('argon.game.game.status', ['user_id'=>$user->id, 'game_id'=>$game->game->original_id, 'value'=>1,'field'=>'view'])}}">활성</a>
        <a  class="btn btn-warning btn-sm" href="{{argon_route('argon.game.game.status', ['user_id'=>$user->id, 'game_id'=>$game->game->original_id, 'value'=>0,'field'=>'view'])}}">비활성</a>
    </td>
    @if ($loop->index > 0)
	</tr>
	@endif
@endforeach
