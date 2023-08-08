<div class="con1">
    <ul>
        <li style="width:48%">
            <a href="#" class="casino_3_open">
                <img style="width:100%" src="/frontend/amor/images/main_game3.png?v=202301301150" class="item front">
                <img style="width:100%" src="/frontend/amor/images/main_game3_h.png?v=202301301150" class="item back">
            </a>
        </li>                                
        <li style="width:48%">
            <a href="#" 
                @if (isset($live) && $live=='disabled')
                onclick="alert('점검중입니다');"
                @else
                class="casino_1_open"
                @endif
                >
                <img style="width:100%" src="/frontend/amor/images/main_game1.png?v=202301301150" class="item front">
                <img style="width:100%" src="/frontend/amor/images/main_game1_h.png?v=202301301150" class="item back">
            </a>
        </li>
    </ul>
</div>
