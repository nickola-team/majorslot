<tr>
    <td>{{ $bank->shopname }}</td>
	<td>{{ $bank->percent }}</td>
	<td>{{ number_format($bank->slots,2) }} <a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="slots" data-shop="{{ $bank->shop_id }}">환수금조절 <i class="fa fa-arrow-circle-right"></i></a></td>
	<td>{{  number_format($bank->table_bank,2) }} <a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="table_bank"  data-shop="{{ $bank->shop_id }}">환수금조절 <i class="fa fa-arrow-circle-right"></i></a></td>
	<td>{{  number_format($bank->master_bonus,2) }} <a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="bonus"  data-shop="{{ $bank->master_id }}">환수금조절 <i class="fa fa-arrow-circle-right"></i></a></td>
</tr>