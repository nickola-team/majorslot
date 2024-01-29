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
    </ul>
</div>
