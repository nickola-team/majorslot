@extends('system.layouts.errors')

@section('title', 'Site is Closed')

@section('content')
<div class="box">
        <div class="box__ghost">
            <div class="symbol"></div>
            <div class="symbol"></div>
            <div class="symbol"></div>
            <div class="symbol"></div>
            <div class="symbol"></div>
            <div class="symbol"></div>
            
            <div class="box__ghost-container">
            <div class="box__ghost-eyes">
                <div class="box__eye-left"></div>
                <div class="box__eye-right"></div>
            </div>
            <div class="box__ghost-bottom">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            </div>
            <div class="box__ghost-shadow"></div>
        </div>
        
        <div class="box__description">
            <div class="box__description-container" style="width:300px;">
            <div class="box__description-title">404 오류!</div>
            <div class="box__description-text">사이트가 존재하지 않습니다.</div>
            <div class="box__description-text">주소를 확인해주세요.</div>
            </div>
            
        </div>
        
        </div>
@stop