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
                @auth()
                <button type="button" class="more-btn"  onclick="boardnotice();closeMenu();">
                @else
                <button type="button" class="more-btn"  onclick="mustSignIn('로그인이 필요한 메뉴입니다.')">
                @endif
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
                        @auth()
                        <?php
                            $detect = new \Detection\MobileDetect();
                            if( $detect->isMobile() || $detect->isTablet() ) 
                            {
                                $top = 0;
                                $left = 0;
                                $width = 'window.innerWidth';
                                $height = 'window.innerHeight';
                            }
                            else
                            {
                                $top = 100;
                                $left = 100;
                                $width = 420;
                                $height = 380;
                            }
                        ?>
                        
                        <td class="text-left"  onclick="addPopup('popupID{{$ntc->id}}', {{$top}}, {{$left}}, {{$width}}, {{$height}});">
                        @else
                        <td class="text-left"  onclick="mustSignIn('로그인이 필요한 메뉴입니다.')">
                        @endif
                            <a href="javascript:void(0)">{{$ntc->title}}</a>
                        </td>
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