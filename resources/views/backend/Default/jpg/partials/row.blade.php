<tr>
	<td>{{ $jackpot->id }}</td>
	<td><a href="{{ route($admurl.'.jpgame.edit', $jackpot->id) }}">{{ $jackpot->name }}</a></td>
	<td>{{ number_format($jackpot->balance,0) }}</td> 
	<td>{{ number_format($jackpot->start_balance,0) }}</td>
	<td>{{ number_format($jackpot->pay_sum,0) }}</td>
	<td>{{ number_format($jackpot->pay_sum_new,0) }}</td>
	<td>{{ $jackpot->percent }}</td>
	<td>
		@if(!$jackpot->view)
			<small><i class="fa fa-circle text-red"></i></small>
		@else
			<small><i class="fa fa-circle text-green"></i></small>
		@endif
	</td>
	<td>
		@if( Auth::user()->hasRole('admin') )
		<a class="addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $jackpot->id }}" >
		<button type="button" class="btn btn-block btn-success btn-xs">충전</button>
		</a>
		@else
			<button type="button" class="btn btn-block btn-success disabled btn-xs">충전</button>
		@endif
	</td>
	<td>
		@if( Auth::user()->hasRole('admin'))
		<a class="outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $jackpot->id }}" >
		<button type="button" class="btn btn-block btn-danger btn-xs">환전</button>
		</a>
		@else
			<button type="button" class="btn btn-block btn-danger disabled btn-xs">환전</button>
		@endif
	</td>
</tr>