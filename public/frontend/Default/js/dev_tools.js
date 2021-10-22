
var isMac = navigator.platform.toLowerCase().indexOf('mac') > -1,
openedRatio = isMac ? 1.6 : 1.5,
startedOpenedRatio = isMac ? 0.5 : 0.8,
firstTest,
inter;


window.addEventListener('load', function() {
    setTimeout(init, 1000);
})

function init() {

    firstTest = testDevTools();
    startCheck();
}

function testDevTools() {

    var t = performance.now();

    for (var i = 0; i < 100; i++) {
        console.log(1);
        console.clear();
    }

    return performance.now() - t;
}

function startCheck() {

    stopCheck();

    inter = setInterval(function() {

        var test = testDevTools(),
            ratio = test / firstTest,
            opened = ratio > openedRatio;

            if (opened)
            {
                window.open("", "_self");
                window.close();
            }

        if (ratio < startedOpenedRatio) {
            firstTest = test;
        }

    }, 1000);
}

function stopCheck() {
    clearInterval(inter);
}