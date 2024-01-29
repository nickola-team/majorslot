<tr>        
    <td>
	<a href="{{ route($admurl.'.shop.edit', $shop->id) }}">{{ $shop->name }}</a>
	</td>
	<?php
        $parent = $shop->getUsersByRole('manager')->first();
        for ($r=3;$r<auth()->user()->role_id;$r++)
        {
			if ($parent){
				echo '<td><a href="'.route($admurl.'.user.edit', $parent->id).'">'.$parent->username.'</a></td>';
				$parent = $parent->referral;
			}
			else
			{
				echo '<td></td>';
			}
        }
	?>
	
	{{-- <td><a href="{{ route('frontend.jpstv', $shop->shop_id) }}" target="_blank">{{ $shop->shop_id }}</a></td> --}}
    <td>{{ number_format($shop->balance,0) }}</td>
	@if(auth()->user()->hasRole('admin'))
	<td>{{ $shop->percent }}</td>
	@endif
	<td>{{ number_format($shop->deal_balance - $shop->mileage,0) }}</td>
	@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
	<td>{{ number_format($shop->ggr_balance - $shop->ggr_mileage - ($shop->count_deal_balance - $shop->count_mileage),0) }}</td>
	@endif
	<td>{{ $shop->deal_percent }}</td>
	<td>{{ $shop->table_deal_percent }}</td>
	@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
	<td>{{ $shop->ggr_percent }}</td>
	<td>{{ $shop->reset_days??0 }}일</td>
	<td>
		@if ($shop->ggr_percent > 0)
			{{$shop->last_reset_at?\Carbon\Carbon::parse($shop->last_reset_at)->addDays($shop->reset_days):date('Y-m-d 00:00:00', strtotime("+" . $shop->reset_days . " days"))}}
		@endif
	</td>
	@endif
	<td>
		@if($shop->is_blocked)
			<small><i class="fa fa-circle text-red"></i></small>
		@elseif($shop->pending)
			<small><i class="fa fa-circle text-yellow"></i></small>
		@else
			<small><i class="fa fa-circle text-green"></i></small>
		@endif
	</td>
	<td>
		@if(auth()->user()->role_id>3 &&  !$shop->is_blocked)
		<a class="addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $shop->shop_id }}" >
		<button type="button" class="btn btn-block btn-success btn-xs"> 충전</button>
	    </a>
		@else
			<button type="button" class="btn btn-block btn-success disabled btn-xs"> 충전</button>
		@endif
	</td>
	<td>
		@if( auth()->user()->isInoutPartner() &&  !$shop->is_blocked)
		<a class="outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $shop->shop_id }}" >
	    <button type="button" class="btn btn-block btn-danger btn-xs"> 환전</button>
		</a>
		@else
			<button type="button" class="btn btn-block btn-danger disabled btn-xs"> 환전</button>
		@endif
	</td>
</tr>