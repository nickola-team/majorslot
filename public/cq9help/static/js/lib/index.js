// 預設語系 非中文即英文
export const fetchLanguage = (isMultiLang = true) => {
    const {
        navigator
    } = window
    const nLanguage = navigator.languages ?
        navigator.languages[0] :
        navigator.language || navigator.userLanguage
    const language = isMultiLang ?
        nLanguage.split("-")[0] === "zh" ?
        "cn" :
        "en" :
        "cn"
    return language
}

// 取網址參數
export const getQuery = (param) => {
    const url = window.location
    const searchUrl = url.search.split("?")
    const joinQuery = searchUrl.slice(1, searchUrl.length).join("")
    const query = new URLSearchParams(joinQuery)
    const value = query.get(param)
    return value
}

export const isMobile = {
    Android: function() {
        return navigator.userAgent.match(new RegExp("Android", "i"))
    },
    BlackBerry: function() {
        return navigator.userAgent.match(new RegExp("BlackBerry", "i"))
    },
    iPhone: function() {
        return navigator.userAgent.match(new RegExp("iPhone", "i"))
    },
    Opera: function() {
        return navigator.userAgent.match(new RegExp("Opera Mini", "i"))
    },
    Windows: function() {
        return navigator.userAgent.match(new RegExp("IEMobile", "i"))
    },
    any: function() {
        return (
            isMobile.Android() ||
            isMobile.BlackBerry() ||
            isMobile.iPhone() ||
            isMobile.Opera() ||
            isMobile.Windows()
        )
    },
}