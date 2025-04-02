(function(root) {
    //FUNCTION DECLARATION
    var auth = {

        'setUserType': setUserType,
        'getUserType': getUserType,
        'isEqualOrUpperUserType': isEqualOrUpperUserType,
        'getUserTypeName': getUserTypeName,
    };

    root.auth = auth;

    var userType;

    var userTypeLevel = {
        "c": 0,
        "s": 1,
        "m": 2,
        "a": 3
    };

    function setUserType(type) {
        userType = type;
    }

    function getUserType() {
        return userType;
    }

    function getUserTypeName() {
        if (userType == 'c')
            return 'CA';
        else if (userType == 's')
            return 'SMA';
        else if (userType == 'm')
            return 'MA';
        else if (userType == 'a')
            return 'AG';
    }

    function isEqualOrUpperUserType(forType) {
        var forLevel = userTypeLevel[forType];
        var userLevel = userTypeLevel[this.getUserType()];

        if (userLevel <= forLevel)
            return true;

        return false;
    }


}(this));