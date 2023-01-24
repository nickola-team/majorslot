@extends('frontend.iris.layouts.app')
@section('page-title', $title)

@section('content')
<div class="body-main">
    <div class="body-center">     
        <div class="cs-container">
            
                
        <div class="cs-casino-slot slot active">
            @if ($categories && count($categories))
            @foreach($categories AS $index=>$category)
                @if ($category->type =='slot')
                <a href="javascript:void(0)" class="cs-btn" onclick="slotgameOn({{$category->original_id}})">                      
                    <div class="img-cont">
                        <div class="main-img">
                            <img src="/frontend/iris/theme/sp/images/slot_image/{{ $category->title.'.png' }}"  class="bgImg">                              
                            <img class="on" src="/frontend/iris/theme/sp/images/game/game_slot_frame_fix2.png">
                            
                            <div class="text-cont">
                            </div>
                            <p class="name">{{ $category->trans?$category->trans->trans_title:$category->title }}</p>
                        </div>
                    </div>
                </a>
                @endif
            @endforeach
            @endif
        </div>
 
        <div class="cs-casino-slot casino">
            @if ($categories && count($categories))
            @foreach($categories AS $index=>$category)
                @if ($category->type =='live')
                <a href="javascript:void(0)" class="cs-btn" onclick="gameStart({{$category->original_id}})">                      
                    <div class="img-cont">
                        <div class="main-img">
                            <img src="/frontend/iris/theme/sp/images/slot_image/{{ $category->title.'.png' }}"  class="bgImg">                              
                            <img class="on" src="/frontend/iris/theme/sp/images/game/game_slot_frame_fix2.png">
                            
                            <div class="text-cont">
                            </div>
                            <p class="name">{{ $category->trans?$category->trans->trans_title:$category->title }}</p>
                        </div>
                    </div>
                </a>
                @endif
            @endforeach
            @endif
        </div>                       
    </div>
    </div>
</div>
@stop