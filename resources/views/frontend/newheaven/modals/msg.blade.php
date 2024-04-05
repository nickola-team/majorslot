@if($detect->isMobile() || $detect->isTablet())
<div id="msg-popup" class="ngdialog ngdialog-theme-default ngdialog-promotion ngdialog-direct ng-scope ng-hide">
@else
<div id="msg-popup" class="ngdialog ngdialog-theme-default ngdialog-main-default ngdialog-msg ng-scope ng-hide">
@endif
        <div class="ngdialog-overlay"></div>
        <div class="ngdialog-content" role="document">
          <div class="ngdialog-customer-page ngdialog-main-default-page ng-scope" ng-controller="CustomerController" ng-init="setTab(selectCustomerTab)">
            @if($detect->isMobile() || $detect->isTablet())
            <div class="ngdialog__header">
              <div class="ngdialog__header__title">
              <h1 class="ng-scope" translate="" id="msgPop">고객센터</h1>
              </div>
            </div>
            @else
            <div class="ngdialog__heading">
              <h4 class="ngdialog__title text-left ng-scope" translate="" id="msgPop">고객센터</h4>
            </div>
            @endif
            <ul class="ngdialog-main-nav list-inline clearfix">
              <li onclick="setTab('notice-set',this,'공지사항')">
                <span class="main-nav__label ng-scope">공지사항</span>
              </li>
              <li onclick="setTab('event-set',this,'이벤트')">
                <span class="main-nav__label ng-scope">이벤트</span>
              </li>
              <li onclick="setTab('customer-set',this,'고객센터');getCustomerPage()">
                <span class="main-nav__label ng-scope">고객센터</span>
                @if($detect->isMobile() || $detect->isTablet())
                <span class="mbadge ng-binding cc-text" ng-bind="UnreadDM" id="_my_msg">{{$unreadmsg}}</span>
                @else
                <span class="badge ng-binding cc-text" ng-bind="UnreadDM" id="_my_msg">{{$unreadmsg}}</span>
                @endif
              </li>
            </ul>
            
            @if($detect->isMobile() || $detect->isTablet())
            <div id="notice-set" class="ngdialog-main-default__content ng-hide" style="overflow-y: auto; height: 500px;">
            @else
            <div id="notice-set" class="ngdialog-main-default__content announcement ng-hide" style="overflow-y: auto; height: 500px;">
            @endif
                          
              <span id="contentSet1" class="contentSet"></span>
              <table class="history-table table" id="noticelist">
                <tbody>
                  <tr class="table-border">
                    <th translate="" class="width6 text-center ng-scope">번호</th>
                    <th translate="" class="text-left width70 ng-scope">제목</th>
                    <th translate="" class="width15 text-center ng-scope">작성일</th>
                  </tr>
                  @foreach ($noticelist as $ntc)
                    <tr ng-repeat="announce in filteredPage" class="ng-scope">
                      <td ng-bind="announce.num" class="width12 text-center ng-binding">{{$ntc->id}}</td>
                      <td class="text-left width70">
                        <a href="javascript:getNotice('notice{{$ntc->id}}')" class="ng-binding">{{$ntc->title}}</a>
                      </td>
                      <td class="width15 text-center ng-binding">{{date('Y-m-d', strtotime($ntc->date_time))}}</td>
                    </tr>
                    <tr ng-repeat="announce in filteredPage" class="ng-scope" id="notice{{$ntc->id}}" style="display:none;">
                      <td colspan="3"><div><?php echo $ntc->content; ?></div></td>
                    </tr>

                  @endforeach

                </tbody>
              </table>

            </div>

            <div id="event-set" class="ngdialog-main-default__content promo-event ng-hide">              
              <span id="contentSet3" class="contentSet"></span>
              <table class="history-table table">
                <tbody>
                  <tr class="table-border">
                    <th translate="" class="width6 text-center ng-scope">번호</th>
                    <th translate="" class="text-left width70 ng-scope">제목</th>
                    <th translate="" class="width15 text-center ng-scope">작성일</th>
                  </tr>

                </tbody>
              </table>

            </div>
            
            <style type="text/css">
              .bbs_reply_manager {
                padding: 12px;
                background: #d5d5d5;
                border-radius: 2px;
                color: #444;
                margin: 10px 0 0 0;
                font-size: 12px;
              }

              .bbs_reply_manager .date {
                text-align: right;
              }
            </style>

            <div id="customer-set" class="ngdialog-main-default__content ng-hide">
              <div class="cc-list">
                <span id="contentSet4" class="contentSet"></span>
                <div  style="overflow-y: auto; height: 500px;">
                  <table class="history-table table">
                    <tbody id="customerList">
                      <tr class="table-border">
                        <th translate="" class="text-left ng-scope" style="padding-left: 20px">제목</th>
                        <th translate="" class="width15 text-center ng-scope">작성 일시</th>
                        <th translate="" class="width15 text-center ng-scope">수신 일시</th>
                        <th translate="" class="width15 text-center ng-scope">타입</th>
                      </tr>
                      
                    </tbody>
                  </table>
                </div>
                
                <span id="customer-pagination"></span>
                <div class="text-center" style="margin-top: 15px;">
                  <button type="submit" class="btn btn-red btn-clear ng-scope btn-send">
                    <span translate="" class="ng-scope">문의하기</span>
                  </button>
                </div>
              </div>
              <form class="ng-pristine ng-valid ng-valid-maxlength cc-from ng-hide">
                <fieldset class="back-button">
                  <button type="button" class="btn btn-red btn-clear ng-scope pull-right cc-back">
                    <span translate="" class="ng-scope">
                      <i class="fa fa-arrow-left"></i>
                    </span>
                  </button>
                </fieldset>
                <input ng-model="writeCustomer.type" type="hidden" class="ng-pristine ng-untouched ng-valid">
                <fieldset>
                  <label translate="" class=" ngdialog__form__label ng-scope">제목</label>
                  <input type="text" class="form-control title1 ng-pristine ng-untouched ng-valid" id="txt_title">
                </fieldset>
                <fieldset>
                  <label translate="" class="ngdialog__form__label ng-scope">내용</label>
                  <textarea rows="4" cols="20" maxlength="300" class="form-control ng-pristine ng-untouched ng-valid ng-valid-maxlength" placeholder="내용은 1000자 이하로 입력해주세요" id="content_txt"></textarea>
                </fieldset>
                <div class="text-center" style="margin-top: 15px;">
                  <button type="button" class="btn btn-red btn-clear ng-scope" onclick="send_text()">작성 완료</button>
                </div>
              </form>
            </div>
            <script type="text/javascript">
              $(document).on('click', '.btn-send', function() {
                $(".cc-from").removeClass('ng-hide');
                $(".cc-list").addClass('ng-hide');
              });
              $(document).on('click', '.cc-back', function() {
                $(".cc-from").addClass('ng-hide');
                $(".cc-list").removeClass('ng-hide');
              });


              
            </script>

          </div>
          @if( $detect->isMobile() || $detect->isTablet() )
          <div class="ngdialog-close" style="margin-top: 8px;"></div>
          @else
          <div class="ngdialog-close"></div>
          @endif
        </div>
      </div>