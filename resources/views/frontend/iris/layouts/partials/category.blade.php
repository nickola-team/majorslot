<div class="category-main">
    <div class="category-center" style="overflow:auto;text-wrap:nowrap;">
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
        @if (isset($logo) && ($logo != 'nyx'))
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
        @endif
        <a href="javascript:void(0)" class="" onclick = 
                    @auth
                      {{$isSports = false}}
                      @foreach($categories AS $index=>$category)
                          @if ($category->type =='sports')
                              @if ($category->status == 0)
                                  "alert('점검중입니다')"
                              @elseif ($category->view == 0)
                                  "alert('지원하지 않는 게임입니다.')"
                              @else
                                  "startGameByProvider('nexus', 'bt1_sports')"
                              @endif 
                                  {{$isSports = true}} 
                                  @break
                          @endif
                      @endforeach
                      @if(!$isSports)
                          "alert('지원하지 않는 게임입니다.')"
                      @endif
                    @else
                    "mustSignIn('로그인이 필요한 메뉴입니다.')"
                    @endif
        >
           <div class="cat-icon icon_sports"></div>
           <div class="cat-text">스포츠</div>
        </a>
    </div>
</div>
