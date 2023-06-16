@extends('backend.argon.layouts.app',[
        'parentSection' => 'player',
        'elementName' => 'player-game-pending'
    ])
@section('page-title',  '미결중 게임내역')

@section('content')
<div class="container-fluid">
    <!-- Search -->
<div class="row">
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">미결중 게임내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">유저</th>
                <th scope="col">게임사</th>
                <th scope="col">게임명</th>
                <th scope="col">배팅금</th>
                <th scope="col">배팅시간</th>
                <th scope="col">상태</th>
                @if (auth()->user()->hasRole('admin'))
                <th scope="col"></th>
                @endif
                </tr>
            </thead>
            <tbody class="list">
                @if (count($statistics) > 0)
                    @foreach ($statistics as $stat)
                        <?php
                            $warningclass = "";
                            $warningtime = strtotime("-5 minutes");
                            if ($warningtime > strtotime($stat->date_time))
                            {
                                $warningclass = "background-color : orange;";
                            }
                        ?>
                        <tr style="{{$warningclass}}">
                            @include('backend.argon.player.partials.row_pending')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="6">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $statistics->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop