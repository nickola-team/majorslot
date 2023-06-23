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

const TableGB3 = ({
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
    let picLang = ""
    switch (lang) {
        case "cn":
            picLang = "cn"
            break
        case "en":
            picLang = "en"
            break
        case "ko":
            picLang = "kr"
            break
        default:
            picLang = "en"
            break
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
                (i.get("SymbolName") === "H1" ||
                    i.get("SymbolName") === "H2" ||
                    i.get("SymbolName") === "H3")
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
        } { /* 多語系圖片 */ } {
            payTableData.get("math_data") &&
                payTableData
                .get("math_data")
                .filter((i) => i.get("SymbolName") === "H4")
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
                    )}_${picLang}.png`
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
        } {
            payTableData.get("math_data") &&
                payTableData
                .get("math_data")
                .filter(
                    (i) =>
                    !i.get("SymbolPays").every((v) => v === 0) &&
                    (i.get("SymbolName") === "N1" || i.get("SymbolName") === "N2")
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
        } { /* 多語系圖片 */ } {
            payTableData.get("math_data") &&
                payTableData
                .get("math_data")
                .filter(
                    (i) =>
                    i.get("SymbolName") === "N3" || i.get("SymbolName") === "N4"
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
                    )}_${picLang}.png`
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
        /PayTableWrapper>
    )
}

export default TableGB3