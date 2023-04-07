@extends('backend.argon.layouts.app',
    [
        'parentSection' => 'share',
        'elementName' => 'share-setting'
    ])

@section('page-title',  '받치기 설정')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">받치기 설정</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                <form action="" method="POST"  id="form">
                    <input type="hidden" value="{{$partner->id}}" name="user_id">
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            이 름
                        </div>
                        <div class="col-7">
                            {{$partner->username}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            레 벨
                        </div>
                        <div class="col-7">
                            {{$partner->role->description}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            받치기롤링금
                        </div>
                        <div class="col-7">
                            {{number_format($partner->deal_balance)}}
                            @if ($partner->id == auth()->user()->id)
                            &nbsp;<a class="btn btn-success btn-sm" href="{{argon_route('argon.share.rolling.convert')}}">롤링금전환</a>
                            @endif  
                        </div>
                        
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            받치기설정
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>게임사</th>
                                        <th>최소베팅금</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($categories as $cat)
                                    <tr>
                                        <td style="padding:3px;">{{($cat->trans)?$cat->trans->trans_title:$cat->title}}</td>

                                        <td style="padding:3px;">
                                        <?php $minlimit = 0; ?>
                                        @foreach ($sharebetinfos as $info)
                                            @if ($info->category_id == $cat->original_id)
                                                <?php $minlimit = intval($info->minlimit); ?>                                                
                                            @endif
                                        @endforeach

                                        @if ($partner->id == auth()->user()->id)
                                            {{number_format($minlimit)}}
                                        @else
                                            <input type="text" name="share_{{$cat->original_id}}" id="share_{{$cat->original_id}}" class="form-control" value="{{$minlimit}}">
                                        @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if ($partner->id == auth()->user()->id)
                                <span class="text-red" style="font-size:.9rem;">*** 최소베팅금이 0이면 받치기가 적용되지 않습니다.</span>
                                @else
                                <span class="text-red" style="font-size:.9rem;">*** 최소베팅금에 0을 입력하면 받치기적용을 하지 않습니다.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($partner->id != auth()->user()->id)
                    <div class="form-group row">
                        <div class="col-6 text-center">
                            <button type="button" class="btn btn-primary col-8" id="doSubmit">설정</button>
                        </div>
                        <div class="col-6 text-center">
                            <button type="button" class="btn btn-secondary col-8" onclick="window.history.go(-1); return false;">취소</button>
                        </div>
                        
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@stop

@push('js')
<script>
    $('#doSubmit').click(function () {
        $(this).attr('disabled', 'disabled');
        $('form#form').submit();
    });
    
</script>
@endpush