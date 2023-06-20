import React, {
    Fragment,
    useState
} from "react"
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

const Rule = ({
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
    const [isHorizontal, setIsHorizontal] = useState(true)
    const onLoad = (e) => {
        const ratio = e.target.naturalHeight / e.target.naturalWidth
        if (ratio > 1.2) {
            setIsHorizontal(false)
        }
    }
    return ( <
        >
        <
        BlockCol windowDimensions = {
            windowDimensions
        } >
        <
        p className = "title" > {
            lang === "zh-tw" ?
            tify(helpData.getIn(["data", 0, "title"])) :
                lang === "cn" ?
                sify(helpData.getIn(["data", 0, "title"])) :
                helpData.getIn(["data", 0, "title"])
        } <
        /p> {
            helpData.getIn(["data", 0, "icon", "link"]) && ( <
                Wf img = {
                    helpData.getIn(["data", 0, "icon", "link"])
                }
                isHorizontal = {
                    isHorizontal
                } >
                <
                img className = {
                    cx("object-fit-scale mb20 h100", {
                        w100: helpData.getIn(["data", 0, "icon", "link"]) !== "F" &&
                            helpData.getIn(["data", 0, "icon", "link"]) !== "W",
                    })
                }
                alt = ""
                onLoad = {
                    (e) => onLoad(e)
                }
                src = {
                    `${imageDomain}/order-detail/common/${gameId}/symbolList/${helpData.getIn(
                ["data", 0, "icon", "link"]
              )}.png`
                }
                /> {
                    helpData.getIn(["data", 0, "icon", "link"]) === "F" ?
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
                            i.get("SymbolName") ===
                            helpData.getIn(["data", 0, "icon", "link"]) &&
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
        } <
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
        /BlockCol> {
            helpData
                .get("data")
                .shift()
                .map((rule, idx) => ( <
                    Fragment key = {
                        idx
                    } > {
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
                                `${
                      imageDomain
                    }/order-detail/common/${gameId}/symbolList/${rule.getIn(["icon", "link"])}.png`
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
                                        i.get("SymbolName") ===
                                        rule.getIn(["icon", "link"]) &&
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
                    div className = "rules" >
                    <
                    div dangerouslySetInnerHTML = {
                        createContent(
                            rule.get("content"),
                            gameId
                        )
                    }
                    /> <
                    /div> <
                    /BlockCol> <
                    /Fragment>
                ))
        } <
        />
    )
}

export default Rule