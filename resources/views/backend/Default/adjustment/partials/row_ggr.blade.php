<tr>
	<td><a href="{{ route($admurl.'.adjustment_ggr', ['parent'=>$adjustment['user_id']]) }}">{{$adjustment['username']}} [ {{$available_roles_trans[$adjustment['role_id']]}} ]</a></td>
	<td>{{$adjustment['last_reset_at']}}</td>
	<td>{{$adjustment['next_reset_at']}}</td> 
	<td>{{$adjustment['reset_days']}}일</td> 
	<td>{{number_format($adjustment['total_bet'])}}</td> 
	<td>{{number_format($adjustment['total_win'])}}</td> 
	<td>{{number_format($adjustment['total_bet'] - $adjustment['total_win'])}}</td> 
	<td>{{$adjustment['ggr_percent']}}</td> 
	<td>{{$adjustment['total_ggr']>0?number_format($adjustment['total_ggr']) : '0'}}</td> 
	<td>{{$adjustment['deal_percent']}}</td> 
	<td>{{number_format($adjustment['total_deal'])}}</td> 
	<td>{{number_format($adjustment['total_sum'])}}</td> 
	<td>
		<a href="{{route($admurl.'.process_ggr', ['id'=>$adjustment['user_id']])}}">
			<button type="button" class="btn btn-block btn-primary btn-xs" {{strtotime($adjustment['next_reset_at']) > strtotime("now")?'disabled':''}}>정산</button>
		</a>
	</td>
	<td>
	<a href="{{route($admurl.'.reset_ggr', ['id'=>$adjustment['user_id']])}}">
			<button type="button" class="btn btn-block btn-danger btn-xs">리셋</button>
		</a>
	</td>
</tr>