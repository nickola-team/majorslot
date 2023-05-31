@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-domain'
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
                            <label for="domain" class="col-md-2 col-form-label form-control-label text-center">도메인제목</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('domain')}}" id="domain" name="domain">
                            </div>
                            <label for="category" class="col-md-2 col-form-label form-control-label text-center">게임이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('category')}}" id="category" name="category">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="username" class="col-md-2 col-form-label form-control-label text-center">총본사이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('username')}}" id="username" name="username">
                            </div>
                            <div class="col-md-1">
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
                <th scope="col">도메인</th>
                <th scope="col">게임이름</th>
                <th scope="col">포지션</th>
                @if (auth()->user()->hasRole('admin'))
                <th scope="col">게임제공사</th>
                <th scope="col">사용여부</th>
                @else
                <th scope="col">게임제공사</th>
                @endif
                <th scope="col">상태</th>
                <th></th>
                </tr>
            </thead>
            <tbody class="list">
            @if (count($sites) > 0)
                @foreach ($sites as $site)
                    <tr>
                        <?php
                            $categories = $site->categories;
                            if (!auth()->user()->hasRole('admin'))
                            {
                                $excat = ['habaneroplay', 'bngplay','cq9play'];
                                $categories = $categories->where('view',1)->where('parent', 0);
                            }
                            if (Request::get('category') != '')
                            {
                                $categories = $categories->where('title', Request::get('category'));
                            }
                        ?>
                        @if (count($categories)>0)
                            <td rowspan="{{$categories->count()}}" style="border-right: 1px solid rgb(233 236 239);" > 
                                {{ $site->title }} 
                                <p>
                                <a href="{{$site->domain}}">{{$site->domain}}</a>
                            </td>

                            @include('backend.argon.game.partials.row_domain', ['categories' => $categories])
                        @else
                        <td > 
                            {{ $site->title }} 
                            <p>
                            <a href="{{$site->domain}}">{{$site->domain}}</a>
                        </td>
                        <td colspan='5'>No Data</td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan='6'>No Data</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $sites->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop