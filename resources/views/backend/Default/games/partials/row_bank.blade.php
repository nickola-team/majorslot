<tr>
    <td>{{ $bank->shop->name }}</td>
	<td>{{ $bank->shop->percent }}</td>
	<td>{{ number_format($bank->slots,0) }} <a href="#" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="slots" data-shop="{{ $bank->shop_id }}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
</tr>