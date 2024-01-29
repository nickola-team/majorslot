@extends('backend.argon.layouts.app',[
        'parentSection' => 'dw',
        'elementName' => 'dw-process'
    ])
@section('page-title',  $in_out->type=='add'?'충전처리':'환전처리')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">{{$in_out->type=='add'?'충전처리':'환전처리'}}</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                <form action="" method="POST"  id="form">
                    <input type="hidden" value="{{$in_out->id}}" name="id">
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            신청번호
                        </div>
                        <div class="col-7">
                            {{$in_out->id}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            신청자 아이디
                        </div>
                        <div class="col-7">
                            @if ($in_out->user)
                                {{$in_out->user->username}} 
                                <p><span class="h5">{{$in_out->user->parents(auth()->user()->role_id)}}</span></p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            레 벨
                        </div>
                        <div class="col-7">
                            @if ($in_out->user)
                            {{$in_out->user->role->description}}
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            거래계좌
                        </div>
                        <div class="col-7">
                            {{$in_out->bankInfo()}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 보유금
                        </div>
                        <div class="col-7">
                            @if ($in_out->user)
                            @if ($in_out->user->hasRole('manager'))
                                {{number_format($in_out->user->shop->balance)}}
                            @else
                                {{number_format($in_out->user->balance)}}
                            @endif
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            신청한 금액
                        </div>
                        <div class="col-7">
                            {{number_format($in_out->sum)}}
                        </div>
                    </div>

                    <div class="form-group row">
                        @if ($in_out->type=='add')
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-primary col-8" id="doSubmit">확인</button>
                            </div>
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-secondary col-8" onclick="location.href='{{argon_route('argon.dw.addmanage')}}';">취소</button>
                            </div>
                        @else
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-warning col-12" id="doSubmit">확인</button>
                            </div>
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-secondary col-12" onclick="location.href='{{argon_route('argon.dw.outmanage')}}';">취소</button>
                            </div>
                        @endif
                    </div>
                    
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