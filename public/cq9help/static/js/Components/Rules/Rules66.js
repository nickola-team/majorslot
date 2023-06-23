import React, {
    Fragment,
    useEffect,
    useState
} from "react"
import styled from "styled-components"
import cx from "classnames"
import {
    List
} from "immutable"
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
import translation from "../../config/translation"
//style
import {
    BlockCol
} from "./Rule"

const FreeGamePayTable = styled.div `
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  .item {
    display: flex;
    flex-direction: ${(props) =>
      props.windowDimensions.width >= 1024 ? "column" : "row"};
    width: ${(props) =>
      props.windowDimensions.width >= 1024
        ? "20%"
        : props.windowDimensions.width >= BREAKPOINT
        ? "50%"
        : "100%"};
    .object-fit-scale {
      width: 112px;
      height: 112px;
    }
    .list {
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
      .any {
        font-size: 18px;
        white-space: nowrap;
      }
    }
    .half {
      align-items: flex-start;
    }
  }
`
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
    const [freeGamePayTableArray, setFreeGamePayTableArray] = useState(List())
    const [scData, setScData] = useState(List())
    useEffect(() => {
        const freeGamePayTableList = ["H5", "N4", "N5", "H8"]

        if (payTableData.get("math_data")) {
            const array = payTableData
                .get("math_data")
                .filter((i) => freeGamePayTableList.includes(i.get("SymbolName")))
            const data = payTableData
                .get("math_data")
                .filter((i) => i.get("SymbolName") === "SC")

            setFreeGamePayTableArray(array)
            setScData(data)
        }
    }, [payTableData])

    return ( <
        >
        <
        hr / >
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
        /p> <
        div className = "rules" >
        <
        div dangerouslySetInnerHTML = {
            createContent(
                helpData.getIn(["data", 0, "content"]),
                gameId
            )
        }
        /> { /* free game pay table */ } <
        FreeGamePayTable windowDimensions = {
            windowDimensions
        } > {
            freeGamePayTableArray.map((v, i) => ( <
                div className = "item" >
                <
                img className = "object-fit-scale"
                alt = ""
                src = {
                    `${imageDomain}/order-detail/common/${gameId}/symbolList/${v.get(
                    "SymbolName"
                  )}.png`
                }
                /> <
                div className = "half" >
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
                                div className = {
                                    i === 3 ? "any" : ""
                                }
                                key = {
                                    k
                                } > {
                                    i === 3 && translation["any"][lang]
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
        } {
            scData.map((v, i) => ( <
                div className = "item" >
                <
                img className = "object-fit-scale"
                alt = ""
                src = {
                    `${imageDomain}/order-detail/common/${gameId}/symbolList/${v.get(
                    "SymbolName"
                  )}.png`
                }
                /> <
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
            ))
        } <
        /FreeGamePayTable>

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

export default Rule