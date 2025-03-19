@extends('backend.argon.layouts.app',
    [
        'parentSection' => 'share',
        'elementName' => 'share-setting'
    ])

@section('page-title',  '받치기 설정')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-12 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-12">
                        <h3 class="mb-0">받치기 설정</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                    <div class="form-group row">
                        <div class="col-4 text-center">
                            이 름
                        </div>
                        <div class="col-8">
                            {{$partner->username}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 text-center">
                            레 벨
                        </div>
                        <div class="col-8">
                            {{$partner->role->description}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 text-center">
                            받치기롤링금
                        </div>
                        <div class="col-8">
                            {{number_format($partner->deal_balance)}}
                            @if ($partner->id == auth()->user()->id)
                            &nbsp;<a class="btn btn-success btn-sm" href="#" onclick="convert_deal_balance();">롤링금전환</a>
                            @endif  
                        </div>
                        
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-4 text-center">
                            받치기설정
                        </div>
                        <div class="col-8">
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>게임사</th>
                                        <th>게임타입</th>
                                        <th>베팅판</th>
                                        <th>최소배팅금</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $type_counts = [
                                            'total' => 0
                                        ];
                                        foreach (\VanguardLTE\ShareBetInfo::BET_TYPES as $type => $values)
                                        {
                                            $type_counts[$type] = count($values);
                                            $type_counts['total'] = $type_counts['total'] + count($values);
                                        }
                                    ?>
                                    @foreach ($categories as $cat)
                                    <form action="" method="POST"  id="form">
                                    <input type="hidden" value="{{$partner->id}}" name="user_id">
                                    <input type="hidden" value="{{$cat->original_id}}" name="cat_id">
                                    <tr>
                                        <td style="padding:3px;" rowspan="{{$type_counts['total']}}">{{($cat->trans)?$cat->trans->trans_title:$cat->title}}</td>
                                        @foreach (\VanguardLTE\ShareBetInfo::BET_TYPES as $type => $values)
                                            <?php $limitinfo = $values; ?>
                                            @if ($loop->index == 0)
                                            <td style="padding:3px;" rowspan="{{$type_counts[$type]}}">{{__($type)}}</td>
                                            @endif
                                            @foreach ($sharebetinfos as $info)
                                                @if ($info->category_id == $cat->original_id)
                                                    <?php $partner_limitinfo = json_decode($info->limit_info, true); ?>
                                                @endif
                                            @endforeach
                                            
                                            @foreach ($limitinfo as $betname => $betvalue)
                                                @if ($loop->index > 0)
                                                <tr>
                                                @endif
                                                <td style="padding:3px;">{{__($betname)}}</td>
                                                <td style="padding:3px;">
                                                @if (isset($partner_limitinfo[$betname]))
                                                <?php $betvalue = $partner_limitinfo[$betname]; ?>
                                                @endif
                                                @if ($partner->id == auth()->user()->id)
                                                    {{number_format($betvalue)}}
                                                @else
                                                    <input type="text" name="{{$betname}}"  class="form-control" value="{{$betvalue}}">
                                                @endif
                                                </td>
                                                @if ($loop->index > 0)
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tr>
                                    @if ($partner->role_id < auth()->user()->role_id)
                                    <tr><td colspan="3"></td><td><button type="button" class="btn btn-primary col-12" id="doSubmit">설정</button></td></tr>
                                    @endif
                                    </form>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if ($partner->id == auth()->user()->id)
                                <span class="text-red" style="font-size:.9rem;">*** 최소배팅금이 0이면 받치기가 적용되지 않습니다.</span>
                                @else
                                <span class="text-red" style="font-size:.9rem;">*** 최소배팅금에 0을 입력하면 받치기적용을 하지 않습니다.</span>
                                @endif
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="alarm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">알림</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="msgbody"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
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

    function show_alarm(msg, aftercallback)
    {
        $('#msgbody').html(msg);
        $('#alarm').on('hidden.bs.modal', function () {
                if (aftercallback) {
                    aftercallback();
                }
        });
        $('#alarm').modal();
    }

    function convert_deal_balance() {
        $.ajax({
            type: 'POST',
            url: "{{route('frontend.api.convert_deal_balance')}}",
            data: { summ: 0},
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    show_alarm(data.msg);
                    return;
                }
                show_alarm('롤링금이 전환되었습니다.', function() { location.reload(true);});
            },
            error: function (err, xhr) {
                show_alarm(err.responseText);
            }
        });
    }
    
</script>
@endpush