app.factory('CsrfToken', function($rootScope, $http) {
    var loggedStatus;
    var token;
    var service = {
        set: set,
        get: get,
        getLogged: getLogged,
        HttpRequest: HttpRequest,
        GetReatltimeTransaction: GetReatltimeTransaction,
        GetEventList: GetEventList,
        GetNoticeList: GetNoticeList,
        CheckServer: CheckServer
    }

    function set() {
        $http.get("/api/system/csrf.php").then(function(data) {
            token = data.data.token;
            loggedStatus = data.data.loggedInStatus;
            $rootScope.$broadcast('csrfToken:setSuccess');
        });
    }

    function getLogged() {
        return loggedStatus
    }

    function get() {
        return token
    }

    function HttpRequest(type, url, param) {
        if (param == "" || !param) {
            param = [];
        }

        if (!type) {
            type = 'GET'
        }

        return $http({
            method: type,
            url: url,
            data: $.param(param),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                eg_csrf_token_label: get()
            }
        })
    }

    function GetReatltimeTransaction() {
        var url = "/frontend/newheaven/api/finance/DisplayTransaction?count=20";
        HttpRequest('GET', url)
            .success(function(data) {
                $rootScope.realTimeTransactionList = data;
            }).error(function(data, result) {

            })["finally"](function() {});
    }

    function CheckServer() {
        var url = "/api/system/CheckServer?type=return";
        HttpRequest('GET', url)
            .success(function(data) {
                $rootScope.bankContactNumber = data.bank;
                $rootScope.techContactNumber = data.tech;
                $rootScope.emailContact = data.email;
                $rootScope.messengerContact1 = data.messenger1;
                $rootScope.messengerContact2 = data.messenger2;
                $rootScope.aff_url = data.aff_url;
                $rootScope.NewMember = data.NewMember;
            }).error(function(data, status) {
                console.error('Repos error', status, data);
            });
    }

    function GetEventList() {
        var url = "/api/operation/GetBoardDetail?type=3";
        HttpRequest("GET", url)
            .success(function(data) {
                $rootScope.eventList = data.list;
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {});
    }

    function GetNoticeList() {
        var url = "/api/operation/GetBoardDetail?type=1&page=1";
        HttpRequest("GET", url)
            .success(function(data) {
                $rootScope.announceList = data.list;
                $rootScope.displayNoticeToday();
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {});
    }

    return service
});

app.run(function(CsrfToken, $rootScope) {
    CsrfToken.set();

    $rootScope.$on('csrfToken:setSuccess', function() {
        CsrfToken.GetReatltimeTransaction();
        CsrfToken.GetEventList();
        CsrfToken.GetNoticeList();
        CsrfToken.CheckServer();
        $rootScope.init(CsrfToken.getLogged());
    });
});