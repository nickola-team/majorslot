import React, {
    Fragment
} from "react"
import cx from "classnames"
import TotalBet from "./TotalBet"
import {
    List
} from "immutable"
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
import {
    PayTableWrapper,
    PayList
} from "./Normal"

const TableTA29 = ({
    lang,
    windowDimensions,
    payTableData,
    gameId,
    isCurrency,
    currency,
    denom,
    betLevel,
    moneyConvert,
    bet,
}) => {
    const payTableList = List(["H1", "H2", "H3", "H4", "H5"])
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
            payTableList.map((v) => ( <
                div className = "half"
                key = {
                    v
                } >
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
                    `${imageDomain}/order-detail/common/${gameId}/symbolList/${v}.png`
                }
                /> <
                /div> <
                div className = "list" > {
                    payTableData
                    .get("math_data")
                    .filter((val) => val.get("SymbolName").includes(v))
                    .reverse()
                    .map((v, k) => ( <
                        Fragment key = {
                            k
                        } > {
                            v.getIn(["SymbolPays", 0]) !== 0 && ( <
                                div key = {
                                    k
                                } >
                                -
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
                                            v.getIn(["SymbolPays", 0]) * denom * betLevel
                                        ) :
                                        v.getIn(["SymbolPays", 0]) * betLevel
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

export default TableTA29