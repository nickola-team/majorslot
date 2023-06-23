import {
    fetchGapiDomain
} from "../common-lib/config/gapidomain"
const is168 = process.env.REACT_APP_SITE_TYPE === "168"
export const API_URL = is168 ?
    fetchGapiDomain({}) :
    fetchGapiDomain({
        isclinetInt: true,
        getInt: "help.cqgame.games"
    })