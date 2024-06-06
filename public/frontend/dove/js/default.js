function openNav() {
    $('.slideout-wrapper').css('display', 'block');
  }
  
  function closeNav() {
    $('.slideout-wrapper').css('display', 'none');
  }
function openLoginModal(logo) {
    const div = document.getElementById('main-modal');
    var logincontent = `<div id="login-modal" class="dialog row" style="flex-direction: row;">
    <div class="container row" style="flex-direction: row; max-width: 400px;">
      <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal();">
        <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
      </button>
      <div data-v-6194c674="" class="container column">
        <div data-v-6194c674="" class="logo-wrap row" style="flex-direction: row;">
            <img src="/frontend/${logo}/logo/${logo}.png">
        </div>
        <div data-v-6194c674="" class="header-text row" style="flex-direction: row;">
          <span data-v-6194c674="" class="text-level-5-5 text">회원님의 접속을 환영합니다</span>
        </div>
        <div data-v-6194c674="" class="form-wrap column">
          <div data-v-6194c674="" class="form column">
            <input data-v-578d3222="" data-v-6194c674="" type="text" placeholder="아이디를 입력하세요" inputmode="text" class="input">
            <input data-v-578d3222="" data-v-6194c674="" type="password" placeholder="비밀번호를 입력하세요" inputmode="text" class="input">
            <button data-v-6194c674="" class="login-btn button block" style="height: 45px;">
              <span data-v-6194c674="" class="text">로그인</span>
            </button>
          </div>
          <div data-v-6194c674="" class="spacer"></div>
          <div data-v-6194c674="" class="forgot-password row" style="flex-direction: row;">
            <div data-v-6194c674="" class="row" style="height: 25px; flex-direction: row;">
              <div data-v-f92ca952="" data-v-6194c674="" class="row" style="flex-direction: row;">
                <div class="row" style="width: 100%; flex-direction: row;">
                  <button data-v-6194c674="" class="margin-left-5 button text" style="color: rgb(255, 255, 255); background: transparent;" onclick="openRegisterModal('${logo}');">
                    <span data-v-6194c674="" class="text">회원가입</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>`;
  div.innerHTML = logincontent;
}
function closeModal(){
    document.getElementById('main-modal').innerHTML = "";
}
function openRegisterModal(logo) {
    const div = document.getElementById('main-modal');
    var logincontent = `<div class="dialog row" style="flex-direction: row;">
    <div class="container row" style="flex-direction: row; max-width: 400px;">
      <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal();">
        <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
      </button>
      <div data-v-06860046="" class="container column">
        <div data-v-06860046="" class="logo-wrap row" style="flex-direction: row;">
        <img src="/frontend/${logo}/logo/${logo}.png">
        </div>
        <div data-v-06860046="" class="header-text row" style="flex-direction: row;">
          <span data-v-06860046="" class="text-level-5-5 text">도브카지노에 오신 것을 환영합니다</span>
        </div>
        <div data-v-06860046="" class="header-text-2 row" style="flex-direction: row;">
          <span data-v-06860046="" class="text-level-9 text">회원가입 후 프로모에 참가하세요</span>
        </div>
        <div data-v-06860046="" class="form-wrap scrollable-auto column">
          <div data-v-06860046="" class="form column">
            <span data-v-06860046="" class="title text">아이디</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="아이디를 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">비밀번호</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="password" placeholder="비밀번호를 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">비밀번호확인</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="password" placeholder="비밀번호를 다시 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">닉네임</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="닉네임을 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <div data-v-06860046="" class="margin-0 row" style="flex-direction: row;">
              <span data-v-06860046="" class="title text">휴대폰번호</span>
            </div>
            <div data-v-06860046="" class="column">
              <div data-v-06860046="" class="row" style="flex-direction: row;">
                <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="휴대폰번호를 입력하세요" inputmode="text" class="input" style="width: unset; background-color: rgb(44, 48, 58); flex-grow: 2;">
                <!---->
              </div>
            </div>
            <!---->
            <span data-v-06860046="" class="title text">은행명</span>
            <div data-v-06860046="" class="column">
              <div data-v-06860046="" dir="auto" class="v-select vs--single vs--unsearchable">
                <div id="vs3__combobox" role="combobox" aria-expanded="false" aria-owns="vs3__listbox" aria-label="Search for option" class="vs__dropdown-toggle">
                  <div class="vs__selected-options">
                    <span class="vs__selected"> 국민은행
                      <!---->
                    </span>
                    <input readonly="readonly" aria-autocomplete="list" aria-labelledby="vs3__combobox" aria-controls="vs3__listbox" type="search" autocomplete="off" class="vs__search">
                  </div>
                  <div class="vs__actions">
                    <button type="button" title="Clear Selected" aria-label="Clear Selected" class="vs__clear" style="display: none;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10">
                        <path d="M6.895455 5l2.842897-2.842898c.348864-.348863.348864-.914488 0-1.263636L9.106534.261648c-.348864-.348864-.914489-.348864-1.263636 0L5 3.104545 2.157102.261648c-.348863-.348864-.914488-.348864-1.263636 0L.261648.893466c-.348864.348864-.348864.914489 0 1.263636L3.104545 5 .261648 7.842898c-.348864.348863-.348864.914488 0 1.263636l.631818.631818c.348864.348864.914773.348864 1.263636 0L5 6.895455l2.842898 2.842897c.348863.348864.914772.348864 1.263636 0l.631818-.631818c.348864-.348864.348864-.914489 0-1.263636L6.895455 5z"></path>
                      </svg>
                    </button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" role="presentation" class="vs__open-indicator">
                      <path d="M9.211364 7.59931l4.48338-4.867229c.407008-.441854.407008-1.158247 0-1.60046l-.73712-.80023c-.407008-.441854-1.066904-.441854-1.474243 0L7 5.198617 2.51662.33139c-.407008-.441853-1.066904-.441853-1.474243 0l-.737121.80023c-.407008.441854-.407008 1.158248 0 1.600461l4.48338 4.867228L7 10l2.211364-2.40069z"></path>
                    </svg>
                    <div class="vs__spinner" style="display: none;">Loading...</div>
                  </div>
                </div>
                <ul id="vs3__listbox" role="listbox" style="display: none; visibility: hidden;"></ul>
              </div>
            </div>
            <span data-v-06860046="" class="title text">계좌번호</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="계좌번호를 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">예금주</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="예금주를 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">환전비밀번호</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="password" placeholder="환전비밀번호를 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">환전비밀번호 확인</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="password" placeholder="환전비밀번호를 다시 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">추천코드</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="추천코드를 입력하세요" inputmode="text" class="input" style="background-color: rgb(44, 48, 58);">
            </div>
            <div data-v-06860046="" class="column">
              <button data-v-06860046="" class="signin-btn button" style="height: 45px;">
                <span data-v-06860046="" class="text">가입완료</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>`;
  div.innerHTML = logincontent;
}