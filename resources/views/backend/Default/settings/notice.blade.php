@extends('backend.Default.layouts.app')

@section('page-title', trans('app.general_settings'))
@section('page-heading', trans('app.general_settings'))

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">

        <div class="box box-default">
            {!! Form::open(['route' => 'backend.settings.notice.update', 'id' => 'notice-settings-form', 'files' => true]) !!}
            <div class="box-header with-border">
                <h3 class="box-title">공지관리</h3>
            </div>

            <div class="box-body">
            <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label>미리보기</label>
                        <p></p>
                        @if($notice != null)
                        <img src="{{$notice->image}}" style="width: 100%;vertical-align: bottom;" alt="팝업공지이미지를 찾을수 없습니다">
                        @else
                        <p class="text-red">팝업공지를 설정하지 않았습니다.</p>
                        @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label>이미지 업로드</label>
                        <input type="file" id="popupimg" name="popupimg" accept="image/png, image/jpeg">
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    공지 설정
                </button>
                <a href="{{route('backend.settings.notice.del')}}">
                <button type="button" class="btn btn-danger">
                    공지 삭제
                </button>
                </a>

            </div>

            
            {{ Form::close() }}
        </div>
    </section>

@stop
