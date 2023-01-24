<div class="board-main">
    <div class="bm-head">
    </div>
    <div class="bm-body">
        <div class="board-cont notice">
            <div class="head">
                <div class="title">                  
                    <span class="text-grp">
                        <p class="name">
                            <img src="/frontend/iris/theme/sp/images/board/ico_notice.png">
                            공지사항
                        </p>
                    </span>
                </div>
                <button type="button" class="more-btn"  onclick="board('notice')">
                    more 
                    <img src="/frontend/iris/theme/sp/images/board/btn_more.png">        
                </button>
            </div>
            <div class="body">
                <table>
                    <tbody>
                    @if (count($noticelist) > 0)
                    @foreach ($noticelist as $ntc)
                    <tr>
                        <td class="text-left"  onclick="boardOn('notice',{{$ntc->id}})">
                            <a href="javascript:void(0)">{{$ntc->title}}</a>
                        </td>
                        <td align="right" style="color:#656565">{{date('Y-m-d', strtotime($ntc->date_time))}}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="text-center">
                            <a href="javascript:void(0)">공지사항이 없습니다</a>
                        </td>
                    </tr>
                    @endif
                </tbody>
                </table>
            </div>
        </div>

       
    </div>
</div>