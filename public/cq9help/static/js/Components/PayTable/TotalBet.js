import React from "react"
import styled from "styled-components"
import translation from "../../config/translation"
import {
    currencyList
} from "../../config/currencyList"
import {
    createSymbol
} from "common-lib/lib/helps"

const TotalBetWrapper = styled.div `
  display: flex;
  align-items: center;
  position: relative;
  height: 40px;
  width: 100%;
  top: -30px;

  & .totalBet-bg {
    position: absolute;
    right: 0px;
    top: 0px;
    padding: 5px 10px;
    border-radius: 16px;
    border: ${(props) => `2px solid ${props.theme.colors.border}`};
    font-size: 16px;
    color: ${(props) => props.theme.colors.totalBetText};
    & > div {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    & .symbol {
      color: #ffd542;
      height: 13px;
    }
    & .total {
      color: #ffd542;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: 10px;
    }
  }
`
const TotalBet = ({
    lang,
    isCurrency,
    currency,
    bet,
    denom
}) => {
    return ( <
        TotalBetWrapper >
        <
        div className = "totalBet-bg" >
        <
        div > {
            `${translation["totalBet"][lang]}`
        } <
        span className = "total" > {
            isCurrency ? ( <
                >
                <
                div className = "symbol"
                dangerouslySetInnerHTML = {
                    createSymbol(
                        currencyList[currency]["symbol"]
                    )
                }
                /> {
                    (bet * 1).toFixed(2)
                } <
                />
            ) : (
                `${bet / denom}`
            )
        } <
        /span> <
        /div> <
        /div> <
        /TotalBetWrapper>
    )
}

export default TotalBet