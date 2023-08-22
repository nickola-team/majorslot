<div class="body-main">
    <div class="body-center">     
        <div class="cs-container">
            
                
        <div class="cs-casino-slot slot active">
            @if ($categories && count($categories))
            @foreach($categories AS $index=>$category)
                @if ($category->type =='slot')
                @auth()
                @if ($category->status == 0)
                <a href="javascript:void(0)" class="cs-btn" onclick="mustSignIn('점검중입니다.')">                      
                @else
                <a href="javascript:void(0)" class="cs-btn" onclick="slotGame('{{$category->href}}')">                      
                @endif
                @else
                <a href="javascript:void(0)" class="cs-btn" onclick="mustSignIn('로그인이 필요한 메뉴입니다.')">                      
                @endif
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
                @auth()
                @if ($category->status == 0)
                <a href="javascript:void(0)" class="cs-btn" onclick="mustSignIn('점검중입니다.')">
                @else
                <a href="javascript:void(0)" class="cs-btn" onclick="casinoGameStart('{{$category->href}}')">
                @endif
                @else
                <a href="javascript:void(0)" class="cs-btn" onclick="mustSignIn('로그인이 필요한 메뉴입니다.')">
                @endif
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
        <div class="cs-casino-slot mini">
            @php
                $status = 0;
            @endphp 
            @foreach($categories AS $index=>$category)
                @if ($category->type =='pball')
                    @if ($category->status == 0)
                        @php
                            $status = 1;
                        @endphp 
                    @else
                        @php
                            $status = 2;
                        @endphp 
                    @endif
                @endif
            @endforeach
            @foreach($pbgames AS $pbgame)
                <a href="javascript:void(0)" 
                @auth 
                    @switch($status)
                        @case(0)
                            onclick="alert('지원하지 않는 게임입니다.');"
                            @break
                        @case(1)
                            onclick="alert('점검중입니다');"
                            @break
                        @case(2)
                            onclick="startGameByProvider(null, '{{$pbgame['name']}}',true);"
                            @break
                        @default
                    @endswitch
                @endauth
                @guest
                    onclick="mustSignIn('로그인이 필요한 메뉴입니다.')"
                @endguest
                class="cs-btn" >
                    <div class="img-cont">
                        <div class="main-img">
                            <img src="/frontend/Default/ico/{{$pbgame['name']}}.jpg"  class="bgImg">                              
                            <img class="on" src="/frontend/iris/theme/sp/images/game/game_slot_frame_fix2.png">
                            <div class="text-cont">
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>                       
    </div>
    </div>
</div>