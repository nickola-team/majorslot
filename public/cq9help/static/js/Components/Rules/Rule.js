import React, {
    Fragment,
    useState
} from "react"
import styled from "styled-components"
import cx from "classnames"

// hooks
import BREAKPOINT from "../../config/breakpoint"
//lib
import {
    imageDomain,
    createSymbol
} from "common-lib/lib/helps"
//config
import {
    currencyList
} from "../../config/currencyList"

export const BlockCol = styled.div `
  width: 100%;
  display: flex;
  flex-direction: column;
  & .half {
    display: flex;
    flex-direction: column;
    align-items: center;
    &.m-row {
      flex-direction: ${(props) =>
        props.windowDimensions.width >= BREAKPOINT ? "column" : "row-reverse"};
      justify-content: center;
    }
    & .circle {
      display: flex;
      align-items: center;
      justify-content: center;
      border: 3px solid;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      &.correct {
        color: #45fe01;
        border-color: #45fe01;
      }
      &.incorrect {
        color: #f80701;
        border-color: #f80701;
      }
    }
  }
`
export const Wf = styled.div `
  display: flex;
  justify-content: center;
  align-items: center;
  height: ${(props) => props.img && (props.isHorizontal ? "200px" : "350px")};
  margin: 10px 0;
  & .half {
    display: flex;
    flex-direction: row;
    align-items: center;

    & .pic {
      width: 110px;
      padding: 20px 0;
      margin-right: 10px;
      text-align: center;
      .pay-img {
        width: 110px;
        height: 110px;
        image-rendering: -webkit-optimize-contrast;
      }
    }
    & .list {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-start;
      font-size: 24px;
      margin-left: 10px;
      & > div {
        margin: 3px 0;
      }
      & span {
        font-size: 24px;
        color: #ffd542;
        margin-left: 5px;
      }
    }
  }
`
const Rule = ({
    rule,
    createContent,
    gameId,
    payTableData,
    isCurrency,
    currency,
    moneyConvert,
    denom,
    betLevel,
    windowDimensions,
    lang,
    sify,
    tify,
}) => {
    const [isHorizontal, setIsHorizontal] = useState(true)
    const onLoad = (e) => {
        const ratio = e.target.naturalHeight / e.target.naturalWidth
        if (ratio > 1.2) {
            setIsHorizontal(false)
        }
    }
    return ( <
        > {
            rule.get("title") !== "+" && < hr / >
        } <
        BlockCol windowDimensions = {
            windowDimensions
        } > {
            rule.get("title") !== "+" && ( <
                p className = "title" > {
                    lang === "zh-tw" ?
                    tify(rule.get("title")) :
                        lang === "cn" ?
                        sify(rule.get("title")) :
                        rule.get("title")
                } <
                /p>
            )
        } {
            rule.getIn(["icon", "link"]) && ( <
                Wf img = {
                    rule.getIn(["icon", "link"])
                }
                isHorizontal = {
                    isHorizontal
                } >
                <
                img className = {
                    cx("object-fit-scale mb20 h100", {
                        w100: rule.getIn(["icon", "link"]) !== "F" &&
                            rule.getIn(["icon", "link"]) !== "W",
                    })
                }
                alt = ""
                onLoad = {
                    (e) => onLoad(e)
                }
                src = {
                    `${imageDomain}/order-detail/common/${gameId}/symbolList/${rule.getIn(
                ["icon", "link"]
              )}.png`
                }
                /> {
                    rule.getIn(["icon", "link"]) === "F" ?
                        payTableData.get("math_data") &&
                        payTableData
                        .get("math_data")
                        .filter(
                            (i) =>
                            (i.get("SymbolName") === "F" ||
                                i.get("SymbolName") === "SC") &&
                            !i.get("SymbolPays").every((v) => v === 0)
                        )
                        .map((v) => ( <
                            div className = "half"
                            key = {
                                v.get("SymbolID")
                            } >
                            <
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
                                            } - < span > {
                                                `${v}X`
                                            } < /span> <
                                            /div>
                                        )
                                    } <
                                    /Fragment>
                                ))
                            } <
                            /div> <
                            /div>
                        )) :
                        payTableData.get("math_data") &&
                        payTableData
                        .get("math_data")
                        .filter(
                            (i) =>
                            i.get("SymbolName") === rule.getIn(["icon", "link"]) &&
                            !i.get("SymbolPays").every((v) => v === 0)
                        )
                        .map((v) => ( <
                            div className = "half"
                            key = {
                                v.get("SymbolID")
                            } >
                            <
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
                /Wf>
            )
        }

        <
        div className = "rules" >
        <
        div dangerouslySetInnerHTML = {
            createContent(rule.get("content"), gameId)
        }
        /> <
        /div> <
        /BlockCol> <
        />
    )
}

export default Rule