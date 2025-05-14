/**
 * Google Tag Manager (GA4)
 * */
try {
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l !== 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-TZKP2QTB');
} catch (e) {
    console.log(e);
}

/**
 * 페이스북 전환스크립트 픽셀
 * */
try {
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) {
            return;
        }
        n = f.fbq = function() {
            n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
        };
        if (!f._fbq) {
            f._fbq = n;
        }
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.body;
        s.appendChild(t, s.nextSibling);
    }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '514175535632514');
    fbq('track', 'PageView');
} catch (e) {}

/**
 * [PLN-1861] [pc] 모바일웹 히트맵분석툴(UX 분석) 추가의 건
 * */
try {
    (function(w, d, a) {
        w.__beusablerumclient__ = {
            load: function(src) {
                var b = d.createElement('script');
                b.src = src;
                b.async = true;
                b.type = 'text/javascript';
                d.getElementsByTagName('head')[0].appendChild(b);
            }
        };
        w.__beusablerumclient__.load(a);
    })(window, document, '//rum.beusable.net/script/b201126e094900u036/1f1f8e561f');
} catch (e) {}

/**
 * 카카오 픽셀 스크립트 로드
 * */
try {
    (function loadKakaoPixel(w, d, a, id) {
        var s = d.createElement('script');
        s.type = 'text/javascript';
        s.charset = 'UTF-8';
        s.src = a;
        s.async = true;

        s.onload = function() {
            if (typeof w.kakaoPixel === 'function') {
                // window의 kakaoPixel 속성을 특정 ID의 객체로 덮어씌우는 동작 제거 (다중 ID 사용이 가능해야 하기 때문에)
                // w.kakaoPixel = w.kakaoPixel(id);

                // ID를 명시적으로 전달하며 이벤트 발생
                w.kakaoPixel(id)
                    .pageView();
            }
        };

        d.head.appendChild(s);
    })(window, document, '//t1.daumcdn.net/adfit/static/kp.js', '6828674910408061769');
} catch (e) {
    console.error(e);
}