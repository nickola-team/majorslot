<div class="con1">
    <ul>
        <li><a href="#" class="casino_3_open"><img src="/frontend/kdior/images/main_game3.png?v=202301301150" class="item front"><img src="/frontend/kdior/images/main_game3_h.png?v=202301301150" class="item back"></a></li>                                
        <li><a href="#" 
        @if (isset($live) && $live=='disabled')
        onclick="alert('점검중입니다');"
        @else
        class="casino_1_open"
        @endif
        ><img src="/frontend/kdior/images/main_game1.png?v=202301301150" class="item front"><img src="/frontend/kdior/images/main_game1_h.png?v=202301301150" class="item back"></a></li>
        @if (!isset($logo) || $logo != 'dorosi')
        {{--
        <li><a href="#" 
        @if (isset($hotel) && $hotel=='disabled')
        onclick="alert('점검중입니다');"
        @else
        class="casino_2_open"
        @endif
        ><img src="/frontend/kdior/images/main_game2.png?v=202301301150" class="item front"><img src="/frontend/kdior/images/main_game2_h.png?v=202301301150" class="item back"></a></li>--}}
        <li><a href="#" 
        @if (isset($mini) && $mini=='disabled')
        onclick="alert('점검중입니다');"
        @else
        class="casino_4_open"
        @endif
        ><img src="/frontend/kdior/images/main_game4.png?v=202301301150" class="item front"><img src="/frontend/kdior/images/main_game4_h.png?v=202301301150" class="item back"></a></li>      
        @endif 
        <li><a href="#"
                @auth
                    {{$isSports = false}}
                    @foreach($categories AS $index=>$category)
                        @if ($category->type =='sports')
                            @if ($category->status == 0)
                                onclick="alert('점검중입니다');"
                            @elseif ($category->view == 0)
                                onclick="alert('지원하지 않는 게임입니다.');"
                            @else
                                onclick="startGameByProvider('bti', 'sports')"
                            @endif 
                                {{$isSports = true}} 
                                @break
                        @endif
                    @endforeach
                    @if(!$isSports)
                        onclick="alert('지원하지 않는 게임입니다.');"
                    @endif
                @else
                    class="etc_pop2_open"
                @endif>
                <img src="/frontend/kdior/images/main_game5.png?v=202301301150" class="item front"><img src="/frontend/kdior/images/main_game5_h.png?v=202301301150" class="item back">
            </a>
        </li>          
    </ul>
</div>
