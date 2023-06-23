import React from "react"
import styled from "styled-components"
// config
import BREAKPOINT from "../config/breakpoint"
import translation from "../config/translation"
import {
    ImCheckmark,
    ImCross
} from "react-icons/im"
import waysOrLines from "../lib/waysOrLines"
import {
    imageDomain
} from "common-lib/lib/helps"
const WaysWrapper = styled.div `
  width: 100%;
  display: flex;
  flex-direction: column;
`
const BlockRow = styled.div `
  width: 100%;
  display: flex;
  flex-direction: ${(props) =>
    props.windowDimensions.width >= BREAKPOINT ? "row" : "column"};
  & .half {
    width: 100%;
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
  & > div:not(:first-child) {
    margin-top: ${(props) =>
      props.windowDimensions.width >= BREAKPOINT ? "0" : "40px"};
  }
`
const Ways = ({
    helpData,
    lang,
    windowDimensions,
    createContent,
    gameId
}) => {
    const noHrId = ["155", "76", "95", "GB5032"]
    const isHr = !noHrId.includes(gameId)
    return ( <
        > {
            isHr && < hr / >
        } {
            helpData.get("line") && waysOrLines(helpData.get("line")) === "ways" ? ( <
                >
                <
                WaysWrapper >
                <
                p className = "title" > {
                    helpData.get("line").includes("ways") ?
                    helpData.get("line") === "richways" ?
                    translation["richways"][lang] :
                    translation["reelways"][lang] :
                        helpData.get("line").includes("any") ?
                        translation[helpData.get("line")][lang] :
                        `${helpData.get("line")} ${translation["ways"][lang]}`
                } <
                /p> <
                div className = "rules" > {
                    helpData.getIn(["line_content", "data"]) && ( <
                        div dangerouslySetInnerHTML = {
                            createContent(
                                helpData.getIn(["line_content", "data"]),
                                gameId
                            )
                        }
                        />
                    )
                } <
                /div> <
                BlockRow windowDimensions = {
                    windowDimensions
                } >
                <
                div className = "half m-row" >
                <
                div className = "circle correct" >
                <
                ImCheckmark / >
                <
                /div> <
                img className = "ways-img max300 m-10 object-fit-contain"
                alt = ""
                src = {
                    `${imageDomain}/help/admin/slot/line/${helpData.get(
                    "line"
                  )}c.png`
                }
                /> <
                /div> <
                div className = "half m-row" >
                <
                div className = "circle incorrect" >
                <
                ImCross / >
                <
                /div> <
                img className = "ways-img max300 m-10 object-fit-contain"
                alt = ""
                src = {
                    `${imageDomain}/help/admin/slot/line/${helpData.get(
                    "line"
                  )}e.png`
                }
                /> <
                /div> <
                /BlockRow> <
                /WaysWrapper> <
                />
            ) : ( <
                >
                <
                WaysWrapper >
                <
                p className = "title" > {
                    helpData.get("line").split("_")[0]
                } {
                    " "
                } {
                    helpData.get("line") === "1" ?
                        translation["line"][lang] :
                        translation["lines"][lang]
                } <
                /p> <
                div className = "rules" > {
                    helpData.getIn(["line_content", "data"]) && ( <
                        div dangerouslySetInnerHTML = {
                            createContent(
                                helpData.getIn(["line_content", "data"]),
                                gameId
                            )
                        }
                        />
                    )
                } <
                /div> <
                BlockRow className = "jcc"
                windowDimensions = {
                    windowDimensions
                } >
                <
                div className = "w100" >
                <
                img className = "object-fit-scale m-w100 object-top"
                alt = ""
                src = {
                    `${imageDomain}/help/admin/slot/line/${helpData.get(
                    "line"
                  )}.png`
                }
                /> <
                /div> <
                /BlockRow> <
                /WaysWrapper> <
                />
            )
        } <
        />
    )
}

export default Ways