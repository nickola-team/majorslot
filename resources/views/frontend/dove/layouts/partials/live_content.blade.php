<div data-v-525011a5="" id="live_content" class="content column" style="display:none">
  <div data-v-525011a5="" class="layout-spacing column">
    <div data-v-4f69ab08="" data-v-525011a5="" class="column">
      <div data-v-4f69ab08="" class="group-banner-wrap row" style="flex-direction: row;">
        <div data-v-4f69ab08="" class="banner-bg row" style="flex-direction: row;"></div>
        <div data-v-4f69ab08="" class="banner-content row" style="flex-direction: row;">
          <span data-v-4f69ab08="" class="text-level-5 text" style="font-weight: bold;">라이브카지노</span>
          <div data-v-4f69ab08="" class="spacer"></div>
          <span data-v-4f69ab08="" class="text" style="width: 100px;">
            <img data-v-4f69ab08="" src="/frontend/dove/assets/img/live_casino.120404c.svg" style="height: 90%;">
          </span>
        </div>
      </div>
      <div data-v-4f69ab08="" class="casino-list row list-4" style="flex-direction: row;">
        @foreach($categories AS $index=>$category)
            @if ($category->type =='live')
              @auth
                @if ($category->status == 0)
                  <button data-v-4f69ab08="" class="zoomIn button" onclick="swal2('점검중입니다.', 'warning')">
                @else
                  <button data-v-4f69ab08="" class="zoomIn button" onclick="casinoGameStart('{{$category->href}}')">
                @endif
              @else
                <button data-v-4f69ab08="" class="zoomIn button" onclick="swal2('로그인후 이용가능합니다.', 'error')">
              @endif
                  <img data-v-4f69ab08="" src="/frontend/dove/assets/img/category/{{strtoupper($category->title)}}.png">
                </button>
            @endif
        @endforeach
      </div>
    </div>
  </div>
</div>