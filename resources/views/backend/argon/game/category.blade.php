@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-category'
    ])
@section('page-title',  '게임사목록')

@section('content')
<div class="container-fluid">
    <!-- Search -->
    <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">검색</h3>
                    </div>
                    <div class="col-4 text-right box-tools">
                        <a class="box-button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"></a>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div id="collapseOne" class="collapse show">
                <div class="card-body">
                    <form action="" method="GET" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">파트너이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('user')}}" id="user" name="user">
                            </div>
                            <label for="role" class="col-md-2 col-form-label form-control-label text-center">파트너 레벨</label>
                            <div class="col-md-3">
                                <select class="form-control" id="role" name="role">
                                    <option value="" @if (Request::get('role') == '') selected @endif>@lang('app.all')</option>
                                    @for ($level=3;$level<=auth()->user()->role_id;$level++)
									<option value="{{$level}}" @if (Request::get('role') == $level) selected @endif> {{\VanguardLTE\Role::find($level)->description}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">게임이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('category')}}" id="category" name="category">
                            </div>
                        </div>
                            
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <button type="submit" class="btn btn-primary col-md-10">검색</button>
                            <div class="col-md-1">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">게임사 목록</h3>
    </div>
    <div class="table-responsive">
        <table class="table align-items-center table-flush" id="agentlist">
            <thead class="thead-light">
                <tr>
                <th scope="col">파트너</th>
                <th scope="col">게임이름</th>
                <th scope="col">포지션</th>
                <th scope="col">게임갯수</th>
                <th scope="col">게임제공사</th>
                <th scope="col">사용중 매장</th>
                <th scope="col">미사용 매장</th>
                <th scope="col">운영중 매장</th>
                <th scope="col">점검중 매장</th>
                <th class="text-right">일괄적용</th>
                </tr>
            </thead>
            <tbody class="list">
            <?php 
                $badge_class = \VanguardLTE\User::badgeclass();
            ?>
            @if (count($users) > 0)
                @foreach ($users as $user)
                    <tr>
                        @if (count($categories)>0)
                            <td rowspan="{{$categories->count()}}" style="border-right: 1px solid rgb(233 236 239);" > 
                            <a href="#" data-toggle="tooltip" data-original-title="{{$user->parents(auth()->user()->role_id)}}">
                                {{ $user->username }} <span class="badge {{$badge_class[$user->role_id]}}">{{$user->role->description}}</span>
                            </a>
                            </td>
                            @include('backend.argon.game.partials.row_category')
                        @endif
                    </tr>
                @endforeach
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
        {{ $categories->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop