<div class="row">
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">최근 신청내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">신청금액</th>
                <th scope="col">타입</th>
                <th scope="col">거래계좌</th>
                <th scope="col">신청 시간</th>
                <th scope="col">상태</th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($in_out_logs) > 0)
                    @foreach ($in_out_logs as $stat)
                        <tr>
                            <td>{{$stat->id}}</td>
                            @if($stat->type == 'add')
                            <td><span class="text-success">{{number_format($stat->sum,0)}}</span></td>
                            <td><span class="text-success">충전</span></td>
                            @else
                            <td><span class="text-warning">{{number_format($stat->sum,0)}}</span></td>
                            <td><span class="text-warning">환전</span></td>
                            @endif
                            <td>{{$stat->bankInfo(!auth()->user()->isInOutPartner())}}</td>
                            <td>{{$stat->created_at}}</td>
                            <td>{{\VanguardLTE\WithdrawDeposit::statMsg()[$stat->status]}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="10">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    </div>
</div>