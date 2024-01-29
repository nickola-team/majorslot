@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-game'
    ])
@section('page-title',  '게임목록')

@section('content')
<div class="container-fluid">
<div class="row">
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">게임목록</h3>
    </div>
    <div class="table-responsive">
        <table class="table align-items-center table-flush" id="agentlist">
            <thead class="thead-light">
                <tr>
                <th scope="col">파트너/게임사</th>
                <th scope="col">게임이름</th>
                <th scope="col">활성된 매장</th>
                <th scope="col">비활성된 매장</th>
                <th scope="col">밸런스</th>
                <th class="text-right">일괄적용</th>
                </tr>
            </thead>
            <tbody class="list">
            <?php 
                $badge_class = \VanguardLTE\User::badgeclass();
            ?>
            @if (count($games) > 0)
                    <tr>
                        @if (count($games)>0)
                            <td rowspan="{{$games->count()}}" style="border-right: 1px solid rgb(233 236 239);" > 
                            <a href="#" data-toggle="tooltip" data-original-title="{{$user->parents(auth()->user()->role_id)}}">
                                {{ $user->username }} <span class="badge {{$badge_class[$user->role_id]}}">{{$user->role->description}}</span>
                                <p>
                                {{$category->title}}
                            </a>
                            </td>
                            @include('backend.argon.game.partials.row_game')
                        @endif
                    </tr>
            @else
                <tr>
                    <td colspan='4'>No Data</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
    {{ $games->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop