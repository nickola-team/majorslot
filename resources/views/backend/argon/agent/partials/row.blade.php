<td><a href="{{route('partners.edit',['id' => $operator->id])}}">{{$operator->username}}</a></td>
<td>{{$operator->email}}</td>
<td>
    @if ($operator->referral)
        {{$operator->referral->username}} [{{__(\App\Models\User::ROLE_NAMES[$operator->referral->role_id])}}]
    @endif
</td>
@if ($role_id == \App\Models\User::ROLE_OPERATOR)
    <td>
        @if ($operator->shop)
        {{$operator->shop->percent}}
        @endif
    </td>
@endif
<td>{{number_format($operator->balance)}}</td>
<td>{{number_format($operator->childBalanceSum())}}</td>
<td>{{number_format($operator->money_percent,2)}}</td>
<td>{{$operator->created_at}}</td>
<td>
    @if ($operator->status == 'Active')
        <span class='text-green'>{{__($operator->status)}}</span>
    @else
        <span class='text-red'>{{__($operator->status)}}</span>
    @endif
</td>
<td class="text-right">
    <button type="button" class="btn btn-primary newPayment addPayment" data-toggle="modal" data-target="#modal-addbalance" data-id="{{$operator->id}}">{{__('AddBalance')}}</button>
    <button type="button" class="btn btn-danger newPayment outPayment" data-toggle="modal" data-target="#modal-outbalance" data-id="{{$operator->id}}">{{__('OutBalance')}}</button>
    @if ($operator->status == 'Active')
    <a  class="btn btn-warning" href="{{route('partners.status.update', ['id'=>$operator->id, 'status'=>\App\Models\User::STATUS_BANNED])}}">{{__('Block')}}</a>
    @else
    <a class="btn btn-success" href="{{route('partners.status.update', ['id'=>$operator->id, 'status'=>\App\Models\User::STATUS_ACTIVE])}}">{{__('Activate')}}</a>
    @endif
    <button type="button" class="btn btn-danger newPayment deleteUser" data-toggle="modal" data-target="#modal-confirm" data-id="{{$operator->id}}">{{__('Delete')}}</button>
</td>
