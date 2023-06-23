import React, {
    Fragment
} from "react";
import styled from "styled-components";
import cx from "classnames";
import TotalBet from "./TotalBet";
// lib
import {
    imageDomain,
    createSymbol
} from "common-lib/lib/helps";

// config
import {
    currencyList
} from "../../config/currencyList";
import translation from "../../config/translation";
import {
    PayTableWrapper,
    PayList
} from "./Normal";
const TotalValueAmount = styled.span `
  font-size: 20px !important;
  &.en {
    font-size: 14px !important;
  }
`;

const Table242 = ({
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
    isLongList,
}) => {
    const totalValueAmount = {
        tw: "總顯示分數",
        cn: "总显示分数",
        en: "TOTAL VALUE AMOUNT",
        ko: "총 심벌 점수",
        th: "แสดงคะแนนรวม",
    };
    const langFetch = (lang = "") => {
        const tw = lang === "zh-tw";
        const cn = lang === "cn";
        const ko = lang === "ko";
        const th = lang === "th";
        if (!tw && !cn && !ko && !th) {
            return "en";
        } else return lang;
    };
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
        }
        isLongList = {
            isLongList
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
                    v.get("SymbolName") === "H5" && ( <
                        div >
                        5 -
                        <
                        TotalValueAmount className = {
                            cx({
                                en: langFetch(lang) === "en"
                            })
                        } >
                        {
                            totalValueAmount[langFetch(lang)]
                        } <
                        /TotalValueAmount> <
                        /div>
                    )
                } {
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
    );
};

export default Table242;