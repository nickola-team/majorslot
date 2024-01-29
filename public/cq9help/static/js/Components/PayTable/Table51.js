import React, {
    Fragment
} from "react"
import cx from "classnames"
import TotalBet from "./TotalBet"
// lib
import {
    imageDomain,
    createSymbol
} from "common-lib/lib/helps"
// config
import {
    currencyList
} from "../../config/currencyList"
import translation from "../../config/translation"
// styled
import {
    PayTableWrapper,
    PayList
} from "./Normal"

const Normal = ({
    lang,
    windowDimensions,
    payTableData,
    gameId,
    isCurrency,
    currency,
    denom,
    betLevel,
    moneyConvert,
    symbol,
    bet,
}) => {
    const payTableContent = {
        cn: "2个相同图标的得分奖励，只出现在免费游戏",
        en: "2 of a kind reward only appears in free games.",
        th: "สัญลักษณ์เหมือนกัน 2 ตัวที่ชนะรางวัลจะปรากฎเฉพาะในฟรีเกมเท่านั้น",
        id: "2 of a kind reward only appears in free games.",
        vn: "Thưởng 2 hình giống nhau chỉ xuất hiện trong Trò chơi miễn phí",
        ko: "동일한2개 아이콘의 득점 보상은 무료 게임에만 나타납니다",
        es: "2 of a kind reward only appears in free games.",
        ja: "2 of a kind reward only appears in free games.",
        "pt-br": "Prêmio de 2 imagens idênticas somente aparecem em jogos gratuitos",
        ph: "2 of a kind reward only appears in free games.",
    }
    return ( <
        PayTableWrapper >
        <
        TotalBet lang = {
            lang
        }
        isCurrency = {
            isCurrency
        }
        currency = {
            currency
        }
        bet = {
            bet
        }
        denom = {
            denom
        }
        /> <
        p className = "title" > {
            translation["payTable"][lang]
        } < /p> <
        PayList windowDimensions = {
            windowDimensions
        } > {
            payTableData.get("math_data") &&
            payTableData
            .get("math_data")
            .filter(
                (i) =>
                !i.get("SymbolPays").every((v) => v === 0) &&
                i.get("SymbolName") !== "SC" &&
                i.get("SymbolName") !== "W" &&
                i.get("SymbolName") !== "F" &&
                i.get("SymbolName") !== "W1" &&
                i.get("SymbolName") !== "W2" &&
                i.get("SymbolName") !== "W3" &&
                i.get("SymbolName") !== "W4" &&
                i.get("SymbolName") !== "W5"
            )
            .map((v) => ( <
                div className = "half"
                key = {
                    v.get("SymbolID")
                } >
                <
                div className = "pic" >
                <
                img className = {
                    cx("pay-img object-fit-scale", {
                        bigSymbol: symbol.some((i) => i === v.get("SymbolName")),
                        mobileSize: windowDimensions.width < 375,
                    })
                }
                alt = ""
                src = {
                    `${imageDomain}/order-detail/common/${gameId}/symbolList/${v.get(
                      "SymbolName"
                    )}.png`
                }
                /> <
                /div> <
                div className = "list" > {
                    v
                    .get("SymbolPays")
                    .reverse()
                    .map((v, k, array = v.get("SymbolPays")) => ( <
                        Fragment key = {
                            k
                        } > {
                            v !== 0 && ( <
                                div key = {
                                    k
                                } > {
                                    array.size - k
                                } -
                                <
                                span className = {
                                    cx({
                                        money: isCurrency
                                    })
                                } > {
                                    isCurrency && ( <
                                        div className = "symbol"
                                        dangerouslySetInnerHTML = {
                                            createSymbol(
                                                currencyList[currency]["symbol"]
                                            )
                                        }
                                        />
                                    )
                                } {
                                    isCurrency
                                        ?
                                        moneyConvert(v * denom * betLevel) :
                                        v * betLevel
                                } <
                                /span> <
                                /div>
                            )
                        } <
                        /Fragment>
                    ))
                } <
                /div> <
                /div>
            ))
        } <
        /PayList> <
        p > {
            `( ${payTableContent[lang]} )`
        } < /p> <
        /PayTableWrapper>
    )
}

export default Normal