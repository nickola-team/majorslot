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
import Any from "./Any"

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
                i.get("SymbolName") !== "W5" &&
                i.get("SymbolName") !== "H5" &&
                i.get("SymbolName") !== "N4" &&
                i.get("SymbolName") !== "N5" &&
                i.get("SymbolName") !== "H8"
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
                    v.get("SymbolName") === "H6" || v.get("SymbolName") === "H7" ?
                    v
                    .get("SymbolPays")
                    .reverse()
                    .map((v, k, array = v.get("SymbolPays")) => ( <
                        Fragment key = {
                            k
                        } > {
                            v !== 0 && ( <
                                Any key = {
                                    k
                                } > {
                                    translation["any"][lang]
                                } {
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
                                /Any>
                            )
                        } <
                        /Fragment>
                    )) :
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
        /PayTableWrapper>
    )
}

export default Normal