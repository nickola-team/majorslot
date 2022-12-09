@extends('system.layouts.errors')

@section('title', 'Game is Closed')

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
            <div class="box__description-title">죄송합니다</div>
            <div class="box__description-text">본 게임을 이용하시려면 에이전트분에게 문의하세요.</div>
            <div class="box__description-text">감사합니다.</div>
            </div>
            
        </div>
        
        </div>
@stop