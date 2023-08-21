<div class="category-main">
    <div class="category-center">
        <a href="javascript:void(0)" class="cat-slot active">
           <div class="cat-icon icon_slot"></div>
           <div class="cat-text">슬롯게임</div>
        </a>
        <a href="javascript:void(0)" 
            @if (isset($live) && $live=='disabled')
            class="" onclick = "alert('점검중입니다');"
            @else
            class="cat-casino"
            @endif
        >
           <div class="cat-icon icon_casino"></div>
           <div class="cat-text">라이브카지노</div>
        </a>
        <a href="javascript:void(0)" 
            @if (isset($mini) && $mini=='disabled')
            class="" onclick = "alert('점검중입니다');"
            @else
            class="cat-mini"
            @endif
        >
           <div class="cat-icon icon_mini"></div>
           <div class="cat-text">미니게임</div>
        </a>
    </div>
</div>
