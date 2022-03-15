<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td>{{ $bank->id }}</td>
<td>{{ $bank->shop->name }} <span class="badge {{$badge_class[3]}}">매장</span></td>
<td>{{ $bank->shop->percent }}</td>
<td>{{ number_format($bank->slots,0) }}</td>
<td> <a href="#" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="slots" data-shop="{{ $bank->shop_id }}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
