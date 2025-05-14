(function() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');

    if (msie > 0 || !!ua.match(/Trident.*rv\:11\./)) {
        var contents = [
            '<style>' +
            '@import url(//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSansNeo.css);' +
            '@import url(//fonts.googleapis.com/earlyaccess/notosanskr.css);' +
            'body,html{margin: 0;padding: 0;height: 100%;}' +
            'p{margin: 0;}' +
            '.ie-block-page{position: relative;width: 100%;height: 100%;}' +
            '.ie-block{width: 100%;position: absolute;top: 50%;left: 50%;margin-top: -40px;transform: translate(-50%, -50%);text-align: center;}' +
            '.ie-block__title{line-height: 64px;font-family: \'Spoqa Han Sans Neo\', \'AppleSDGothicNeo-Regular\', \'Malgun Gothic\', \'Dotum\',' +
            ' \'sans-serif\';font-size:48px;color: #1C2028;font-weight: 500;}' +
            '.ie-block__logo{display: inline-block;width: 100px; height: 100px;background: url("resources/img/comm/img-block-ie.png") no-repeat 0 0;background-size: 100px' +
            ' 100px;margin-bottom: 30px;}' +
            '.ie-block__desc{margin-top: 24px;line-height: 27px;font-family: \'Spoqa Han Sans Neo\', \'AppleSDGothicNeo-Regular\', \'Malgun Gothic\', \'Dotum\', \'sans-serif\';font-size:' +
            ' 20px;color: #3D414B;}' +
            '.ie-block__button-box{margin-top: 64px;}' +
            '.ie-block__button{display: inline-block;width: 360px;height: 72px;line-height: 72px;border-radius: 4px;font-family: \'Noto Sans KR\', \'AppleSDGothicNeo-Regular\', \'Malgun Gothic\',' +
            ' \'Dotum\', \'sans-serif\';font-size: 24px;color: #FFF;text-decoration: none;box-sizing: border-box;background-color: #1C2028;font-weight: 500;}' +
            '</style>' +
            '<div class="ie-block-page">' +
            '    <div class="ie-block">' +
            '        <div class="ie-block__logo">' +
            '        </div>' +
            '        <div class="ie-block__title">' +
            '            Internet Explorer 에서는<br>빗썸을 이용하실 수 없습니다.' +
            '        </div>' +
            '        <div class="ie-block__desc">' +
            '            <p>Chrome, Safari 등에서 이용하실 수 있으며,</p>' +
            '            <p>Chrome 브라우저에 최적화 되어있습니다.</p>' +
            '        </div>' +
            '        <div class="ie-block__button-box">' +
            '            <a href="https://www.google.co.kr/chrome/browser/desktop/index.html" target="_blank" class="ie-block__button">크롬 다운로드</a>' +
            '        </div>' +
            '    </div>' +
            '</div>'
        ];

        document.body.innerHTML = contents.join('');
    } else {
        // console.log('other browser');
    }
}());