import React, {
    useEffect
} from "react"
import styled from "styled-components"
import {
    localeList
} from "../config/localeList"
import {
    List
} from "immutable"
import cx from "classnames"
// import BREAKPOINT from "../config/breakpoint"
import {
    isMobile
} from "../lib"
const Selector = styled.div `
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  width: 50px;
  height: 40px;
  /* background-color: #2c2011; */
  right: 0px;
  margin-right: constant(safe-area-inset-right);
  margin-right: min(30px, env(safe-area-inset-right));
  bottom: 0px;
  cursor: pointer;
  transition: all 0.2s ease;
  z-index: 1;
  & > img {
    width: 33px;
    height: 21px;
    object-fit: contain;
  }
`
const Popup = styled.ul `
  position: fixed;
  /* width: 100px; */
  max-height: 70vh;
  overflow: scroll;
  right: 7px;
  margin-right: constant(safe-area-inset-right);
  margin-right: min(30px, env(safe-area-inset-right));
  bottom: 40px;
  border-radius: 5px;
  background-color: #dedede;
  z-index: 1;
  &::-webkit-scrollbar {
    display: none;
  }
  & > li {
    box-sizing: border-box;
    width: 100%;
    height: 35px;
    padding-right: 10px;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    cursor: pointer;
    border-bottom: 1px solid #c4c4c4;
    &:hover {
      background-color: rgba(0, 0, 0, 0.1);
    }
    & > img {
      height: 20px;
      width: 40px;
      margin: 0 5px;
      object-fit: contain;
    }
    & > p {
      color: #808080;
      &.current {
        color: #000;
      }
    }
  }
`
const LocaleSelector = ({
    lang,
    popupHandler,
    popupState,
    popupRef,
    langHandler,
    edited = List(),
    windowDimensions,
}) => {
    // 篩選有提供的語系
    const createLocaleList = () => {
        const found = []
        for (let i = 0; i < edited.size; i++) {
            found.push(
                Object.values(localeList).find(
                    (elm) => elm.key.toLowerCase() === edited.get(i).toLowerCase()
                )
            )
        }
        return found
    }
    useEffect(() => {
        let isOpen = false
        if (popupState) {
            isOpen = true
        }
        const rootStyle = document.getElementById("root").style
        rootStyle.overflowY = isOpen ? "hidden" : "scroll"
    }, [popupState])

    return ( <
        >
        <
        Selector onClick = {
            popupHandler
        }
        windowDimensions = {
            windowDimensions
        }
        isMobile = {
            isMobile
        } >
        <
        img alt = ""
        src = {
            localeList[lang]["img"]
        }
        /> <
        /Selector> {
            popupState && ( <
                Popup ref = {
                    popupRef
                }
                windowDimensions = {
                    windowDimensions
                }
                isMobile = {
                    isMobile
                } >
                {
                    createLocaleList().map((item) => ( <
                        li key = {
                            item.key
                        }
                        onClick = {
                            () => langHandler(item.key)
                        } >
                        <
                        img alt = ""
                        src = {
                            item.img
                        }
                        /> <
                        p className = {
                            cx({
                                current: item.key === lang
                            })
                        } > {
                            item.title
                        } < /p> <
                        /li>
                    ))
                } <
                /Popup>
            )
        } <
        />
    )
}

export default LocaleSelector