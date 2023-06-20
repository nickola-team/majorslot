import React, {
    Fragment
} from "react"
import styled from "styled-components"
import cx from "classnames"
import {
    sify,
    tify
} from "chinese-conv"

// hooks
import BREAKPOINT from "../config/breakpoint"
// lib
import {
    imageDomain,
    createSymbol
} from "common-lib/lib/helps"
// config
import {
    currencyList
} from "../config/currencyList"

const WildFreeWrapper = styled.div `
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  flex-direction: ${(props) =>
    props.windowDimensions.width >= BREAKPOINT ? "row" : "column"};
  & .wildfree-rules {
    width: ${(props) => (props.wildFreeCount === 2 ? "80%" : "100%")};
    @media (max-width: 699px) {
      width: 100%;
    }
  }

  & .half {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    &.continue {
      margin-top: 0;
    }
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
const Wf = styled.div `
  display: flex;
  justify-content: center;
  height: 140px;
  ${(props) =>
    (props.gameId === "133" || props.gameId === "TA2") &&
    props.isImg === "free_game_feature" &&
    "height:auto;"}
  ${(props) =>
    (props.gameId === "133" || props.gameId === "TA2") &&
    props.isImg === "free_game_feature"
      ? props.windowDimensions.width < 700
        ? "width:100%;"
        : "width:60%;"
      : ""}
  margin: 10px 0;
  & .mw100 {
    max-width: 100%;
  }
  & .mw200px {
    max-width: 200px;
  }
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
const WildFree = ({
    helpData,
    windowDimensions,
    createContent,
    gameId,
    payTableData,
    isCurrency,
    moneyConvert,
    denom,
    betLevel,
    currency,
    lang,
}) => {
    let wildFreeCount = 0
    for (let i = 0; i < helpData.getIn(["default_data"]).size; i++) {
        const link = helpData.getIn(["default_data", i, "icon", "link"])
        if (
            link === "F" ||
            link === "W" ||
            link === "W_S" ||
            link === "super_wild"
        ) {
            wildFreeCount += 1
        }
    }
    return ( <
        >
        <
        WildFreeWrapper windowDimensions = {
            windowDimensions
        }
        wildFreeCount = {
            wildFreeCount
        } >
        {
            helpData.get("default_data") !== "" &&
            helpData.get("default_data").map((v, k) => ( <
                div key = {
                    k
                }
                className = {
                    cx("flex-column aic", {
                        continue: v.get("title") === "+",
                        w50:
                            (wildFreeCount === 2 &&
                                (v.getIn(["icon", "link"]) === "F" ||
                                    v.getIn(["icon", "link"]) === "W" ||
                                    v.getIn(["icon", "link"]) === "W_S" ||
                                    v.getIn(["icon", "link"]) === "super_wild") &&
                                windowDimensions.width >= 700) ||
                            // id 242 特殊排列
                            (gameId === "242" &&
                                k !== 0 &&
                                windowDimensions.width >= 700),
                        w100:
                            !(
                                v.getIn(["icon", "link"]) === "F" ||
                                v.getIn(["icon", "link"]) === "W" ||
                                v.getIn(["icon", "link"]) === "W_S" ||
                                v.getIn(["icon", "link"]) === "super_wild" ||
                                // id 242 特殊排列
                                (gameId === "242" &&
                                    v.getIn(["icon", "link"]) === "random_wild")
                            ) ||
                            // id 242 特殊排列
                            (gameId === "242" && k === 0) ||
                            wildFreeCount !== 2 ||
                            windowDimensions.width < 700,
                        "mt-60": wildFreeCount === 2 &&
                            (helpData.getIn(["default_data", 0]) !== "F" ||
                                helpData.getIn(["default_data", 0]) !== "W" ||
                                helpData.getIn(["default_data", 0]) !== "W_S" ||
                                helpData.getIn(["default_data", 0]) !== "super_wild") &&
                            windowDimensions.width >= 700,
                    })
                } >
                {
                    v.get("title") !== "+" && ( <
                        p className = "title" > {
                            lang === "zh-tw" ?
                            tify(v.get("title")) :
                                lang === "cn" ?
                                sify(v.get("title")) :
                                v.get("title")
                        } <
                        /p>
                    )
                } {
                    v.getIn(["icon", "link"]) && ( <
                        Wf wildFreeCount = {
                            wildFreeCount
                        }
                        isImg = {
                            v.getIn(["icon", "link"])
                        }
                        gameId = {
                            gameId
                        }
                        windowDimensions = {
                            windowDimensions
                        } >
                        <
                        img className = "h100 object-fit-contain mw200px"
                        alt = ""
                        src = {
                            `${imageDomain}/order-detail/common/${gameId}/symbolList/${v.getIn(
                      ["icon", "link"]
                    )}.png`
                        }
                        /> {
                            v.getIn(["icon", "link"]) === "F" ||
                                (v.getIn(["icon", "link"]) === "W_S" && gameId === "199") ?
                                payTableData.get("math_data") &&
                                payTableData
                                .get("math_data")
                                .filter(
                                    (i) =>
                                    (i.get("SymbolName") === "F" ||
                                        i.get("SymbolName") === "SC") &&
                                    !i.get("SymbolPays").every((v) => v === 0)
                                )
                                .slice(0, 1)
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
                                    i.get("SymbolName") === v.getIn(["icon", "link"]) &&
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
                                                    } >
                                                    {
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
                div className = "rules wildfree-rules" > {
                    helpData.size !== 0 && helpData.get("default_data") && ( <
                        div dangerouslySetInnerHTML = {
                            createContent(
                                v.get("content"),
                                gameId
                            )
                        }
                        />
                    )
                } <
                /div> <
                /div>
            ))
        } <
        /WildFreeWrapper> <
        />
    )
}

export default WildFree