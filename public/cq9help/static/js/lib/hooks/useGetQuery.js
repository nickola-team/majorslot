import {
    useEffect,
    useState
} from "react"
import {
    getQuery
} from "lib"
import defaultCurrency from "config/defaultCurrency"
import defaultLangList from "config/defaultLangList"

const is168 = process.env.REACT_APP_SITE_TYPE === "168"

const useGetQuery = () => {
    const [defaultLang, setDefaultLang] = useState("")
    const [gameId, setGameId] = useState("")
    const [minExtra, setMinExtra] = useState(0)
    const [maxExtra, setMaxExtra] = useState(0)
    const [denom, setDenom] = useState(1)
    const [bet, setBet] = useState(0)
    const [betLevel, setBetLevel] = useState(1)
    const [isSoundOn, setIsSoundOn] = useState(true)
    const [currency, setCurrency] = useState("default")
    const [isCurrency, setIsCurrency] = useState(false)
    useEffect(() => {
        const QueryLang = getQuery("language")
        const QueryGameId =
            getQuery("gameid") === "7006" ?
            "GB6" :
            getQuery("gameid") === "7012" ?
            "GB12" :
            getQuery("gameid")
        const QueryExtra = getQuery("extra")
        const QueryDenom = getQuery("denom")
        const QueryBet = getQuery("bet")
        const QueryBetLevel = getQuery("betLevel")
        const QueryCurrency =
            getQuery("currency") && getQuery("currency").toLowerCase()
        const QueryIsCurrency = getQuery("isCurrency")
        const QuerySoundOn = getQuery("soundOn")
        QueryGameId && setGameId(QueryGameId)
        QueryExtra && setMinExtra(QueryExtra.split(",")[0])
        QueryExtra && setMaxExtra(QueryExtra.split(",")[1])
        QueryDenom && setDenom(QueryDenom)
        QueryBet && setBet(QueryBet)
        QueryBetLevel && setBetLevel(QueryBetLevel)
        if (QuerySoundOn === "false") {
            setIsSoundOn(false)
        }
        if (QueryIsCurrency === "true") {
            setIsCurrency(true)
        }
        is168
            ?
            setDefaultLang("zh-tw") :
            QueryLang === "zh-cn" ?
            setDefaultLang("cn") :
            QueryLang ?
            defaultLangList().includes(QueryLang) ?
            setDefaultLang(QueryLang) :
            setDefaultLang("en") :
            setDefaultLang("cn")

        defaultCurrency().includes(QueryCurrency) && setCurrency(QueryCurrency)
    }, [])

    return {
        defaultLang,
        gameId,
        minExtra,
        maxExtra,
        denom,
        bet,
        betLevel,
        isSoundOn,
        currency,
        isCurrency,
    }
}

export default useGetQuery