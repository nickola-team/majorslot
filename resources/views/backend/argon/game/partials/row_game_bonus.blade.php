<td>{{$bank->id}}</td>
<td>
	@if ($bank->game_id == 0)
		일반
	@else
		{{ $bank->game->title }}
	@endif
</td>
<td>{{ number_format($bank->bank_01,0) }} <a href="{{argon_route('argon.game.bankbalance', ['batch'=>0,'type'=>'bonus','id'=>$bank->id])}}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
<td>{{ number_format($bank->bank_02,0) }} <a href="{{argon_route('argon.game.bankbalance', ['batch'=>0,'type'=>'bonus','id'=>$bank->id])}}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
<td>{{ number_format($bank->bank_03,0) }} <a href="{{argon_route('argon.game.bankbalance', ['batch'=>0,'type'=>'bonus','id'=>$bank->id])}}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
<td>{{ number_format($bank->bank_04,0) }} <a href="{{argon_route('argon.game.bankbalance', ['batch'=>0,'type'=>'bonus','id'=>$bank->id])}}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
<td>{{ number_format($bank->bank_05,0) }} <a href="{{argon_route('argon.game.bankbalance', ['batch'=>0,'type'=>'bonus','id'=>$bank->id])}}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
<td>
    @if ($bank->max_bank == 0)
        없음
    @else
    {{ number_format($bank->max_bank,0) }} 
    @endif
</td>