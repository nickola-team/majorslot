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
// styled
import {
    Wf,
    BlockCol
} from "./Rule"

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
                img className = "object-fit-scale mb20 h100"
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
                    rule.getIn(["icon", "link"]) === "W_S" ?
                        payTableData.get("math_data") &&
                        payTableData
                        .get("math_data")
                        .filter(
                            (i) =>
                            i.get("SymbolName") === "SC" &&
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

const Rules = (props) => {
    const {
        helpData
    } = props
    return ( <
        > {
            helpData.get("data").map((rule, k) => ( <
                Rule rule = {
                    rule
                }
                key = {
                    k
                } { ...props
                }
                />
            ))
        } <
        />
    )
}

export default Rules