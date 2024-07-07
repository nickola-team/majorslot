<transaction-page>
    <div class="transaction-page">
        <div class="transaction-container">
        <table class="transaction-table">
            <tbody>
            <tr>
                <th colspan="3">
                <div class="table-heading">
                    <span>공지사항</span>

                    <span class="pull-right" onclick="navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)');">전체 보기</span>

                </div>
                </th>
            </tr>
            @foreach ($noticelist as $ntc)
                <tr role="button" class="ng-scope">
                    <td class="width30" style="width: 134px;">
                    <span class="ng-binding">{{date('Y-m-d', strtotime($ntc->date_time))}}</span>&nbsp;
                    </td>
                    <td class="width70 click-disable text-white text-left">
                    <span onclick="navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)');getNotice('notice{{$ntc->id}}');" class="ng-binding">{{$ntc->title}}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="transaction-table">
            <div class="table-heading" style="text-align: left;display: block;">
            <span class="transaction-title active">실시간 입금 현황</span>
            </div>
            <ul class="transaction-list ng-scope" id="deposit-ticker"></ul>

        </div>
        <div class="transaction-table">
            <div class="table-heading">
            <span class="transaction-title">실시간 출금 현황</span>
            </div>
            <ul class="transaction-list" id="withdraw-ticker"></ul>
        </div>
        <div class="clearfix"></div>
        </div>
    </div>
    </transaction-page>