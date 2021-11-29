<tr>
    <td>
	@if ($bank->game_id == 0)
		일반
	@else
		{{ $bank->game->title }}
	@endif
	</td>
	<td>{{ number_format($bank->bank,0) }} <a href="#" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="bonus" data-shop="{{ $bank->id }}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
	<td>
		@if ($bank->max_bank == 0)
			없음
		@else
		{{ number_format($bank->max_bank,0) }} 
		@endif
		<a href="#" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="bonusmax" data-shop="{{ $bank->id }}">수정 <i class="fa fa-arrow-circle-right"></i></a>
	</td>
</tr>