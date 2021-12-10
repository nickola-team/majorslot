<!DOCTYPE html>
<html lang="ko">

<head>
    <title>
        게임 내역
    </title>
    <link rel="shortcut icon" href="/images/favicon.png" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=0.8" />
    <script src="./lang/history.js?l=ko"></script>

    <link href="./bundles/styles/siteCssHistory.css?v=IzDC922W8wfB3jpJJ9mvEI-2cDaui_e-rRH-ApjZxSw1" rel="stylesheet" />

    <script src="./bundles/scripts/siteJsHistoryTelerik.js?v=wryz6CwFFdv-yabVHvjbpES7eR-r7h-hFgMG-Z5LviY1"></script>

    <link
        href="./WebResource.css"
        type="text/css" rel="stylesheet" class="Telerik_stylesheet" />
</head>

<body>

    <script>
        var locale = ('ko');
        var url = '/hbn/history';
        var filterMaxDays = 90;
        var currencyCode = 'KRW';
        var appCBID = '5.1.9611.1174';
        var jurisId = 0;
        var hideBaseInfo = 0;
        var eventIndex = -1
        var viewType = 'client';

        initLocals({
            locale: locale,
            url: url,
            filterMaxDays: filterMaxDays,
            currencyCode: currencyCode,
            appCBID: appCBID,
            jurisId: jurisId,
            viewType: viewType,
            hideBaseInfo: hideBaseInfo,
            eventIndex: eventIndex
        });



        var loaderFilter = jQuery('<div id="loadercont"><div id="loader"></div></div>').css({
            position: "absolute",
            top: "25%",
            left: "50%",
            "margin-left": "-32px",
            "z-index": "1"
        }).appendTo("body").hide();

        function loadGame(sender, args) {

            var extRoundId = args.getDataKeyValue("ExtRoundId");

            var isTestSite = args.getDataKeyValue("IsTestSite");

            var useHabaneroGameDetails = $("#useHbGameInfo").val();
            if (useHabaneroGameDetails == "1") {
                extRoundId = "";
            }

            if (extRoundId != null && extRoundId.length > 0) {
                getGameDetails(args.getDataKeyValue("GameInstanceId"), true, jurisId, appCBID, isTestSite)
            } else {
                getGameDetails(args.getDataKeyValue("GameInstanceId"), false, jurisId, appCBID, isTestSite)
            }

            $('#GameInstanceContent').hide();
            $('.dateSelect').hide();
        }

        function showCompletedGrid(sender) {
            $('#completedPanel').show();
            loaderFilter.hide();
            postMessageLoadComplete();
        }

        var loadComplete = false;

        function postMessageLoadComplete() {
            if (loadComplete == false) {
                var target = window.parent;
                if (!target) {
                    target = window;
                }
                target.postMessage("notify:load.complete", "*");
                loadComplete = true; // do this since if the table is empty, it does 3 calls
            }
        }

        function requestFailed(sender, args) {
            console.log('req failed');
        }

        function grdRowDataBound(sender, args) {
            if (args.get_item().get_cell("GameKeyName") != null) {
                args.get_item().get_cell("GameKeyName").innerHTML = "<span class='gn'>" + translate("GAMENAME_" + args
                    .get_dataItem()["GameKeyName"], false, args.get_dataItem()["GameKeyName"]) + "</span>";
            }
            if (args.get_item().get_cell("DateToShow") != null) {
                args.get_item().get_cell("DateToShow").innerText = formatDate(args.get_dataItem()["DateToShow"]);
            }
            if (args.get_item().get_cell("RealStake") != null) {
                args.get_item().get_cell("RealStake").innerText = formatMoney(args.get_dataItem()["RealStake"], args
                    .get_dataItem()["Exponent"]);
            }
            if (args.get_item().get_cell("RealPayout") != null) {
                args.get_item().get_cell("RealPayout").innerText = formatMoney(args.get_dataItem()["RealPayout"], args
                    .get_dataItem()["Exponent"]);
            }
        }

        function onDataParse(sender, args) {
            var response = args.get_response().d;
            if (response != undefined) {
                if (response.olaperror != undefined) {
                    olaperror = 1;
                    console.log("Olap Error");
                    showOlapError();
                    args.set_dataField([]);
                } else {
                    if (response.length > 0) {
                        args.set_dataField(response);
                    } else {
                        args.set_dataField([]);
                    }
                }
            }
        }

        function showOlapError() {
            $("#lblOlapCont").addClass("show");
        }

    </script>



    <style id="generalStyle">
        #lblOlapCont {
            background: #00000054;
            text-align: center;
            padding: 10px;
            position: absolute;
            TOP: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1;
            flex-direction: column;
            justify-content: center;
            display: none;
        }

        #lblOlapCont.show {
            display: flex;
        }

        #olapMessage {
            display: block;
            background: white;
            padding: 10px;
            flex: 0;
            align-items: center;
            align-content: center;
            font-size: 14px;
            line-height: 20px;
        }

        .slotgrid th {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .RadCalendarTimeView_Bootstrap th {
            display: none;
        }

        #grdGameCompleted {
            overflow-x: hidden;
            /*do not change - must not horizontal scroll*/
            width: 100% !important;
        }

        .RadGrid .rgHeader:first-child {
            border-left: 0;
        }

        .RadGrid .t-font-icon:before {
            font-size: 17px;
        }

        .rgMasterTable tbody tr:hover {
            cursor: pointer;
        }

        .filter_controls {
            padding: 10px 5px;
            font-size: 12px
        }

        .hide {
            display: none;
        }

        .btns {
            display: block
        }

        .btns .btn {
            font-size: 12px
        }

        .dateSelect {
            display: none
        }

        .dateSelect .dtFrom,
        .dateSelect .dtTo {
            margin-bottom: 5px;
        }

        #extGameInfo {
            flex-grow: 1;
            display: flex;
        }

        /* mobile content*/
        @media all and (max-width: 330px) {

            td,
            th {
                font-size: 10px !important;
            }

            .gn {
                width: 50px !important;
            }
        }

        /* mobile content*/
        @media all and (max-width: 670px) and (min-height: 320px) {

            td,
            th {
                padding: 4px 4px !important;
                font-size: 11px;
            }

            .gn {
                width: 80px;
                display: block;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .RadGrid {
                line-height: 20px !important;
            }

            /* hide date column content mobile*/
            .rgMasterTable td:nth-child(5) {
                display: none;
            }

            .rgMasterTable th:nth-child(5) {
                display: none;
            }

            .btns {
                margin-left: 0
            }

            .filter_controls label {
                min-width: 45px
            }

            .ac .ac-trigger::after {
                right: 10px;
            }
        }

    </style>


    <style>
        .filter_controls {
            padding: 10px 0
        }

        .RadGrid .rgIcon {
            color: white
        }

        #loader {
            background-image: url('./images/spinner.png');
            width: 64px;
            height: 64px;
            background-size: cover;
            -webkit-animation: spin 2s linear infinite;
            -moz-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-moz-keyframes spin {
            100% {
                -moz-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }


        body {
            color: #fff;
            background-color: #131313;
        }

        .RadGrid .rgIcon {
            color: #333
        }

        .RadGrid .rgPagerCell {
            padding: 0px;
        }

        .RadGrid .rgPagerCell .NextPrevAndNumeric {
            padding: 10px;
        }

        .RadGrid_Bootstrap .rgPagerCell>div {
            border-top: none;
        }

        .RadGrid_Bootstrap {
            color: #efefef !important;
            background-color: transparent !important;
            border-radius: 0px !important;
        }

        .RadGrid_Bootstrap .rgSorted {
            background-color: transparent !important;
        }

        .rgHeader,
        .rgHeader a,
        .rgFooter,
        .rgFooter a,
        .rgPagerCell>div,
        .filter_controls {
            color: #efefef !important;
            background-color: #232020 !important;
        }

        .rgCurrentPage,
        .btnFilter {
            background-color: #808080 !important;
            border-color: #808080 !important;
        }

        .rgAltRow .rgSorted {
            background-color: #232020 !important;
            color: white !important;
        }

        .rgAltRow td {
            background-color: #232020 !important;
            color: white !important;
        }

        .filter_controls label {
            color: #efefef !important;
        }

        @media all and (max-width: 670px) and (min-height: 320px) {
            /*
        #outerWrapper {
          min-width: auto;
          width: 100%;
        }
        */

            #GameDetailsContainer table {
                width: 100% !important;
            }

            .leftcol,
            .rightcol {
                display: block;
            }

            .rightcol {
                margin-top: 10px;
            }
        }

    </style>


    <form method="post"
        action="./?brandid={{Request::get('brandid')}}&token={{Request::get('token')}}&locale=ko"
        id="form1">
        <div class="aspNetHidden">
            <input type="hidden" name="rsm_TSM" id="rsm_TSM" value="" />
            <input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
            <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
            <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
                value="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" />
        </div>

        <script type="text/javascript">
            //<![CDATA[
            var theForm = document.forms['form1'];
            if (!theForm) {
                theForm = document.form1;
            }

            function __doPostBack(eventTarget, eventArgument) {
                if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
                    theForm.__EVENTTARGET.value = eventTarget;
                    theForm.__EVENTARGUMENT.value = eventArgument;
                    theForm.submit();
                }
            }
            //]]>

        </script>


        <script
            src="./WebResource.js"
            type="text/javascript"></script>


        <script
            src="./Telerik.Web.UI.js"
            type="text/javascript"></script>
        <div class="aspNetHidden">

            <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="A98E9E20" />
        </div>
        <script type="text/javascript">
            //<![CDATA[
            Sys.WebForms.PageRequestManager._initialize('rsm', 'form1', [], [], [], 90, '');
            //]]>

        </script>

        <div id="outerWrapper">
            <div id="content">
                <div class="dateSelect">
                    <div class="filter_controls">
                        <div>
                            <div id="dtFrom_wrapper" class="RadPicker RadDateTimePicker RadPicker_Bootstrap dtFrom"
                                style="width:200px;">
                                <!-- 2020.3.1021.45 --><input
                                    style="visibility:hidden;display:block;float:right;margin:0 0 -1px -1px;width:1px;height:1px;overflow:hidden;border:0;padding:0;"
                                    id="dtFrom" name="dtFrom" type="text" class="rdfd_ radPreventDecorate" value=""
                                    title="Visually hidden input created for functionality purposes." />
                                <div id="dtFrom_dateInput_wrapper" class="RadInput RadInput_Bootstrap">
                                    <input id="dtFrom_dateInput" name="dtFrom$dateInput" class="riTextBox riRead"
                                        readonly="readonly" type="text" />
                                    <div class="rcSelect">
                                        <a title=" " href="#" id="dtFrom_popupButton" class="rcCalPopup"></a><a title=""
                                            href="#" id="dtFrom_timePopupLink" class="rcTimePopup"></a>
                                        <div id="dtFrom_calendar" class="RadCalendar RadCalendar_Bootstrap"
                                            style="display:none;">
                                            <div class="rcTitlebar">
                                                <a id="dtFrom_calendar_FNP" class="t-button rcFastPrev" title="&lt;&lt;"
                                                    href="../#"><span
                                                        class="t-font-icon t-i-arrow-double-60-left"></span></a><a
                                                    id="dtFrom_calendar_NP" class="t-button rcPrev" title="&lt;"
                                                    href="../#"><span class="t-font-icon t-i-arrow-left"></span></a>
                                                <div class="rcNextButtons">
                                                    <a id="dtFrom_calendar_NN" class="t-button rcNext" title=">"
                                                        href="../#"><span
                                                            class="t-font-icon t-i-arrow-right"></span></a><a
                                                        id="dtFrom_calendar_FNN" class="t-button rcFastNext" title=">>"
                                                        href="../#"><span
                                                            class="t-font-icon t-i-arrow-double-60-right"></span></a>
                                                </div><span id="dtFrom_calendar_Title" class="rcTitle">2021년 12월</span>
                                            </div>
                                            <div class="rcMain">
                                                <table id="dtFrom_calendar_Top" class="rcMainTable">
                                                    <caption>
                                                        <span style='display:none;'>2021년 12월</span>
                                                    </caption>
                                                    <thead>
                                                        <tr class="rcWeek">
                                                            <th class="rcViewSel" scope="col">&nbsp;</th>
                                                            <th id="dtFrom_calendar_Top_cs_1" title="일요일" scope="col">일
                                                            </th>
                                                            <th id="dtFrom_calendar_Top_cs_2" title="월요일" scope="col">월
                                                            </th>
                                                            <th id="dtFrom_calendar_Top_cs_3" title="화요일" scope="col">화
                                                            </th>
                                                            <th id="dtFrom_calendar_Top_cs_4" title="수요일" scope="col">수
                                                            </th>
                                                            <th id="dtFrom_calendar_Top_cs_5" title="목요일" scope="col">목
                                                            </th>
                                                            <th id="dtFrom_calendar_Top_cs_6" title="금요일" scope="col">금
                                                            </th>
                                                            <th id="dtFrom_calendar_Top_cs_7" title="토요일" scope="col">토
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="rcRow">
                                                            <th id="dtFrom_calendar_Top_rs_1" scope="row">49</th>
                                                            <td class="rcOtherMonth" title="일요일, 11월 28, 2021"><a
                                                                    href="#">28</a></td>
                                                            <td class="rcOtherMonth" title="월요일, 11월 29, 2021"><a
                                                                    href="#">29</a></td>
                                                            <td class="rcOtherMonth" title="화요일, 11월 30, 2021"><a
                                                                    href="#">30</a></td>
                                                            <td title="수요일, 12월 01, 2021"><a href="#">1</a></td>
                                                            <td title="목요일, 12월 02, 2021"><a href="#">2</a></td>
                                                            <td class="rcToday rcToday" title="금요일, 12월 03, 2021"><a
                                                                    href="#">3</a></td>
                                                            <td class="rcWeekend" title="토요일, 12월 04, 2021"><a
                                                                    href="#">4</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtFrom_calendar_Top_rs_2" scope="row">50</th>
                                                            <td class="rcWeekend" title="일요일, 12월 05, 2021"><a
                                                                    href="#">5</a></td>
                                                            <td title="월요일, 12월 06, 2021"><a href="#">6</a></td>
                                                            <td title="화요일, 12월 07, 2021"><a href="#">7</a></td>
                                                            <td title="수요일, 12월 08, 2021"><a href="#">8</a></td>
                                                            <td title="목요일, 12월 09, 2021"><a href="#">9</a></td>
                                                            <td title="금요일, 12월 10, 2021"><a href="#">10</a></td>
                                                            <td class="rcWeekend" title="토요일, 12월 11, 2021"><a
                                                                    href="#">11</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtFrom_calendar_Top_rs_3" scope="row">51</th>
                                                            <td class="rcWeekend" title="일요일, 12월 12, 2021"><a
                                                                    href="#">12</a></td>
                                                            <td title="월요일, 12월 13, 2021"><a href="#">13</a></td>
                                                            <td title="화요일, 12월 14, 2021"><a href="#">14</a></td>
                                                            <td title="수요일, 12월 15, 2021"><a href="#">15</a></td>
                                                            <td title="목요일, 12월 16, 2021"><a href="#">16</a></td>
                                                            <td title="금요일, 12월 17, 2021"><a href="#">17</a></td>
                                                            <td class="rcWeekend" title="토요일, 12월 18, 2021"><a
                                                                    href="#">18</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtFrom_calendar_Top_rs_4" scope="row">52</th>
                                                            <td class="rcWeekend" title="일요일, 12월 19, 2021"><a
                                                                    href="#">19</a></td>
                                                            <td title="월요일, 12월 20, 2021"><a href="#">20</a></td>
                                                            <td title="화요일, 12월 21, 2021"><a href="#">21</a></td>
                                                            <td title="수요일, 12월 22, 2021"><a href="#">22</a></td>
                                                            <td title="목요일, 12월 23, 2021"><a href="#">23</a></td>
                                                            <td title="금요일, 12월 24, 2021"><a href="#">24</a></td>
                                                            <td class="rcWeekend" title="토요일, 12월 25, 2021"><a
                                                                    href="#">25</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtFrom_calendar_Top_rs_5" scope="row">53</th>
                                                            <td class="rcWeekend" title="일요일, 12월 26, 2021"><a
                                                                    href="#">26</a></td>
                                                            <td title="월요일, 12월 27, 2021"><a href="#">27</a></td>
                                                            <td title="화요일, 12월 28, 2021"><a href="#">28</a></td>
                                                            <td title="수요일, 12월 29, 2021"><a href="#">29</a></td>
                                                            <td title="목요일, 12월 30, 2021"><a href="#">30</a></td>
                                                            <td title="금요일, 12월 31, 2021"><a href="#">31</a></td>
                                                            <td class="rcOtherMonth" title="토요일, 1월 01, 2022"><a
                                                                    href="#">1</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtFrom_calendar_Top_rs_6" scope="row">2</th>
                                                            <td class="rcOtherMonth" title="일요일, 1월 02, 2022"><a
                                                                    href="#">2</a></td>
                                                            <td class="rcOtherMonth" title="월요일, 1월 03, 2022"><a
                                                                    href="#">3</a></td>
                                                            <td class="rcOtherMonth" title="화요일, 1월 04, 2022"><a
                                                                    href="#">4</a></td>
                                                            <td class="rcOtherMonth" title="수요일, 1월 05, 2022"><a
                                                                    href="#">5</a></td>
                                                            <td class="rcOtherMonth" title="목요일, 1월 06, 2022"><a
                                                                    href="#">6</a></td>
                                                            <td class="rcOtherMonth" title="금요일, 1월 07, 2022"><a
                                                                    href="#">7</a></td>
                                                            <td class="rcOtherMonth" title="토요일, 1월 08, 2022"><a
                                                                    href="#">8</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div><input type="hidden" name="dtFrom_calendar_SD" id="dtFrom_calendar_SD"
                                                value="[]" /><input type="hidden" name="dtFrom_calendar_AD"
                                                id="dtFrom_calendar_AD" value="[[1980,1,1],[2099,12,30],[2021,12,3]]" />
                                        </div>
                                        <div id="dtFrom_timeView_wrapper" style="display:none;">
                                            <div id="dtFrom_timeView">
                                                <table id="dtFrom_timeView_tdl"
                                                    class="RadCalendarTimeView RadCalendarTimeView_Bootstrap"
                                                    summary="Table holding time picker for selecting time of day."
                                                    cellspacing="0">
                                                    <caption>
                                                        <span style='display: none'>Time picker</span>
                                                    </caption>
                                                    <tr>
                                                        <th colspan="3" scope="col" class="rcHeader">Time Picker</th>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">00:00</a></td>
                                                        <td><a href="#">01:00</a></td>
                                                        <td><a href="#">02:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">03:00</a></td>
                                                        <td><a href="#">04:00</a></td>
                                                        <td><a href="#">05:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">06:00</a></td>
                                                        <td><a href="#">07:00</a></td>
                                                        <td><a href="#">08:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">09:00</a></td>
                                                        <td><a href="#">10:00</a></td>
                                                        <td><a href="#">11:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">12:00</a></td>
                                                        <td><a href="#">13:00</a></td>
                                                        <td><a href="#">14:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">15:00</a></td>
                                                        <td><a href="#">16:00</a></td>
                                                        <td><a href="#">17:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">18:00</a></td>
                                                        <td><a href="#">19:00</a></td>
                                                        <td><a href="#">20:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">21:00</a></td>
                                                        <td><a href="#">22:00</a></td>
                                                        <td><a href="#">23:00</a></td>
                                                    </tr>
                                                </table><input id="dtFrom_timeView_ClientState"
                                                    name="dtFrom_timeView_ClientState" type="hidden" />
                                            </div>
                                        </div>
                                    </div><input id="dtFrom_dateInput_ClientState" name="dtFrom_dateInput_ClientState"
                                        type="hidden" />
                                </div><input id="dtFrom_ClientState" name="dtFrom_ClientState" type="hidden" />
                            </div>
                        </div>
                        <div>
                            <div id="dtTo_wrapper" class="RadPicker RadDateTimePicker RadPicker_Bootstrap dtTo"
                                style="width:200px;">
                                <input
                                    style="visibility:hidden;display:block;float:right;margin:0 0 -1px -1px;width:1px;height:1px;overflow:hidden;border:0;padding:0;"
                                    id="dtTo" name="dtTo" type="text" class="rdfd_ radPreventDecorate" value=""
                                    title="Visually hidden input created for functionality purposes." />
                                <div id="dtTo_dateInput_wrapper" class="RadInput RadInput_Bootstrap">
                                    <input id="dtTo_dateInput" name="dtTo$dateInput" class="riTextBox riRead"
                                        readonly="readonly" type="text" />
                                    <div class="rcSelect">
                                        <a title=" " href="#" id="dtTo_popupButton" class="rcCalPopup"></a><a title=""
                                            href="#" id="dtTo_timePopupLink" class="rcTimePopup"></a>
                                        <div id="dtTo_calendar" class="RadCalendar RadCalendar_Bootstrap"
                                            style="display:none;">
                                            <div class="rcTitlebar">
                                                <a id="dtTo_calendar_FNP" class="t-button rcFastPrev" title="&lt;&lt;"
                                                    href="../#"><span
                                                        class="t-font-icon t-i-arrow-double-60-left"></span></a><a
                                                    id="dtTo_calendar_NP" class="t-button rcPrev" title="&lt;"
                                                    href="../#"><span class="t-font-icon t-i-arrow-left"></span></a>
                                                <div class="rcNextButtons">
                                                    <a id="dtTo_calendar_NN" class="t-button rcNext" title=">"
                                                        href="../#"><span
                                                            class="t-font-icon t-i-arrow-right"></span></a><a
                                                        id="dtTo_calendar_FNN" class="t-button rcFastNext" title=">>"
                                                        href="../#"><span
                                                            class="t-font-icon t-i-arrow-double-60-right"></span></a>
                                                </div><span id="dtTo_calendar_Title" class="rcTitle">2021년 12월</span>
                                            </div>
                                            <div class="rcMain">
                                                <table id="dtTo_calendar_Top" class="rcMainTable">
                                                    <caption>
                                                        <span style='display:none;'>2021년 12월</span>
                                                    </caption>
                                                    <thead>
                                                        <tr class="rcWeek">
                                                            <th class="rcViewSel" scope="col">&nbsp;</th>
                                                            <th id="dtTo_calendar_Top_cs_1" title="일요일" scope="col">일
                                                            </th>
                                                            <th id="dtTo_calendar_Top_cs_2" title="월요일" scope="col">월
                                                            </th>
                                                            <th id="dtTo_calendar_Top_cs_3" title="화요일" scope="col">화
                                                            </th>
                                                            <th id="dtTo_calendar_Top_cs_4" title="수요일" scope="col">수
                                                            </th>
                                                            <th id="dtTo_calendar_Top_cs_5" title="목요일" scope="col">목
                                                            </th>
                                                            <th id="dtTo_calendar_Top_cs_6" title="금요일" scope="col">금
                                                            </th>
                                                            <th id="dtTo_calendar_Top_cs_7" title="토요일" scope="col">토
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="rcRow">
                                                            <th id="dtTo_calendar_Top_rs_1" scope="row">49</th>
                                                            <td class="rcOtherMonth" title="일요일, 11월 28, 2021"><a
                                                                    href="#">28</a></td>
                                                            <td class="rcOtherMonth" title="월요일, 11월 29, 2021"><a
                                                                    href="#">29</a></td>
                                                            <td class="rcOtherMonth" title="화요일, 11월 30, 2021"><a
                                                                    href="#">30</a></td>
                                                            <td title="수요일, 12월 01, 2021"><a href="#">1</a></td>
                                                            <td title="목요일, 12월 02, 2021"><a href="#">2</a></td>
                                                            <td title="금요일, 12월 03, 2021"><a href="#">3</a></td>
                                                            <td class="rcWeekend" title="토요일, 12월 04, 2021"><a
                                                                    href="#">4</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtTo_calendar_Top_rs_2" scope="row">50</th>
                                                            <td class="rcWeekend" title="일요일, 12월 05, 2021"><a
                                                                    href="#">5</a></td>
                                                            <td title="월요일, 12월 06, 2021"><a href="#">6</a></td>
                                                            <td title="화요일, 12월 07, 2021"><a href="#">7</a></td>
                                                            <td title="수요일, 12월 08, 2021"><a href="#">8</a></td>
                                                            <td title="목요일, 12월 09, 2021"><a href="#">9</a></td>
                                                            <td title="금요일, 12월 10, 2021"><a href="#">10</a></td>
                                                            <td class="rcWeekend" title="토요일, 12월 11, 2021"><a
                                                                    href="#">11</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtTo_calendar_Top_rs_3" scope="row">51</th>
                                                            <td class="rcWeekend" title="일요일, 12월 12, 2021"><a
                                                                    href="#">12</a></td>
                                                            <td title="월요일, 12월 13, 2021"><a href="#">13</a></td>
                                                            <td title="화요일, 12월 14, 2021"><a href="#">14</a></td>
                                                            <td title="수요일, 12월 15, 2021"><a href="#">15</a></td>
                                                            <td title="목요일, 12월 16, 2021"><a href="#">16</a></td>
                                                            <td title="금요일, 12월 17, 2021"><a href="#">17</a></td>
                                                            <td class="rcWeekend" title="토요일, 12월 18, 2021"><a
                                                                    href="#">18</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtTo_calendar_Top_rs_4" scope="row">52</th>
                                                            <td class="rcWeekend" title="일요일, 12월 19, 2021"><a
                                                                    href="#">19</a></td>
                                                            <td title="월요일, 12월 20, 2021"><a href="#">20</a></td>
                                                            <td title="화요일, 12월 21, 2021"><a href="#">21</a></td>
                                                            <td title="수요일, 12월 22, 2021"><a href="#">22</a></td>
                                                            <td title="목요일, 12월 23, 2021"><a href="#">23</a></td>
                                                            <td title="금요일, 12월 24, 2021"><a href="#">24</a></td>
                                                            <td class="rcWeekend" title="토요일, 12월 25, 2021"><a
                                                                    href="#">25</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtTo_calendar_Top_rs_5" scope="row">53</th>
                                                            <td class="rcWeekend" title="일요일, 12월 26, 2021"><a
                                                                    href="#">26</a></td>
                                                            <td title="월요일, 12월 27, 2021"><a href="#">27</a></td>
                                                            <td title="화요일, 12월 28, 2021"><a href="#">28</a></td>
                                                            <td title="수요일, 12월 29, 2021"><a href="#">29</a></td>
                                                            <td title="목요일, 12월 30, 2021"><a href="#">30</a></td>
                                                            <td title="금요일, 12월 31, 2021"><a href="#">31</a></td>
                                                            <td class="rcOtherMonth" title="토요일, 1월 01, 2022"><a
                                                                    href="#">1</a></td>
                                                        </tr>
                                                        <tr class="rcRow">
                                                            <th id="dtTo_calendar_Top_rs_6" scope="row">2</th>
                                                            <td class="rcOtherMonth" title="일요일, 1월 02, 2022"><a
                                                                    href="#">2</a></td>
                                                            <td class="rcOtherMonth" title="월요일, 1월 03, 2022"><a
                                                                    href="#">3</a></td>
                                                            <td class="rcOtherMonth" title="화요일, 1월 04, 2022"><a
                                                                    href="#">4</a></td>
                                                            <td class="rcOtherMonth" title="수요일, 1월 05, 2022"><a
                                                                    href="#">5</a></td>
                                                            <td class="rcOtherMonth" title="목요일, 1월 06, 2022"><a
                                                                    href="#">6</a></td>
                                                            <td class="rcOtherMonth" title="금요일, 1월 07, 2022"><a
                                                                    href="#">7</a></td>
                                                            <td class="rcOtherMonth" title="토요일, 1월 08, 2022"><a
                                                                    href="#">8</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div><input type="hidden" name="dtTo_calendar_SD" id="dtTo_calendar_SD"
                                                value="[]" /><input type="hidden" name="dtTo_calendar_AD"
                                                id="dtTo_calendar_AD" value="[[1980,1,1],[2099,12,30],[2021,12,3]]" />
                                        </div>
                                        <div id="dtTo_timeView_wrapper" style="display:none;">
                                            <div id="dtTo_timeView">
                                                <table id="dtTo_timeView_tdl"
                                                    class="RadCalendarTimeView RadCalendarTimeView_Bootstrap"
                                                    summary="Table holding time picker for selecting time of day."
                                                    cellspacing="0">
                                                    <caption>
                                                        <span style='display: none'>Time picker</span>
                                                    </caption>
                                                    <tr>
                                                        <th colspan="3" scope="col" class="rcHeader">Time Picker</th>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">00:00</a></td>
                                                        <td><a href="#">01:00</a></td>
                                                        <td><a href="#">02:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">03:00</a></td>
                                                        <td><a href="#">04:00</a></td>
                                                        <td><a href="#">05:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">06:00</a></td>
                                                        <td><a href="#">07:00</a></td>
                                                        <td><a href="#">08:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">09:00</a></td>
                                                        <td><a href="#">10:00</a></td>
                                                        <td><a href="#">11:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">12:00</a></td>
                                                        <td><a href="#">13:00</a></td>
                                                        <td><a href="#">14:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">15:00</a></td>
                                                        <td><a href="#">16:00</a></td>
                                                        <td><a href="#">17:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">18:00</a></td>
                                                        <td><a href="#">19:00</a></td>
                                                        <td><a href="#">20:00</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">21:00</a></td>
                                                        <td><a href="#">22:00</a></td>
                                                        <td><a href="#">23:00</a></td>
                                                    </tr>
                                                </table><input id="dtTo_timeView_ClientState"
                                                    name="dtTo_timeView_ClientState" type="hidden" />
                                            </div>
                                        </div>
                                    </div><input id="dtTo_dateInput_ClientState" name="dtTo_dateInput_ClientState"
                                        type="hidden" />
                                </div><input id="dtTo_ClientState" name="dtTo_ClientState" type="hidden" />
                            </div>
                        </div>
                        <input type="hidden" name="hdTimeZone" id="hdTimeZone" />
                        <div class="btns">
                            <input type="button" id="btnFilter" value="보기" class="btn btn-primary btnFilter"
                                onclick="rebindGrid();" />
                        </div>
                    </div>
                </div>

                <div id="divBackToLists" style="padding-bottom: 10px; padding-top: 5px;">
                    <span class="btn btn-default hide" id="btnShowGameInstanceList" onclick="BackToList()">&laquo; 목록으로
                        돌아가기</span>
                </div>

                <div id="btnClose" style="display: none; right: 10px; position: absolute; padding-top: 5px;">
                    <input type="button" onclick="parent.closeDialog();" value="X" />
                </div>
                <div id="lblOlapCont">
                    <span id="olapMessage" class="warn-color">
                        <span class="olapMsg">Reporting Delay. Please try again in a few minutes or contact support if
                            problem persists</span>
                    </span>
                </div>
                <div id="GameInstanceContent">
                    <div id="completedPanel" class="hide">
                        <span id="csDS" style="display:none;"></span>

                        <div id="grdGameCompleted" class="RadGrid RadGrid_Bootstrap" style="width:100%;">

                            <table class="rgMasterTable" id="grdGameCompleted_ctl00"
                                style="width:100%;table-layout:auto;empty-cells:show;">
                                <colgroup>
                                    <col />
                                    <col />
                                    <col />
                                    <col />
                                    <col />
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th scope="col" class="rgHeader" style="font-weight:bold;"><a
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;GameKeyName&#39;); return false;"
                                                href="javascript:__doPostBack(&#39;grdGameCompleted$ctl00$ctl02$ctl01$ctl04&#39;,&#39;&#39;)">게임</a><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl05"
                                                value="Sorted asc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;GameKeyName&#39;); return false;"
                                                title="Sorted asc" class="t-button rgActionButton rgSortAsc"
                                                id="grdGameCompleted_ctl00__GameKeyName__SortAsc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortAscIcon"></span></button><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl06"
                                                value="Sorted desc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;GameKeyName&#39;); return false;"
                                                title="Sorted desc" class="t-button rgActionButton rgSortDesc"
                                                id="grdGameCompleted_ctl00__GameKeyName__SortDesc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortDescIcon"></span></button></th>
                                        <th scope="col" class="rgHeader" style="font-weight:bold;"><a
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;FriendlyId&#39;); return false;"
                                                href="javascript:__doPostBack(&#39;grdGameCompleted$ctl00$ctl02$ctl01$ctl07&#39;,&#39;&#39;)">nº</a><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl08"
                                                value="Sorted asc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;FriendlyId&#39;); return false;"
                                                title="Sorted asc" class="t-button rgActionButton rgSortAsc"
                                                id="grdGameCompleted_ctl00__FriendlyId__SortAsc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortAscIcon"></span></button><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl09"
                                                value="Sorted desc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;FriendlyId&#39;); return false;"
                                                title="Sorted desc" class="t-button rgActionButton rgSortDesc"
                                                id="grdGameCompleted_ctl00__FriendlyId__SortDesc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortDescIcon"></span></button></th>
                                        <th scope="col" class="rgHeader" style="font-weight:bold;"><a
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;RealStake&#39;); return false;"
                                                href="javascript:__doPostBack(&#39;grdGameCompleted$ctl00$ctl02$ctl01$ctl10&#39;,&#39;&#39;)">스테이크</a><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl11"
                                                value="Sorted asc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;RealStake&#39;); return false;"
                                                title="Sorted asc" class="t-button rgActionButton rgSortAsc"
                                                id="grdGameCompleted_ctl00__RealStake__SortAsc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortAscIcon"></span></button><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl12"
                                                value="Sorted desc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;RealStake&#39;); return false;"
                                                title="Sorted desc" class="t-button rgActionButton rgSortDesc"
                                                id="grdGameCompleted_ctl00__RealStake__SortDesc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortDescIcon"></span></button></th>
                                        <th scope="col" class="rgHeader" style="font-weight:bold;"><a
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;RealPayout&#39;); return false;"
                                                href="javascript:__doPostBack(&#39;grdGameCompleted$ctl00$ctl02$ctl01$ctl13&#39;,&#39;&#39;)">지불</a><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl14"
                                                value="Sorted asc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;RealPayout&#39;); return false;"
                                                title="Sorted asc" class="t-button rgActionButton rgSortAsc"
                                                id="grdGameCompleted_ctl00__RealPayout__SortAsc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortAscIcon"></span></button><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl15"
                                                value="Sorted desc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;RealPayout&#39;); return false;"
                                                title="Sorted desc" class="t-button rgActionButton rgSortDesc"
                                                id="grdGameCompleted_ctl00__RealPayout__SortDesc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortDescIcon"></span></button></th>
                                        <th scope="col" class="rgHeader" style="font-weight:bold;"><a
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;DateToShow&#39;); return false;"
                                                href="javascript:__doPostBack(&#39;grdGameCompleted$ctl00$ctl02$ctl01$ctl16&#39;,&#39;&#39;)">완료일</a><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl17"
                                                value="Sorted asc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;DateToShow&#39;); return false;"
                                                title="Sorted asc" class="t-button rgActionButton rgSortAsc"
                                                id="grdGameCompleted_ctl00__DateToShow__SortAsc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortAscIcon"></span></button><button
                                                type="submit" name="grdGameCompleted$ctl00$ctl02$ctl01$ctl18"
                                                value="Sorted desc"
                                                onclick="Telerik.Web.UI.Grid.Sort($find(&#39;grdGameCompleted_ctl00&#39;), &#39;DateToShow&#39;); return false;"
                                                title="Sorted desc" class="t-button rgActionButton rgSortDesc"
                                                id="grdGameCompleted_ctl00__DateToShow__SortDesc"
                                                style="display:none;"><span
                                                    class="t-font-icon rgIcon rgSortDescIcon"></span></button></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="rgPager">
                                        <td class="rgPagerCell" colspan="5">
                                            <div class="NextPrevAndNumeric">
                                                <div class="rgWrap rgArrPart1">
                                                    <button type="submit"
                                                        name="grdGameCompleted$ctl00$ctl03$ctl01$ctl02" value=" "
                                                        onclick="Telerik.Web.UI.Grid.NavigateToPage(&#39;grdGameCompleted_ctl00&#39;, &#39;First&#39;); return false;"
                                                        class="t-button rgActionButton rgPageFirst"><span
                                                            class="t-font-icon rgIcon"></span></button><button
                                                        type="submit" name="grdGameCompleted$ctl00$ctl03$ctl01$ctl03"
                                                        value=" "
                                                        onclick="Telerik.Web.UI.Grid.NavigateToPage(&#39;grdGameCompleted_ctl00&#39;, &#39;Prev&#39;); return false;"
                                                        class="t-button rgActionButton rgPagePrev"><span
                                                            class="t-font-icon rgIcon"></span></button>
                                                </div>
                                                <div class="rgWrap rgNumPart">
                                                    <div id="grdGameCompleted_ctl00NPPH"><a
                                                            onclick="Telerik.Web.UI.Grid.NavigateToPage(&#39;grdGameCompleted_ctl00&#39;, &#39;1&#39;); return false;"
                                                            class="rgCurrentPage"
                                                            href="javascript:__doPostBack(&#39;grdGameCompleted$ctl00$ctl03$ctl01$ctl05&#39;,&#39;&#39;)">1</a><a
                                                            onclick="Telerik.Web.UI.Grid.NavigateToPage(&#39;grdGameCompleted_ctl00&#39;, &#39;2&#39;); return false;"
                                                            href="javascript:__doPostBack(&#39;grdGameCompleted$ctl00$ctl03$ctl01$ctl06&#39;,&#39;&#39;)">2</a>
                                                    </div>
                                                </div>
                                                <div class="rgWrap rgArrPart2">
                                                    <button type="button"
                                                        name="grdGameCompleted$ctl00$ctl03$ctl01$ctl08" value=" "
                                                        onclick="Telerik.Web.UI.Grid.NavigateToPage(&#39;grdGameCompleted_ctl00&#39;, &#39;Next&#39;); return false;__doPostBack(&#39;grdGameCompleted$ctl00$ctl03$ctl01$ctl08&#39;,&#39;&#39;)"
                                                        class="t-button rgActionButton rgPageNext"><span
                                                            class="t-font-icon rgIcon"></span></button><button
                                                        type="button" name="grdGameCompleted$ctl00$ctl03$ctl01$ctl09"
                                                        value=" "
                                                        onclick="Telerik.Web.UI.Grid.NavigateToPage(&#39;grdGameCompleted_ctl00&#39;, &#39;Last&#39;); return false;__doPostBack(&#39;grdGameCompleted$ctl00$ctl03$ctl01$ctl09&#39;,&#39;&#39;)"
                                                        class="t-button rgActionButton rgPageLast"><span
                                                            class="t-font-icon rgIcon"></span></button>
                                                </div>
                                                <div class="rgWrap rgAdvPart">

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr class="rgNoRecords">
                                        <td colspan="5" style="text-align:left;">
                                            <div>화면에 표시할 기록이 없습니다.</div>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                            <div id="grdGameCompleted_SharedCalendarContainer" style="display:none;">

                            </div><input id="grdGameCompleted_ClientState" name="grdGameCompleted_ClientState"
                                type="hidden" />
                        </div>

                    </div>
                </div>
                <div id="GameDetailsContainer" style="display: none"></div>
                <div id="extGameInfoCss"></div>
                <div id="extGameInfo"></div>
                <div id="NuSlotHolder" class="slotBetDetails">
                    <div id="EventReelCanvas" style="position: absolute; z-index: 100; top: 0; left: 0;">
                    </div>
                    <div id="slotAccordion"></div>
                </div>
                <div class="slotAddtnlInfo">


                </div>
                <input type="hidden" name="bid" id="bid" value="{{Request::get('brandid')}}" />
                <input type="hidden" name="gid" id="gid" />
                <input type="hidden" name="ext" id="ext" />
                <input type="hidden" name="pid" id="pid" value="{{Request::get('token')}}" />
                <input type="hidden" name="bsu" id="bsu" value="0" />
                <input type="hidden" name="useHbGameInfo" id="useHbGameInfo" value="0" />



            </div>
        </div>


        <script type="text/javascript">
            //<![CDATA[
            window.__TsmHiddenField = $get('rsm_TSM');
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadCalendar, {
                    "_DayRenderChangedDays": {
                        "2021_12_3": ["", "rcToday rcToday"]
                    },
                    "_FormatInfoArray": [
                        ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
                        ["일", "월", "화", "수", "목", "금", "토"],
                        ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월",
                            ""
                        ],
                        ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", ""],
                        "yyyy\u0027년\u0027 M\u0027월\u0027 d\u0027일\u0027 dddd tt h:mm:ss",
                        "yyyy\u0027년\u0027 M\u0027월\u0027 d\u0027일\u0027 dddd", "tt h:mm:ss",
                        "M월 d일",
                        "ddd, dd MMM yyyy HH\u0027:\u0027mm\u0027:\u0027ss \u0027GMT\u0027",
                        "yyyy-MM-dd", "tt h:mm",
                        "yyyy\u0027-\u0027MM\u0027-\u0027dd\u0027T\u0027HH\u0027:\u0027mm\u0027:\u0027ss",
                        "yyyy\u0027-\u0027MM\u0027-\u0027dd HH\u0027:\u0027mm\u0027:\u0027ss\u0027Z\u0027",
                        "yyyy\u0027년\u0027 M\u0027월\u0027", "오전", "오후", "-", ":", 0
                    ],
                    "_ViewRepeatableDays": {
                        "2021_12_3": "0001_1_1"
                    },
                    "_ViewsHash": {
                        "dtFrom_calendar_Top": [
                            [2021, 12, 1], 1
                        ]
                    },
                    "_calendarWeekRule": 0,
                    "_culture": "ko",
                    "_enableKeyboardNavigation": false,
                    "_enableViewSelector": false,
                    "_firstDayOfWeek": 7,
                    "_postBackCall": "__doPostBack(\u0027dtFrom$calendar\u0027,\u0027@@\u0027)",
                    "_rangeSelectionMode": 0,
                    "_renderMode": 2,
                    "clientStateFieldID": "dtFrom_calendar_ClientState",
                    "enableMultiSelect": false,
                    "enabled": true,
                    "monthYearNavigationSettings": ["Today", "OK", "Cancel", "Date is out of range.",
                        "False", "True", "300", "1", "300", "1", "False"
                    ],
                    "skin": "Bootstrap",
                    "specialDaysArray": [
                        [, [2021, 12, 3], 1, 0, 0, 0, 16, 0, , {
                            "SpecialDayStyle_2021_12_3": ["", "rcToday"]
                        }]
                    ],
                    "stylesHash": {
                        "DayStyle": ["", ""],
                        "CalendarTableStyle": ["", "rcMainTable"],
                        "OtherMonthDayStyle": ["", "rcOtherMonth"],
                        "TitleStyle": ["", ""],
                        "SelectedDayStyle": ["", "rcSelected"],
                        "SelectorStyle": ["", ""],
                        "DisabledDayStyle": ["", "rcDisabled"],
                        "OutOfRangeDayStyle": ["", "rcOutOfRange"],
                        "WeekendDayStyle": ["", "rcWeekend"],
                        "DayOverStyle": ["", "rcHover"],
                        "FastNavigationStyle": ["",
                            "RadCalendarMonthView RadCalendarMonthView_Bootstrap"
                        ],
                        "ViewSelectorStyle": ["", "rcViewSel"]
                    },
                    "titleFormat": "yyyy\u0027년\u0027 M\u0027월\u0027",
                    "useColumnHeadersAsSelectors": false,
                    "useRowHeadersAsSelectors": false
                }, null, null, $get("dtFrom_calendar"));
            });
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadTimeView, {
                    "_ItemsCount": 24,
                    "_OwnerDatePickerID": "dtFrom",
                    "_TimeOverStyleCss": "rcHover",
                    "_culture": "ko",
                    "_enableKeyboardNavigation": false,
                    "_renderDirection": "Horizontal",
                    "_timeFormat": "HH:00",
                    "clientStateFieldID": "dtFrom_timeView_ClientState",
                    "itemStyles": {
                        "TimeStyle": ["", ""],
                        "AlternatingTimeStyle": ["", ""],
                        "HeaderStyle": ["", "rcHeader"],
                        "FooterStyle": ["", "rcFooter"],
                        "TimeOverStyle": ["", "rcHover"]
                    }
                }, null, null, $get("dtFrom_timeView"));
            });
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadDateInput, {
                    "_displayText": "",
                    "_focused": false,
                    "_initialValueAsText": "",
                    "_postBackEventReferenceScript": "__doPostBack(\u0027dtFrom\u0027,\u0027\u0027)",
                    "_renderMode": 2,
                    "_skin": "Bootstrap",
                    "_validationGroup": "fromGroup",
                    "_validationText": "",
                    "clientStateFieldID": "dtFrom_dateInput_ClientState",
                    "dateFormat": "dd MMM yyyy HH:mm",
                    "dateFormatInfo": {
                        "DayNames": ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
                        "MonthNames": ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월",
                            "11월", "12월", ""
                        ],
                        "AbbreviatedDayNames": ["일", "월", "화", "수", "목", "금", "토"],
                        "AbbreviatedMonthNames": ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10",
                            "11", "12", ""
                        ],
                        "AMDesignator": "오전",
                        "PMDesignator": "오후",
                        "DateSeparator": "-",
                        "TimeSeparator": ":",
                        "FirstDayOfWeek": 0,
                        "DateSlots": {
                            "Month": 1,
                            "Year": 2,
                            "Day": 0
                        },
                        "ShortYearCenturyEnd": 2029,
                        "TimeInputOnly": false,
                        "MonthYearOnly": false
                    },
                    "displayDateFormat": "yyyy-MM-dd tt h:mm",
                    "enabled": true,
                    "incrementSettings": {
                        InterceptArrowKeys: true,
                        InterceptMouseWheel: true,
                        Step: 1
                    },
                    "styles": {
                        HoveredStyle: ["", "riTextBox riHover"],
                        InvalidStyle: ["", "riTextBox riError"],
                        DisabledStyle: ["", "riTextBox riDisabled"],
                        FocusedStyle: ["", "riTextBox riFocused"],
                        EmptyMessageStyle: ["", "riTextBox riEmpty"],
                        ReadOnlyStyle: ["", "riTextBox riRead"],
                        EnabledStyle: ["", "riTextBox riEnabled"]
                    }
                }, null, null, $get("dtFrom_dateInput"));
            });
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadDateTimePicker, {
                    "_PopupButtonSettings": {
                        ResolvedImageUrl: "",
                        ResolvedHoverImageUrl: ""
                    },
                    "_TimePopupButtonSettings": {
                        ResolvedImageUrl: "",
                        ResolvedHoverImageUrl: ""
                    },
                    "_animationSettings": {
                        ShowAnimationDuration: 300,
                        ShowAnimationType: 1,
                        HideAnimationDuration: 300,
                        HideAnimationType: 1
                    },
                    "_enableKeyboardNavigation": false,
                    "_popupControlID": "dtFrom_popupButton",
                    "_renderMode": 2,
                    "_timePopupControlID": "dtFrom_timePopupLink",
                    "clientStateFieldID": "dtFrom_ClientState",
                    "focusedDate": "2021-12-03-00-00-00"
                }, {
                    "dateSelected": dtFromDS,
                    "popupOpening": setMinDate
                }, {
                    "calendar": "dtFrom_calendar",
                    "dateInput": "dtFrom_dateInput",
                    "timeView": "dtFrom_timeView"
                }, $get("dtFrom"));
            });
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadCalendar, {
                    "_DayRenderChangedDays": {},
                    "_FormatInfoArray": [
                        ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
                        ["일", "월", "화", "수", "목", "금", "토"],
                        ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월",
                            ""
                        ],
                        ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", ""],
                        "yyyy\u0027년\u0027 M\u0027월\u0027 d\u0027일\u0027 dddd tt h:mm:ss",
                        "yyyy\u0027년\u0027 M\u0027월\u0027 d\u0027일\u0027 dddd", "tt h:mm:ss",
                        "M월 d일",
                        "ddd, dd MMM yyyy HH\u0027:\u0027mm\u0027:\u0027ss \u0027GMT\u0027",
                        "yyyy-MM-dd", "tt h:mm",
                        "yyyy\u0027-\u0027MM\u0027-\u0027dd\u0027T\u0027HH\u0027:\u0027mm\u0027:\u0027ss",
                        "yyyy\u0027-\u0027MM\u0027-\u0027dd HH\u0027:\u0027mm\u0027:\u0027ss\u0027Z\u0027",
                        "yyyy\u0027년\u0027 M\u0027월\u0027", "오전", "오후", "-", ":", 0
                    ],
                    "_ViewRepeatableDays": {},
                    "_ViewsHash": {
                        "dtTo_calendar_Top": [
                            [2021, 12, 1], 1
                        ]
                    },
                    "_calendarWeekRule": 0,
                    "_culture": "ko",
                    "_enableKeyboardNavigation": false,
                    "_enableViewSelector": false,
                    "_firstDayOfWeek": 7,
                    "_postBackCall": "__doPostBack(\u0027dtTo$calendar\u0027,\u0027@@\u0027)",
                    "_rangeSelectionMode": 0,
                    "_renderMode": 2,
                    "clientStateFieldID": "dtTo_calendar_ClientState",
                    "enableMultiSelect": false,
                    "enabled": true,
                    "monthYearNavigationSettings": ["Today", "OK", "Cancel", "Date is out of range.",
                        "False", "True", "300", "1", "300", "1", "False"
                    ],
                    "skin": "Bootstrap",
                    "specialDaysArray": [],
                    "stylesHash": {
                        "DayStyle": ["", ""],
                        "CalendarTableStyle": ["", "rcMainTable"],
                        "OtherMonthDayStyle": ["", "rcOtherMonth"],
                        "TitleStyle": ["", ""],
                        "SelectedDayStyle": ["", "rcSelected"],
                        "SelectorStyle": ["", ""],
                        "DisabledDayStyle": ["", "rcDisabled"],
                        "OutOfRangeDayStyle": ["", "rcOutOfRange"],
                        "WeekendDayStyle": ["", "rcWeekend"],
                        "DayOverStyle": ["", "rcHover"],
                        "FastNavigationStyle": ["",
                            "RadCalendarMonthView RadCalendarMonthView_Bootstrap"
                        ],
                        "ViewSelectorStyle": ["", "rcViewSel"]
                    },
                    "titleFormat": "yyyy\u0027년\u0027 M\u0027월\u0027",
                    "useColumnHeadersAsSelectors": false,
                    "useRowHeadersAsSelectors": false
                }, null, null, $get("dtTo_calendar"));
            });
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadTimeView, {
                    "_ItemsCount": 24,
                    "_OwnerDatePickerID": "dtTo",
                    "_TimeOverStyleCss": "rcHover",
                    "_culture": "ko",
                    "_enableKeyboardNavigation": false,
                    "_renderDirection": "Horizontal",
                    "_timeFormat": "HH:00",
                    "clientStateFieldID": "dtTo_timeView_ClientState",
                    "itemStyles": {
                        "TimeStyle": ["", ""],
                        "AlternatingTimeStyle": ["", ""],
                        "HeaderStyle": ["", "rcHeader"],
                        "FooterStyle": ["", "rcFooter"],
                        "TimeOverStyle": ["", "rcHover"]
                    }
                }, null, null, $get("dtTo_timeView"));
            });
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadDateInput, {
                    "_displayText": "",
                    "_focused": false,
                    "_initialValueAsText": "",
                    "_postBackEventReferenceScript": "__doPostBack(\u0027dtTo\u0027,\u0027\u0027)",
                    "_renderMode": 2,
                    "_skin": "Bootstrap",
                    "_validationGroup": "toGroup",
                    "_validationText": "",
                    "clientStateFieldID": "dtTo_dateInput_ClientState",
                    "dateFormat": "dd MMM yyyy HH:mm",
                    "dateFormatInfo": {
                        "DayNames": ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
                        "MonthNames": ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월",
                            "11월", "12월", ""
                        ],
                        "AbbreviatedDayNames": ["일", "월", "화", "수", "목", "금", "토"],
                        "AbbreviatedMonthNames": ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10",
                            "11", "12", ""
                        ],
                        "AMDesignator": "오전",
                        "PMDesignator": "오후",
                        "DateSeparator": "-",
                        "TimeSeparator": ":",
                        "FirstDayOfWeek": 0,
                        "DateSlots": {
                            "Month": 1,
                            "Year": 2,
                            "Day": 0
                        },
                        "ShortYearCenturyEnd": 2029,
                        "TimeInputOnly": false,
                        "MonthYearOnly": false
                    },
                    "displayDateFormat": "yyyy-MM-dd tt h:mm",
                    "enabled": true,
                    "incrementSettings": {
                        InterceptArrowKeys: true,
                        InterceptMouseWheel: true,
                        Step: 1
                    },
                    "styles": {
                        HoveredStyle: ["", "riTextBox riHover"],
                        InvalidStyle: ["", "riTextBox riError"],
                        DisabledStyle: ["", "riTextBox riDisabled"],
                        FocusedStyle: ["", "riTextBox riFocused"],
                        EmptyMessageStyle: ["", "riTextBox riEmpty"],
                        ReadOnlyStyle: ["", "riTextBox riRead"],
                        EnabledStyle: ["", "riTextBox riEnabled"]
                    }
                }, null, null, $get("dtTo_dateInput"));
            });
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadDateTimePicker, {
                    "_PopupButtonSettings": {
                        ResolvedImageUrl: "",
                        ResolvedHoverImageUrl: ""
                    },
                    "_TimePopupButtonSettings": {
                        ResolvedImageUrl: "",
                        ResolvedHoverImageUrl: ""
                    },
                    "_animationSettings": {
                        ShowAnimationDuration: 300,
                        ShowAnimationType: 1,
                        HideAnimationDuration: 300,
                        HideAnimationType: 1
                    },
                    "_enableKeyboardNavigation": false,
                    "_popupControlID": "dtTo_popupButton",
                    "_renderMode": 2,
                    "_timePopupControlID": "dtTo_timePopupLink",
                    "clientStateFieldID": "dtTo_ClientState",
                    "focusedDate": "2021-12-03-00-00-00"
                }, {
                    "dateSelected": dtToDS,
                    "popupOpening": setMaxDate
                }, {
                    "calendar": "dtTo_calendar",
                    "dateInput": "dtTo_dateInput",
                    "timeView": "dtTo_timeView"
                }, $get("dtTo"));
            });

            var callBackFrameUrl =
                '/WebResource.axd?d=beToSAE3vdsL1QUQUxjWdSRN5hGTA-BFk21Hcgy1WcUp5lxYg66xtN7CiP5FewGc3hXpbOe3wWIQfp0Hf_UjLQ2&t=637454356754849868';
            WebForm_InitCallback();
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadClientDataSource, {
                    "_allowPaging": true,
                    "_id": "csDS",
                    "_pageSize": 8,
                    "_uniqueID": "csDS",
                    "dataSourceSettings": {},
                    "schema": {
                        "data": "d"
                    },
                    "transport": {
                        "read": {
                            "contentType": "application/json",
                            "url": "/hbn/history/GetHistory",
                            "type": "POST"
                        }
                    }
                }, {
                    "customParameter": ParameterMap,
                    "dataRequested": onDataParse,
                    "requestEnd": showCompletedGrid
                }, null, $get("csDS"));
            });
            Sys.Application.add_init(function () {
                $create(Telerik.Web.UI.RadGrid, {
                    "ClientID": "grdGameCompleted",
                    "ClientSettings": {
                        "AllowAutoScrollOnDragDrop": true,
                        "ShouldCreateRows": true,
                        "DataBinding": {},
                        "Selecting": {
                            "CellSelectionMode": 0
                        },
                        "Scrolling": {},
                        "Resizing": {},
                        "ClientMessages": {},
                        "KeyboardNavigationSettings": {
                            "AllowActiveRowCycle": false,
                            "EnableKeyboardShortcuts": true,
                            "FocusKey": 89,
                            "InitInsertKey": 73,
                            "RebindKey": 82,
                            "ExitEditInsertModeKey": 27,
                            "UpdateInsertItemKey": 13,
                            "DeleteActiveRow": 127,
                            "ExpandDetailTableKey": 39,
                            "CollapseDetailTableKey": 37,
                            "MoveDownKey": 40,
                            "MoveUpKey": 38,
                            "SaveChangesKey": 85,
                            "CancelChangesKey": 81
                        },
                        "Animation": {},
                        "Virtualization": {}
                    },
                    "Skin": "Bootstrap",
                    "SortingSettings": {
                        "SortToolTip": "",
                        "SortedAscToolTip": "Sorted asc",
                        "SortedDescToolTip": "Sorted desc",
                        "SortedBackColor": {
                            "R": 0,
                            "G": 0,
                            "B": 0,
                            "A": 0,
                            "IsKnownColor": false,
                            "IsEmpty": true,
                            "IsNamedColor": false,
                            "IsSystemColor": false,
                            "Name": "0"
                        },
                        "EnableSkinSortStyles": true,
                        "ViewState": {}
                    },
                    "UniqueID": "grdGameCompleted",
                    "_activeRowIndex": "",
                    "_clientDataSourceID": "csDS",
                    "_clientKeyValues": {
                        "0": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "1": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "2": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "3": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "4": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "5": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "6": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "7": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "8": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        },
                        "9": {
                            "GameInstanceId": "",
                            "GameKeyName": "",
                            "ExtRoundId": "",
                            "Exponent": "",
                            "IsTestSite": ""
                        }
                    },
                    "_controlToFocus": "",
                    "_currentPageIndex": 0,
                    "_defaultDateTimeFormat": "yyyy-MM-dd tt h:mm:ss",
                    "_editIndexes": "[]",
                    "_embeddedSkin": true,
                    "_freezeText": "Freeze",
                    "_gridTableViewsData": "[{\"ClientID\":\"grdGameCompleted_ctl00\",\"UniqueID\":\"grdGameCompleted$ctl00\",\"PageSize\":10,\"PageCount\":2,\"EditMode\":\"EditForms\",\"AllowPaging\":true,\"CurrentPageIndex\":0,\"VirtualItemCount\":0,\"AllowMultiColumnSorting\":false,\"AllowNaturalSort\":true,\"AllowFilteringByColumn\":false,\"PageButtonCount\":10,\"HasDetailTables\":false,\"HasMultiHeaders\":false,\"CheckListWebServicePath\":\"\",\"GroupLevelsCount\":\"0\",\"EnableGroupsExpandAll\":false,\"GroupHeadersCount\":[],\"GroupByExpressions\":[],\"DataFieldHeaderText\":{\"GameKeyName\":\"게임\",\"FriendlyId\":\"nº\",\"RealStake\":\"스테이크\",\"RealPayout\":\"지불\",\"DateToShow\":\"완료일\"},\"GroupLoadMode\":\"Server\",\"PagerAlwaysVisible\":false,\"changePageSizeComboBoxTopClientID\":\"\",\"changePageSizeComboBoxClientID\":\"\",\"IsItemInserted\":false,\"clientDataKeyNames\":[\"GameInstanceId\",\"GameKeyName\",\"ExtRoundId\",\"Exponent\",\"IsTestSite\"],\"hasDetailItemTemplate\":false,\"_dataBindTemplates\":false,\"_selectedItemStyle\":\"\",\"_selectedItemStyleClass\":\"rgSelectedRow\",\"_columnsData\":[{\"UniqueName\":\"GameKeyName\",\"Resizable\":true,\"Reorderable\":true,\"Selectable\":true,\"Groupable\":true,\"ColumnType\":\"GridBoundColumn\",\"ColumnGroupName\":\"\",\"Editable\":true,\"SortExpression\":\"GameKeyName\",\"DataTypeName\":\"System.String\",\"DataField\":\"GameKeyName\",\"Display\":true},{\"UniqueName\":\"FriendlyId\",\"Resizable\":true,\"Reorderable\":true,\"Selectable\":true,\"Groupable\":true,\"ColumnType\":\"GridBoundColumn\",\"ColumnGroupName\":\"\",\"Editable\":true,\"SortExpression\":\"FriendlyId\",\"DataTypeName\":\"System.String\",\"DataField\":\"FriendlyId\",\"Display\":true},{\"UniqueName\":\"RealStake\",\"Resizable\":true,\"Reorderable\":true,\"Selectable\":true,\"Groupable\":true,\"ColumnType\":\"GridBoundColumn\",\"ColumnGroupName\":\"\",\"Editable\":true,\"SortExpression\":\"RealStake\",\"DataTypeName\":\"System.Decimal\",\"DataField\":\"RealStake\",\"Display\":true},{\"UniqueName\":\"RealPayout\",\"Resizable\":true,\"Reorderable\":true,\"Selectable\":true,\"Groupable\":true,\"ColumnType\":\"GridBoundColumn\",\"ColumnGroupName\":\"\",\"Editable\":true,\"SortExpression\":\"RealPayout\",\"DataTypeName\":\"System.Decimal\",\"DataField\":\"RealPayout\",\"Display\":true},{\"UniqueName\":\"DateToShow\",\"Resizable\":true,\"Reorderable\":true,\"Selectable\":true,\"Groupable\":true,\"ColumnType\":\"GridBoundColumn\",\"ColumnGroupName\":\"\",\"Editable\":true,\"SortExpression\":\"DateToShow\",\"DataTypeName\":\"System.String\",\"DataField\":\"DateToShow\",\"Display\":true}]}]",
                    "_groupingSettings": {
                        "GroupContinuesFormatString": " Group continues on the next page.",
                        "GroupContinuedFormatString": "... group continued from the previous page. ",
                        "GroupSplitDisplayFormat": "Showing {0} of {1} items.",
                        "GroupSplitFormat": " ({0})",
                        "GroupByFieldsSeparator": "; ",
                        "CaseSensitive": true,
                        "ExpandTooltip": "Expand group",
                        "ExpandAllTooltip": "Expand all groups",
                        "CollapseTooltip": "Collapse group",
                        "CollapseAllTooltip": "Collapse all groups",
                        "UnGroupTooltip": "Drag out of the bar to ungroup",
                        "UnGroupButtonTooltip": "Click here to ungroup",
                        "ShowUnGroupButton": false,
                        "IgnorePagingForGroupAggregates": false,
                        "RetainGroupFootersVisibility": false,
                        "MainTableSummary": "",
                        "NestedTableSummary": "",
                        "GroupItemsWrapperTableSummary": "",
                        "MainTableCaption": "",
                        "NestedTableCaption": "",
                        "GroupItemsWrapperTableCaption": "",
                        "ViewState": {}
                    },
                    "_masterClientID": "grdGameCompleted_ctl00",
                    "_shouldFocusOnPage": false,
                    "_unfreezeText": "Unfreeze",
                    "allowMultiRowSelection": false,
                    "clientStateFieldID": "grdGameCompleted_ClientState",
                    "expandItems": {},
                    "renderMode": 2
                }, {
                    "rowClick": loadGame,
                    "rowDataBound": grdRowDataBound
                }, null, $get("grdGameCompleted"));
            });
            //]]>

        </script>
    </form>


    <script type="text/javascript">
        $("#hdTimeZone").val(new Date().getTimezoneOffset());
        var isTimeSet = false;
        var isSortSet = false;
        var sort = "1";
        var initLoad = 0;

        function setTimeDefault() {
            if (isTimeSet == false) {
                var newDate = new Date();
                var minDate = new Date(newDate.setHours(0, 0, 0, 0));
                var datepicker = $find("dtFrom");
                datepicker.set_selectedDate(new Date(minDate));


                var newMaxDate = new Date();
                var maxDate = new Date(newMaxDate.setDate(newMaxDate.getDate() + 1)).setHours(0, 0, 0, 0);

                var datepicker = $find("dtTo");
                datepicker.set_selectedDate(new Date(maxDate));
                isTimeSet = true;
            }
        }


        function rebindGrid() {

            var dtFrom = $('#dtFrom').val().split("-");
            var dtTo = $('#dtTo').val().split("-");

            var dateToday = new Date(new Date().setHours(0, 0, 0, 0));
            var dateFrom = new Date(dtFrom[0], dtFrom[1] - 1, dtFrom[2], dtFrom[3], dtFrom[4], dtFrom[5]);
            var dateTo = new Date(dtTo[0], dtTo[1] - 1, dtTo[2], dtTo[3], dtTo[4], dtTo[5]);

            if ((dateToday.getTime() != dateFrom.getTime()) || (new Date(new Date(dateToday.setDate(dateToday
                .getDate() + 1)).setHours(0, 0, 0, 0)).getTime() != dateTo.getTime())) {
                var sortOrder = Telerik.Web.UI.GridSortOrder.Ascending;
                AddSortExpression($find("grdGameCompleted"), "DateToShow", sortOrder);
                sort = "0";
            }

            var comptable = $find("grdGameCompleted").get_masterTableView();

            comptable.rebind();
        }

        function zeroPad(num, places) {
            var zero = places - num.toString().length + 1;
            return Array(+(zero > 0 && zero)).join("0") + num;
        }

        function ParameterMap(sender, args) {
            loaderFilter.show();
            setTimeDefault();
            var bid = $('#bid').val();
            var pid = $('#pid').val();
            var dtFrom = $('#dtFrom').val().split("-");
            var dtTo = $('#dtTo').val().split("-");

            var dtFromDate = "";
            var dtToDate = "";

            if (dtFrom.length > 1) {
                var fromDate = new Date(dtFrom[0], dtFrom[1] - 1, dtFrom[2], dtFrom[3], dtFrom[4], dtFrom[5]);
                //convert to utc
                var fromDateUtc = convertDateToUTC(fromDate);
                dtFromDate = "" + fromDateUtc.getFullYear() + "" + zeroPad(fromDateUtc.getMonth() + 1, 2) + "" +
                    zeroPad(fromDateUtc.getDate(), 2) + "" + zeroPad(fromDateUtc.getHours(), 2) + "" + zeroPad(
                        fromDateUtc.getMinutes(), 2) + "" + zeroPad(fromDateUtc.getSeconds(), 2);
            }

            if (dtTo.length > 1) {
                var toDate = new Date(dtTo[0], dtTo[1] - 1, dtTo[2], dtTo[3], dtTo[4], dtTo[5]);
                //convert to utc
                var toDateUtc = convertDateToUTC(toDate);
                dtToDate = "" + toDateUtc.getFullYear() + "" + zeroPad(toDateUtc.getMonth() + 1, 2) + "" + zeroPad(
                    toDateUtc.getDate(), 2) + "" + zeroPad(toDateUtc.getHours(), 2) + "" + zeroPad(toDateUtc
                    .getMinutes(), 2) + "" + zeroPad(toDateUtc.getSeconds(), 2);
            }
            var keyName = getUrlParameter("keyname");
            if (!keyName) {
                keyName = "";
            }

            //check if dt was sent via URL
            var dtStartFromURL = getUrlParameter("dtstartutc");
            if (dtStartFromURL) {
                dtFromDate = dtStartFromURL;
            }
            var dtEndFromURL = getUrlParameter("DtEndUTC");
            if (dtEndFromURL) {
                dtToDate = dtEndFromURL;
            }

            args.set_parameterFormat("{ 'pid':'" + pid + "','bid':'" + bid + "', 'dtStartUtc':'" + dtFromDate +
                "', 'dtEndUtc':'" + dtToDate + "', 'keyName':'" + keyName + "', 'tz':'" + new Date()
                .getTimezoneOffset() + "','sort':'" + sort + "'  }");
        }

        function convertDateToUTC(date) {
            return new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date
                .getUTCMinutes(), date.getUTCSeconds());
        }

        function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0].toLowerCase() === sParam.toLowerCase()) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };

        var dtStartFromURL = getUrlParameter("dtstartutc");
        var dtEndFromURL = getUrlParameter("DtEndUTC");
        var gameInstanceId = getUrlParameter("gameInstanceId");
        var friendlyGameInstanceId = getUrlParameter("FriendlyId");

        if (!dtStartFromURL && !dtEndFromURL && !gameInstanceId && !friendlyGameInstanceId) {
            $(".dateSelect").show();
        }

        function setMaxDate(sender, args) {
            var newMaxDate = new Date();
            var maxDate = new Date(newMaxDate.setDate(newMaxDate.getDate() + 1)).setHours(0, 0, 0, 0);

            sender.set_maxDate(new Date(maxDate));
        }

        function setMinDate(sender, args) {
            var newDate = new Date();
            var minDate = new Date(newDate.setDate(newDate.getDate() - filterMaxDays)).setHours(0, 0, 0, 0);
            sender.set_minDate(new Date(minDate));
            sender.set_maxDate(new Date(new Date().setHours(23, 0, 0, 0)));
        }

        function AddSortExpression(grid, fieldName, sortOrder) {
            var sortExpression = new Telerik.Web.UI.GridSortExpression();
            sortExpression.set_fieldName(fieldName);
            sortExpression.set_sortOrder(sortOrder);
            grid.get_masterTableView()._sortExpressions.clear();
            grid.get_masterTableView()._sortExpressions.add(sortExpression);
            grid.get_masterTableView()._showSortIconForField(fieldName, sortOrder);
        }

        function isSelectedDateValid() {
            var dtFrom = $('#dtFrom').val().split("-");
            var dtTo = $('#dtTo').val().split("-");

            var dtFromDate = "";
            var dtToDate = "";

            if (dtFrom.length > 1) {
                var fromDate = new Date(dtFrom[0], dtFrom[1] - 1, dtFrom[2], dtFrom[3], dtFrom[4], dtFrom[5]);
                //convert to utc
                var fromDateUtc = convertDateToUTC(fromDate);
                dtFromDate = "" + fromDateUtc.getFullYear() + "" + zeroPad(fromDateUtc.getMonth() + 1, 2) + "" +
                    zeroPad(fromDateUtc.getDate(), 2) + "" + zeroPad(fromDateUtc.getHours(), 2) + "" + zeroPad(
                        fromDateUtc.getMinutes(), 2) + "" + zeroPad(fromDateUtc.getSeconds(), 2);
            }

            if (dtTo.length > 1) {
                var toDate = new Date(dtTo[0], dtTo[1] - 1, dtTo[2], dtTo[3], dtTo[4], dtTo[5]);
                //convert to utc
                var toDateUtc = convertDateToUTC(toDate);
                dtToDate = "" + toDateUtc.getFullYear() + "" + zeroPad(toDateUtc.getMonth() + 1, 2) + "" + zeroPad(
                    toDateUtc.getDate(), 2) + "" + zeroPad(toDateUtc.getHours(), 2) + "" + zeroPad(toDateUtc
                    .getMinutes(), 2) + "" + zeroPad(toDateUtc.getSeconds(), 2);
            }

            if (dtFromDate > dtToDate) {
                return false;
            }

            return true;
        }

        function dtToDS(sender, args) {
            if (initLoad == 0) {
                initLoad = 1;
            } else {
                var isDateValid = isSelectedDateValid();
                var dtoControl = $find("dtTo");
                var dfromControl = $find("dtFrom");

                if (!isDateValid) {
                    dtoControl.get_dateInput()._invalid = true;
                    dtoControl.get_dateInput().updateCssClass();
                } else {
                    dtoControl.get_dateInput()._invalid = false;
                    dfromControl.get_dateInput()._invalid = false;
                    dtoControl.get_dateInput().updateCssClass();
                    dfromControl.get_dateInput().updateCssClass();
                }
            }

        }

        function dtFromDS(sender, args) {
            if (initLoad == 0) {
                initLoad = 1;
            } else {
                var isDateValid = isSelectedDateValid();
                var dtoControl = $find("dtTo");
                var dfromControl = $find("dtFrom");

                if (!isDateValid) {
                    dfromControl.get_dateInput()._invalid = true;
                    dfromControl.get_dateInput().updateCssClass();
                } else {
                    dtoControl.get_dateInput()._invalid = false;
                    dfromControl.get_dateInput()._invalid = false;
                    dtoControl.get_dateInput().updateCssClass();
                    dfromControl.get_dateInput().updateCssClass();
                }
            }
        }

    </script>

</body>

</html>
