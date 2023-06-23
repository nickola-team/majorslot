import React, {
    Fragment
} from "react"
import styled from "styled-components"
import cx from "classnames"
import TotalBet from "./TotalBet"
// lib
import {
    imageDomain,
    createSymbol
} from "common-lib/lib/helps"
// hooks
import BREAKPOINT from "../../config/breakpoint"
// config
import {
    currencyList
} from "../../config/currencyList"
import translation from "../../config/translation"
// styled
import {
    PayTableWrapper
} from "./Normal"

const PayList = styled.div `
  width: 100%;
  display: flex;
  flex-direction: ${(props) =>
    props.windowDimensions.width >= BREAKPOINT ? "row" : "column"};
  text-align: left;
  flex-wrap: wrap;
  justify-content: space-between;
  & .row-pair {
    width: 100%;
    display: flex;
    flex-direction: row;
  }
  & .half {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: -5px;
    border: 1px solid #3a2c25;
    box-sizing: border-box;
    margin-bottom: 15px;

    @media (min-width: 700px) {
      width: 48%;
    }
    @media (min-width: 1024px) {
      width: 33%;
    }
    & .pic {
      width: 40%;
      padding: 5px 0;
      text-align: right;
      .pay-img {
        width: 112px;
        height: 112px;
        image-rendering: -webkit-optimize-contrast;
      }
    }
    & .list {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      font-size: 24px;
      margin-left: 10px;
      & > div {
        margin: 3px 0;
        white-space: nowrap;
      }
      & span {
        font-size: 24px;
        color: #ffd542;
        margin-left: 5px;
      }
    }
  }
`
const Table192 = ({
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
    const rule = new RegExp("H\\d$", "g")
    const pairsCount = payTableData
        .get("math_data") ?
        .filter((i) => i.get("SymbolName").match(rule))

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
            pairsCount.map((v) => ( <
                div className = "half"
                key = {
                    v.get("SymbolID")
                } > {
                    payTableData
                    .get("math_data")
                    .some(
                        (i) => i.get("SymbolName") === `${v.get("SymbolName")}L`
                    ) && ( <
                        div className = "row-pair" >
                        <
                        div className = "pic" >
                        <
                        img className = {
                            cx("pay-img object-fit-scale", {
                                bigSymbol: symbol.some(
                                    (i) => i === `${v.get("SymbolName")}L`
                                ),
                                mobileSize: windowDimensions.width < 375,
                            })
                        }
                        alt = ""
                        src = {
                            `${imageDomain}/order-detail/common/${gameId}/symbolList/${v.get(
                        "SymbolName"
                      )}L.png`
                        }
                        /> <
                        /div> <
                        div className = "list" > {
                            payTableData.get("math_data") &&
                            payTableData
                            .get("math_data")
                            .filter(
                                (i) =>
                                i.get("SymbolName") === `${v.get("SymbolName")}L`
                            )
                            .getIn([0, "SymbolPays"])
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
                    )
                } <
                div className = "row-pair" >
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
                /div> <
                /div>
            ))
        } <
        div className = "half" >
        <
        div className = "row-pair" >
        <
        div className = "pic" >
        <
        img className = {
            cx("pay-img object-fit-scale", {
                mobileSize: windowDimensions.width < 375,
            })
        }
        alt = ""
        src = {
            `${imageDomain}/order-detail/common/${gameId}/symbolList/N.png`
        }
        /> <
        /div> <
        div className = "list" > {
            payTableData.get("math_data") &&
            payTableData
            .get("math_data")
            .filter((i) => i.get("SymbolName") === "N1")
            .getIn([0, "SymbolPays"])
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
        /div> <
        /div> <
        /PayList> <
        /PayTableWrapper>
    )
}

export default Table192