<header-nav-page><header class="header-nav ng-scope" ng-controller="NavController">
  <div class="header-nav__menu">
    <div class="main-container">
      <div class="logo">
        <a href="">
          {{--<img src="/frontend/newheaven/common/images/logo-blue-new.png" alt="">--}}
          
          <img src="/frontend/{{$logo}}/LOGO.png?0.1">
        </a>
      </div>
      <nav class="main-nav">
        <ul class="list-inline">
          <li ng-repeat="navItem in mainNav" class="ng-scope" >
            <a href="javascript:;" onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif
                    setTab('main-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)' ,'')">
              <p class="classA {{navItem.classA ng-binding">마이월렛</p>
              <p class="classB {{navItem.classB ng-binding">My Wallet</p>
            </a>
          </li>
          <li ng-repeat="navItem in mainNav" class="ng-scope">
            <a href="javascript:;" onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif
                  setTab('deposit-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(2)','');mydepositlist();">
              <p class="classA {{navItem.classA ng-binding">입금신청</p>
              <p class="classB {{navItem.classB ng-binding">Deposit</p>
            </a>
          </li><!-- end ngRepeat: navItem in mainNav --><li ng-repeat="navItem in mainNav" class="ng-scope">
            <a href="javascript:;" onclick="
                  @auth
                    navClick('page-popup');
                  @else
                    navClick('login-popup');
                  @endif

                  setTab('withdraw-set','#page-popup &gt; div.ngdialog-content &gt; div.ngdialog-wallet-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(3)','');mywithdrawlist();">
              <p class="classA {{navItem.classA ng-binding">출금신청</p>
              <p class="classB {{navItem.classB ng-binding">Withdraw</p>
            </a>
          </li><!-- end ngRepeat: navItem in mainNav --><li ng-repeat="navItem in mainNav" class="ng-scope">
            <a href="javascript:;" onclick="
                  @auth
                    navClick('msg-popup');
                  @else
                    navClick('login-popup');
                  @endif
                  setTab('notice-set','#msg-popup > div.ngdialog-content > div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope > ul > li:nth-child(1)','고객센터');">
              <p class="classA {{navItem.classA ng-binding">고객센터</p>
              <p class="classB {{navItem.classB ng-binding">Customer</p>
            </a>
          </li><!-- end ngRepeat: navItem in mainNav -->
        </ul>
      </nav>
    </div>
  </div>
</header>
</header-nav-page>