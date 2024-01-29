import React, {
    Fragment,
    useRef,
    useEffect,
    useState
} from "react"
import styled from "styled-components"
import cx from "classnames"
//lib
import {
    imageDomain,
    createSymbol
} from "common-lib/lib/helps"
//config
import {
    currencyList
} from "../../config/currencyList"
//style
import {
    BlockCol,
    Wf
} from "./Rule"

const FreeGame = styled.div `
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  height: ${(props) => props.img && (props.isHorizontal ? "200px" : "350px")};
  margin: 10px 0;
  & .free-span {
    font-weight: bold;
    font-size: 20px;
    display: flex;
    & > span {
      font-weight: normal;
      margin-left: 3px;
      color: rgb(255, 213, 66);
      display: flex;
      align-items: center;
    }
  }

  & .half {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 70%;
    justify-content: space-evenly;
    & > div {
      display: flex;
      align-items: center;
      padding: 0 5px;
    }
    & img {
      object-fit: scale-down;
      height: 40px;
      vertical-align: middle;
    }
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
  }
`
const Rules24 = ({
    helpData,
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
    const ruleImgRef = useRef()
    const [isHorizontal, setIsHorizontal] = useState(true)
    useEffect(() => {
        const ratio =
            ruleImgRef.current ? .naturalHeight / ruleImgRef.current ? .naturalWidth
        if (ratio > 1.2) {
            setIsHorizontal(false)
        }
    }, [ruleImgRef])
    return ( <
        > { /* part1 */ } {
            helpData.getIn(["data", 0, "title"]) !== "+" && < hr / >
        } <
        BlockCol windowDimensions = {
            windowDimensions
        } > {
            helpData.getIn(["data", 0, "title"]) !== "+" && ( <
                p className = "title" > {
                    lang === "zh-tw" ?
                    tify(helpData.getIn(["data", 0, "title"])) :
                        lang === "cn" ?
                        sify(helpData.getIn(["data", 0, "title"])) :
                        helpData.getIn(["data", 0, "title"])
                } <
                /p>
            )
        } <
        FreeGame img = {
            helpData.getIn(["data", 0, "icon", "link"])
        }
        isHorizontal = {
            isHorizontal
        } >
        {
            helpData.getIn(["data", 0, "icon", "link"]) && ( <
                img className = "object-fit-scale h100"
                alt = ""
                ref = {
                    ruleImgRef
                }
                src = {
                    `${imageDomain}/order-detail/common/${gameId}/symbolList/${helpData.getIn(
                ["data", 0, "icon", "link"]
              )}.png`
                }
                />
            )
        } <
        div className = "half list" >
        <
        div >
        <
        img className = "object-fit-scale"
        alt = ""
        ref = {
            ruleImgRef
        }
        src = {
            `${imageDomain}/order-detail/common/${gameId}/symbolList/F3.png`
        }
        /> <
        span className = "free-span" >
        1 -
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
                moneyConvert(
                    payTableData.getIn(["math_data", 1, "SymbolPays", 0]) *
                    denom *
                    betLevel
                ) :
                payTableData.getIn(["math_data", 1, "SymbolPays", 0]) *
                betLevel
        } <
        /span> <
        /span> <
        /div> <
        div >
        <
        img className = "object-fit-scale"
        alt = ""
        ref = {
            ruleImgRef
        }
        src = {
            `${imageDomain}/order-detail/common/${gameId}/symbolList/F2.png`
        }
        /> <
        span className = "free-span" >
        1 -
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
                moneyConvert(
                    payTableData.getIn(["math_data", 2, "SymbolPays", 0]) *
                    denom *
                    betLevel
                ) :
                payTableData.getIn(["math_data", 2, "SymbolPays", 0]) *
                betLevel
        } <
        /span> <
        /span> <
        /div> <
        /div> <
        /FreeGame> <
        div className = "rules" >
        <
        div dangerouslySetInnerHTML = {
            createContent(
                helpData.getIn(["data", 0, "content"]),
                gameId
            )
        }
        /> <
        /div> <
        /BlockCol> { /* part2 */ } {
            helpData.getIn(["data", 1, "title"]) !== "+" && < hr / >
        } <
        BlockCol windowDimensions = {
            windowDimensions
        } > {
            helpData.getIn(["data", 1, "title"]) !== "+" && ( <
                p className = "title" > {
                    lang === "zh-tw" ?
                    tify(helpData.getIn(["data", 1, "title"])) :
                        lang === "cn" ?
                        sify(helpData.getIn(["data", 1, "title"])) :
                        helpData.getIn(["data", 1, "title"])
                } <
                /p>
            )
        } {
            helpData.getIn(["data", 1, "icon", "link"]) && ( <
                Wf img = {
                    helpData.getIn(["data", 1, "icon", "link"])
                }
                isHorizontal = {
                    isHorizontal
                } >
                <
                img className = "object-fit-scale mb20 h100"
                alt = ""
                ref = {
                    ruleImgRef
                }
                src = {
                    `${imageDomain}/order-detail/common/${gameId}/symbolList/${helpData.getIn(
                ["data", 1, "icon", "link"]
              )}.png`
                }
                /> {
                    helpData.getIn(["data", 1, "icon", "link"]) === "F" ?
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
                        )) :
                        payTableData.get("math_data") &&
                        payTableData
                        .get("math_data")
                        .filter(
                            (i) =>
                            i.get("SymbolName") ===
                            helpData.getIn(["data", 1, "icon", "link"]) &&
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
            createContent(
                helpData.getIn(["data", 1, "content"]),
                gameId
            )
        }
        /> <
        /div> <
        /BlockCol> <
        />
    )
}

export default Rules24