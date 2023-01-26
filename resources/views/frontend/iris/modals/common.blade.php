

<div class="modal" id="modal-alert-success" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel"
     aria-hidden="true" style="top: 115px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="defaultModalLabel">알림</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success m-b-0">
                    <h4><i class="fa fa-check-circle"></i> </h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-alert-danger" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel"
     aria-hidden="true" style="top: 115px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="defaultModalLabel">알림</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger m-b-0">
                    <h4><i class="fa fa-check-circle"></i> </h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-alert-logout" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel"
     aria-hidden="true" style="top: 115px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="defaultModalLabel" style="color: #a94442;">경고</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger m-b-0">
                    <h4><i class="fa fa-check-circle"></i> 자동 로그아웃까지 90초 남았습니다. 계속하려면 확인을 클릭하세요.</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">확인</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="logOut()">
                    로그아웃                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-alert-loginOverlap" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel"
     aria-hidden="true" style="top: 115px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="defaultModalLabel" style="color: #a94442;">경고</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger m-b-0">
                    <h4><i class="fa fa-check-circle"></i> 중복으로 로그인되어 현재 위치에서 로그아웃 합니다.</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-alert-message" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog"
     aria-labelledby="defaultModalLabel" aria-hidden="true" style="top: 115px; z-index: 1916;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="defaultModalLabel">알림</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success m-b-0" id="message2Player"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">확인</button>
            </div>
        </div>
    </div>
</div>
</div>