


<transaction-page>
    <div class="transaction-page">
        <div class="transaction-container">
            <div class="transaction-table" style="background: #202020;">
                <div class="table-heading">
                    <span>공지사항</span>

                    <span class="pull-right" onclick=@auth
                    "navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)','공지사항');"
                    @else
                    "navClick('login-popup')"
                    @endif >전체 보기</span>

                </div>
                <div style="overflow: hidden; height:160px;">
                    <ul class=" list-unstyled transaction-list" >
                    @foreach ($noticelist as $ntc)
                        <li role="button" class="ng-scope">
                            <div class="col-xs-4 text-left">
                                <span class="ng-binding">{{date('Y-m-d', strtotime($ntc->date_time))}}</span>&nbsp;
                            </div>
                            <div class="col-xs-8 text-left text-white">
                                <span onclick=@auth
                                    "navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)','공지사항');getNotice('notice{{$ntc->id}}');"
                                    @else
                                    "navClick('login-popup')"
                                    @endif class="ng-binding">{{$ntc->title}}</span>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                </div>
                
            </div>
            <div class="transaction-table">
                <div class="table-heading"><span>실시간 입금 현황</span></div>
                <ul class=" list-unstyled transaction-list"  style="width: 328px;" id="deposit-ticker">
                    
                </ul>
            </div>
            <div class="transaction-table">
                <div class="table-heading"><span>실시간 출금 현황</span></div>
                <ul class=" list-unstyled transaction-list"  style="width: 328px;" id="withdraw-ticker">
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</transaction-page>

    <customer-page>
<div class="customer-page">
    <div class="customer-content">
    <div class="customer-buttons click-disable" onclick="navClick(&#39;login-popup&#39;)">
        <img src="/frontend/newheaven/common/images/customer/partner-icon.png" alt="">
        <div class="text-con">
        <p>파트너를 모십니다. </p>
        <p class="goldTxt">파트너 제휴 문의</p>
        </div>
    </div>
    <div class="customer-buttons click-disable" onclick="navClick(&#39;login-popup&#39;)">
        <img src="/frontend/newheaven/common/images/customer/faq-icon.png" alt="">
        <div class="text-con">
        <p>궁금한 점이 있으세요?</p>
        <p class="goldTxt">자주 묻는 질문 </p>
        </div>
    </div>
    <div class="customer-buttons click-disable text-center">
        <img src="/frontend/newheaven/common/images/customer/telegram-icon.png" alt="">
        <div class="text-con text-left">
        <p>365 게임문의</p>
        <p class="goldTxt ng-binding" ng-bind="techContactNumber">텔레그램 </p>
        </div>
    </div>
    <div class="customer-buttons click-disable text-center">
        <img src="/frontend/newheaven/common/images/customer/live-chat-icon.png" alt="">
        <div class="text-con text-left">
        <p>365 결제문의</p>
        <p class="goldTxt ng-binding" ng-bind="bankContactNumber">카톡 </p>
        </div>
    </div>
    <div class="clearfix"></div>
    </div>
</div>
</customer-page>


    <script>
  setTimeout(function () {
    $('#notice-ticker').newsTicker({
      row_height: 14.5,
      max_rows: 11,
      duration: 2000,
      autostart:0
    });
    $('#deposit-ticker').newsTicker({
      row_height: 32,
      max_rows: 5,
      direction: 'up',
      autostart: 1,
      pauseOnHover: 1,
      delay : 10000
    });
  });
  setTimeout(function () {
    $('#withdraw-ticker').newsTicker({
      row_height: 32,
      max_rows: 5,
      direction: 'up',
      autostart: 1,
      pauseOnHover: 1,
      delay : 10000
    });
  },500);
</script>