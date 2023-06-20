import React, {
    Fragment,
    useState,
    useEffect
} from "react"
import styled from "styled-components"
import cx from "classnames"
import TotalBet from "./TotalBet"
import {
    v4 as uuidv4
} from "uuid"
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
const PayTableWrapper = styled.div `
  width: 100%;
  display: flex;
  flex-direction: column;
  border: 2px solid #422e1e;
  box-sizing: border-box;
  margin-bottom: 20px;
  & .level-title {
    background-color: #422e1e;
    padding: 10px 0;
    font-weight: bold;
    font-size: 20px;
  }
`
const PayList = styled.div `
  padding: 10px;
  width: 100%;
  display: flex;
  flex-direction: ${(props) =>
    props.windowDimensions.width >= BREAKPOINT ? "row" : "column"};
  text-align: left;
  flex-wrap: wrap;
  & .half {
    width: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: -5px;
    @media (min-width: 700px) {
      width: 50%;
    }
    @media (min-width: 1024px) {
      width: 33.3%;
    }
    & .pic {
      width: 35%;
      padding: 20px 0;
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
const Challenge = ({
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
    const [level, setLevel] = useState(1)
    const [levelSymbols, setLevelSymbols] = useState([])

    // 總共有幾關
    useEffect(() => {
        if (payTableData.get("math_data")) {
            const level = payTableData
                .get("math_data")
                .filter((i) => i.get("SymbolName").includes("H1_"))
            setLevel(level.size)
        }
    }, [payTableData])

    // 找出每一關變換的圖
    useEffect(() => {
        if (payTableData.get("math_data")) {
            const array = payTableData
                .get("math_data")
                .filter((i) => i.get("SymbolName").includes("_1"))
            const array2 = array.map((v) => v.get("SymbolName").slice(0, -1))
            setLevelSymbols(array2)
        }
    }, [payTableData])

    const cnLevelConvert = (level) => {
        switch (level) {
            case 1:
                return "一"
            case 2:
                return "二"
            case 3:
                return "三"
            case 4:
                return "四"
            case 5:
                return "五"
            default:
                break
        }
    }
    const i18nLevelConvert = (lang, level) => {
        switch (lang) {
            case "cn":
                return `第${cnLevelConvert(level)}关赔率表`
            case "en":
                return `Level ${level}`
            case "ko":
                return `제 ${level}레벨`
            case "th":
                return `ด่านที่${level}`
            case "vn":
                return `Cửa ải số ${level}`
            case "id":
                return `Level ${level}`
            case "ja":
                return `ステージ${level}`
            case "es":
                return `Nivel${level}`
            case "pt-br":
                return `nível ${level}`
            default:
                return `Level ${level}`
        }
    }
    return ( <
        >
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
        } < /p> {
            Array.from({
                length: level
            }, (v, i) => i).map((v, k) => ( <
                Fragment key = {
                    uuidv4()
                } >
                <
                PayTableWrapper >
                <
                p className = "level-title" > {
                    i18nLevelConvert(lang, k + 1)
                } < /p> <
                PayList windowDimensions = {
                    windowDimensions
                } > {
                    levelSymbols.map((value, key) => ( <
                        div className = "half"
                        key = {
                            uuidv4()
                        } >
                        <
                        div className = "pic" >
                        <
                        img className = {
                            cx("pay-img object-fit-scale", {
                                bigSymbol: symbol.some(
                                    (i) => i === v.get("SymbolName")
                                ),
                                mobileSize: windowDimensions.width < 375,
                            })
                        }
                        alt = ""
                        src = {
                            `${
                        imageDomain
                      }/order-detail/common/${gameId}/symbolList/${`${value}${k + 1}`}.png`
                        }
                        /> <
                        /div> <
                        div className = "list" > {
                            payTableData.get("math_data") &&
                            payTableData
                            .get("math_data")
                            .filter(
                                (i) => i.get("SymbolName") === `${value}${k + 1}`
                            )
                            .getIn([0, "SymbolPays"])
                            .reverse()
                            .map((a, b) => ( <
                                Fragment key = {
                                    uuidv4()
                                } > {
                                    a !== 0 && ( <
                                        div key = {
                                            uuidv4()
                                        } > {
                                            5 - b
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
                                                moneyConvert(a * denom * betLevel) :
                                                a * betLevel
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
                            !i.get("SymbolName").includes("_") &&
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
                                    bigSymbol: symbol.some(
                                        (i) => i === v.get("SymbolName")
                                    ),
                                    mobileSize: windowDimensions.width < 375,
                                })
                            }
                            alt = ""
                            src = {
                                `${
                            imageDomain
                          }/order-detail/common/${gameId}/symbolList/${v.get("SymbolName")}.png`
                            }
                            /> <
                            /div> <
                            div className = "list" > {
                                v
                                .get("SymbolPays")
                                .reverse()
                                .map((v, k, array = v.get("SymbolPays")) => ( <
                                    Fragment key = {
                                        uuidv4()
                                    } > {
                                        v !== 0 && ( <
                                            div key = {
                                                uuidv4()
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
                /PayTableWrapper> <
                /Fragment>
            ))
        } <
        />
    )
}

export default Challenge