import React, {
    useState,
    useRef,
    useEffect
} from "react";
import styled, {
    ThemeProvider
} from "styled-components";
import {
    Map,
    fromJS
} from "immutable";
import {
    sify,
    tify
} from "chinese-conv";
// lib
import {
    slotImgFetch
} from "./common-lib/lib/helps";
import song from "./audio/UI_Button.mp3";
import song_GB from "./audio/UI_Button_GB.mp3";
// hooks
import useGetQuery from "lib/hooks/useGetQuery";
// config
import translation from "./config/translation";
import BREAKPOINT from "./config/breakpoint";
import themeColors from "config/themeColors";
// components
import LocaleSelector from "./Components/LocaleSelector";
import WildFree from "./Components/WildFree";
import Rules from "./Components/Rules/Rules";
import Rules24 from "./Components/Rules/Rules24";
import Rules66 from "./Components/Rules/Rules66";
import Rules77 from "./Components/Rules/Rules77";
import Rules121 from "./Components/Rules/Rules121";
import Rules204 from "./Components/Rules/Rules204";
import RuleTA29 from "./Components/Rules/RuleTA29";
import RuleGB5015 from "./Components/Rules/RuleGB5015";
import Ways from "./Components/Ways";
import Normal from "./Components/PayTable/Normal";
import Pair from "./Components/PayTable/Pair";
import Table19 from "./Components/PayTable/Table19";
import Table20 from "./Components/PayTable/Table20";
import Table21 from "./Components/PayTable/Table21";
import Table22 from "./Components/PayTable/Table22";
import Table26 from "./Components/PayTable/Table26";
import Table32 from "./Components/PayTable/Table32";
import Table35 from "./Components/PayTable/Table35";
import Table47 from "./Components/PayTable/Table47";
import Table51 from "./Components/PayTable/Table51";
import Table66 from "./Components/PayTable/Table66";
import Table117 from "./Components/PayTable/Table117";
import Table128 from "./Components/PayTable/Table128";
import Table130 from "./Components/PayTable/Table130";
import Table133 from "./Components/PayTable/Table133";
import Table171 from "./Components/PayTable/Table171";
import Table192 from "./Components/PayTable/Table192";
import TableGB3 from "./Components/PayTable/TableGB3";
import TableGB5019 from "./Components/PayTable/TableGB5019";
import TableTA25 from "./Components/PayTable/TableTA25";
import TableTA29 from "./Components/PayTable/TableTA29";
import TableTA33 from "./Components/PayTable/TableTA33";
import Table242 from "./Components/PayTable/Table242";
import Table251 from "./Components/PayTable/Table251";
import Challenge from "./Components/PayTable/Challenge";
// hooks
import useDeviceOrientation from "./lib/hooks/useWindowDimensions";
import useOutsideClickAlert from "./lib/hooks/useOutsideClickAlert";

// api
import {
    apiGetHelp,
    apiGetPayTable
} from "./api";

// packageJson
import packageJson from "../package.json";

// styled components
const AppWrapper = styled.div `
  box-sizing: border-box;
  width: 100%;
  /* height: 100%; */
  min-height: 100vh;
  color: #fff;
  background-color: rgba(0, 0, 0, 0.95);
  & .symbol {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 18px;
    padding-right: 2px;
    color: #ffd542;
    & > img {
      height: 100%;
      object-fit: contain;
    }
  }
  .back-icon {
    position: fixed;
    font-size: 40px;
    bottom: 40px;
    left: 10px;
    cursor: pointer;
  }
  .table,
  .table-ckeditor {
    display: flex;
    align-self: center;
    justify-content: center;
    margin: 30px 0;
    & img {
      width: 50px;
      height: 50px;
      object-fit: scale-down;
    }
    table,
    tbody,
    tr,
    td {
      max-width: 100%;
    }
    td {
      padding: 0.4em;
      min-width: 2em;
      border: 1px solid #6393db;
    }
    td:first-child {
      background-color: #092b5e;
    }
    & > table {
      display: block;
      overflow: auto;
    }
  }
`;
const DefaultMsg = styled.div `
  box-sizing: border-box;
  width: 100vw;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
`;
const Content = styled.div `
  position: relative;
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  max-width: ${(props) =>
    props.windowDimensions.width >= BREAKPOINT ? "1024px" : "375px"};
  padding: ${(props) =>
    props.windowDimensions.width >= BREAKPOINT
      ? "40px 50px 80px"
      : "40px 35px 80px"};
  margin: 0 auto;
  font-size: 16px;
  text-align: center;
  .caution-hr {
    margin: 80px 0 40px;
  }
  hr {
    margin: 80px 0;
    border-color: ${(props) => props.theme.colors.hr};
  }
  .number {
    color: #ffff1a;
    margin: 0 5px;
  }
  .cautions {
    font-size: 16px;
    color: ${(props) => props.theme.colors.cautions};
    line-height: 28px;
  }
  .half .list span.money {
    font-size: ${(props) =>
      props.isMoneyOver6 ? (props.isMoneyOver7 ? "16px" : "18px") : "20px"};
    display: inline-flex;
    align-items: center;
    margin-left: 5px;
  }
  .title {
    font-size: 28px;
    color: ${(props) => props.theme.colors.title};
    margin-bottom: 20px;
    font-weight: bold;
  }
  .flex-column {
    display: flex;
    flex-direction: column;
  }

  .jcc {
    justify-content: center;
  }
  .aic {
    align-items: center;
  }
  .ways-img {
    width: 50%;
    @media (max-width: 666px) {
      width: 70%;
    }
  }
  .w50 {
    width: 50%;
    /* &:not(:nth-child(1)):not(:nth-child(2)) {
      margin-top: 60px;
    } */
  }
  .mt-60 {
    margin-top: 60px;
  }
  .bigSymbol {
    transform: scale(1.2);
  }
  .mobileSize {
    padding-right: 20px;
    box-sizing: border-box;
  }
  .max130 {
    max-width: 130px;
  }
  .h100px {
    height: 100px;
  }
  .h150px {
    height: 150px;
  }
  .h100 {
    height: 100%;
  }
  .w100px {
    width: 100px;
  }
  .w150px {
    width: 150px;
  }
  .max300 {
    max-width: 300px;
  }
  .my-10 {
    margin: 10px 0;
  }
  .m-10 {
    margin: 10px;
  }
  .mb20 {
    margin-bottom: 20px;
  }
  .object-fit-contain {
    object-fit: contain;
  }
  .object-fit-scale {
    object-fit: scale-down;
  }
  .object-fit-cover {
    object-fit: cover;
  }
  .object-top {
    object-position: top;
  }
  .w100 {
    width: 100%;
    &:not(:first-child) {
      margin-top: 60px;
    }
    &.continue {
      margin-top: 0;
    }
  }
  .w70 {
    width: 70%;
  }
  .m-w100 {
    max-width: 100%;
    @media (max-width: 666px) {
      width: 100%;
    }
  }
  .rules {
    text-align: left;
    margin-bottom: 20px;
    & .num {
      color: #ffff00;
    }
    p {
      position: relative;
      font-size: ${(props) =>
        props.windowDimensions.width >= BREAKPOINT ? "20px" : "16px"};
      line-height: 50px;
      padding-left: 20px;
      margin-bottom: 10px;
      /* height:40px; */
      -moz-text-size-adjust: none;
      -webkit-text-size-adjust: none;
      text-size-adjust: none;
      & img {
        object-fit: scale-down;
        /* width: 40px; */
        max-height: 50px;
        vertical-align: middle;
        /* margin: 0 3px; */
      }
      &::before {
        content: "◆";
        font-size: 14px;
        vertical-align: middle;
        position: absolute;
        left: 0px;
        height: 50px;
        display: flex;
        align-items: center;
      }
      &.indent {
        margin-left: 20px;
        &::before {
          content: "";
        }
      }
      &.no-dot {
        &::before {
          content: "";
        }
      }
      &.img-text {
        text-align: center;
        margin-bottom: 20px;
        padding-left: 0;
        &::before {
          content: "";
        }
      }
      &.highlight {
        text-align: center;
        margin-bottom: 20px;
        padding-left: 0;
        font-size: 28px;
        &::before {
          content: "";
        }
      }
    }
  }
`;
const GameName = styled.div `
  position: fixed;
  display: flex;
  align-items: center;
  justify-content: center;
  bottom: 0;
  width: 100%;
  height: 40px;
  background-color: ${(props) => props.theme.colors.bar};
  & p {
    font-size: 14px;
    color: ${(props) => props.theme.colors.barText};
    font-weight: bold;
    text-shadow: 0px 1px 0 rgba(0, 0, 0, 0.38);
  }
`;
const is168 = process.env.REACT_APP_SITE_TYPE === "168";

// main
function App() {
    const {
        defaultLang,
        gameId,
        denom,
        bet,
        betLevel,
        isSoundOn,
        currency,
        isCurrency,
    } = useGetQuery();
    const popupRef = useRef(null);
    const windowDimensions = useDeviceOrientation();
    const [isMaintain, setIsMaintain] = useState(false);
    const [isLoading, setIsLoading] = useState(true);
    const [lang, setLang] = useState(defaultLang);
    const [popupState, setPopupState] = useState(false);
    const [helpData, setHelpData] = useState(Map());
    const [payTableData, setPayTableData] = useState(Map());
    const [isMoneyOver6, setIsMoneyOver6] = useState(false);
    const [isMoneyOver7, setIsMoneyOver7] = useState(false);
    const longListGame = ["34", "136", "153", "184", "GB5001", "TA25"]; // SymbolPays length > 4

    const theme = {
        colors: themeColors,
    };
    const playSound = (song) => {
        let sound = new Audio(song);
        sound.play();
        sound.onended = () => {
            sound.remove();
            sound.srcObject = null;
        };
    };
    const popupHandler = () => {
        setPopupState(!popupState);
        if (isSoundOn) {
            if (gameId.includes("GB")) {
                if (gameId === "GB8" || gameId === "GB9" || gameId === "GB12") {
                    playSound(song);
                } else {
                    playSound(song_GB);
                }
            } else {
                playSound(song);
            }
        }
    };
    const langHandler = (lang) => {
        setLang(lang);
        if (isSoundOn) {
            if (gameId.includes("GB")) {
                if (gameId === "GB8" || gameId === "GB9" || gameId === "GB12") {
                    playSound(song);
                } else {
                    playSound(song_GB);
                }
            } else {
                playSound(song);
            }
        }
        setPopupState(false);
    };
    useOutsideClickAlert(popupRef, () => {
        setPopupState(false);
    });

    const createContent = (value, gameId) => {
        return {
            __html: slotImgFetch({
                html: lang === "zh-tw" ? tify(value) : lang === "cn" ? sify(value) : value,
                game_id: gameId,
            }),
        };
    };
    const moneyConvert = (money) => {
        const noNeedConvertList = ["cny", "usd", "thb", "krw"];
        const needConvert = !noNeedConvertList.includes(currency.toLowerCase()) &&
            money.toFixed(0).toString().length > 3;

        if (money.toFixed(0).toString().length >= 6 && !needConvert) {
            setIsMoneyOver6(true);
        }
        if (money.toFixed(0).toString().length >= 7 && !needConvert) {
            setIsMoneyOver7(true);
        }

        if (needConvert) {
            if (money.toFixed(0).toString().length > 6) {
                let num = `${money.toFixed(0).toString().slice(0, -6)}${
          money.toFixed(0).toString().slice(-6, -5) !== "0"
            ? `.${money.toFixed(0).toString().slice(-6, -5)}`
            : ".0"
        }${
          money.toFixed(0).toString().slice(-5, -4) !== "0"
            ? `${money.toFixed(0).toString().slice(-5, -4)}`
            : "0"
        }${
          money.toFixed(0).toString().slice(-4, -3) !== "0"
            ? `${money.toFixed(0).toString().slice(-4, -3)}`
            : "0"
        }${
          money.toFixed(0).toString().slice(-3, -2) !== "0"
            ? `${money.toFixed(0).toString().slice(-3, -2)}`
            : "0"
        }${
          money.toFixed(0).toString().slice(-2, -1) !== "0"
            ? `${money.toFixed(0).toString().slice(-2, -1)}`
            : "0"
        }${
          money.toFixed(0).toString().slice(-1) !== "0"
            ? `${money.toFixed(0).toString().slice(-1)}`
            : "0"
        }`;
                return `${num * 1}M`;
            } else {
                let num = `${money.toFixed(0).toString().slice(0, -3)}${
          money.toFixed(0).toString().slice(-3, -2) !== "0"
            ? `.${money.toFixed(0).toString().slice(-3, -2)}`
            : ".0"
        }${
          money.toFixed(0).toString().slice(-2, -1) !== "0"
            ? `${money.toFixed(0).toString().slice(-2, -1)}`
            : "0"
        }${
          money.toFixed(0).toString().slice(-1) !== "0"
            ? `${money.toFixed(0).toString().slice(-1)}`
            : "0"
        }`;
                return `${num * 1}K`;
            }
        } else {
            return money.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        }
    };
    const createPayTable = ({
        type,
        props
    }) => {
        switch (gameId) {
            case "19":
                return <Table19 { ...props
                }
                />;
            case "20":
                return <Table20 { ...props
                }
                />;
            case "21":
                return <Table21 { ...props
                }
                />;
            case "22":
                return <Table22 { ...props
                }
                />;
            case "26":
                return <Table26 { ...props
                }
                />;
            case "32":
                return <Table32 { ...props
                }
                />;
            case "35":
                return <Table35 { ...props
                }
                />;
            case "47":
                return <Table47 { ...props
                }
                />;
            case "51":
                return <Table51 { ...props
                }
                />;
            case "66":
                return <Table66 { ...props
                }
                />;
            case "117":
                return <Table117 { ...props
                }
                />;
            case "128":
                return <Table128 { ...props
                }
                />;
            case "130":
                return <Table130 { ...props
                }
                />;
            case "TA2":
            case "133":
                return <Table133 { ...props
                }
                />;
            case "171":
                return <Table171 { ...props
                }
                />;
            case "192":
                return <Table192 { ...props
                }
                />;
            case "242":
                return <Table242 { ...props
                }
                />;
            case "251":
                return <Table251 { ...props
                }
                />;
            case "GB3":
                return <TableGB3 { ...props
                }
                />;
            case "GB5019":
                return <TableGB5019 { ...props
                }
                />;
            case "TA25":
                return <TableTA25 { ...props
                }
                />;
            case "TA29":
                return <TableTA29 { ...props
                }
                />;
            case "TA33":
                return <TableTA33 { ...props
                }
                />;
            default:
                break;
        }
        switch (type) {
            case "normal":
                return <Normal { ...props
                }
                />;
            case "pair":
                return <Pair { ...props
                }
                />;
            case "challenge":
                return <Challenge { ...props
                }
                />;
            default:
                break;
        }
    };

    const createRules = ({
        gameId,
        props
    }) => {
        switch (gameId) {
            case "24":
                return <Rules24 { ...props
                }
                />;
            case "66":
                return <Rules66 { ...props
                }
                />;
            case "77":
                return <Rules77 { ...props
                }
                />;
            case "121":
                return <Rules121 { ...props
                }
                />;
            case "204":
                return <Rules204 { ...props
                }
                />;
            case "TA29":
                return <RuleTA29 { ...props
                }
                />;
            case "GB5015":
                return <RuleGB5015 { ...props
                }
                />;
            default:
                return <Rules { ...props
                }
                />;
        }
    };
    // disable right click
    document.addEventListener("contextmenu", (event) => event.preventDefault());

    // fetch api data
    useEffect(() => {
        if (gameId && lang) {
            apiGetHelp(gameId, lang)
                .then((res) => {
                    if (res.data.error_code === 1706006) {
                        setIsMaintain(true);
                    }
                    if (!res.data.result.edited.some((v) => v === lang)) {
                        setLang("en");
                    }
                    setHelpData(fromJS(res.data.result));
                })
                .then(() => {
                    setIsLoading(false);
                })
                .catch((err) => console.log(err));

            apiGetPayTable(gameId).then((res) =>
                setPayTableData(fromJS(res.data.result))
            );
        }
    }, [lang, gameId]);

    useEffect(() => {
        setLang(defaultLang);
    }, [defaultLang]);
    return ( <
        ThemeProvider theme = {
            theme
        } >
        <
        AppWrapper data - version = {
            packageJson.version
        } > {
            isLoading ? ( <
                DefaultMsg > Loading... < /DefaultMsg>
            ) : !isMaintain ? (
                helpData.get("data") && helpData.get("data") !== null ? ( <
                    >
                    <
                    Content windowDimensions = {
                        windowDimensions
                    }
                    isMoneyOver6 = {
                        isMoneyOver6
                    }
                    isMoneyOver7 = {
                        isMoneyOver7
                    } >
                    {
                        helpData.get("default_data") &&
                        helpData.get("default_data").size > 0 && ( <
                            WildFree lang = {
                                lang
                            }
                            helpData = {
                                helpData
                            }
                            windowDimensions = {
                                windowDimensions
                            }
                            createContent = {
                                createContent
                            }
                            gameId = {
                                gameId
                            }
                            payTableData = {
                                payTableData
                            }
                            isCurrency = {
                                isCurrency
                            }
                            currency = {
                                currency
                            }
                            denom = {
                                denom
                            }
                            betLevel = {
                                betLevel
                            }
                            moneyConvert = {
                                moneyConvert
                            }
                            />
                        )
                    } {
                        helpData.get("default_data") &&
                            helpData.get("default_data").size > 0 &&
                            helpData.getIn(["pay_table", "status"]) && < hr / >
                    } {
                        helpData.getIn(["pay_table", "status"]) &&
                            createPayTable({
                                type: helpData.getIn(["pay_table", "type"]),
                                props: {
                                    lang: lang,
                                    windowDimensions: windowDimensions,
                                    payTableData: payTableData,
                                    gameId: gameId,
                                    isCurrency: isCurrency,
                                    currency: currency,
                                    denom: denom,
                                    betLevel: betLevel,
                                    moneyConvert: moneyConvert,
                                    symbol: helpData.getIn(["pay_table", "symbol"]),
                                    bet: bet,
                                    isLongList: longListGame.includes(gameId),
                                },
                            })
                    } {
                        helpData.size !== 0 &&
                            helpData.get("data").size !== 0 &&
                            createRules({
                                gameId: gameId,
                                props: {
                                    lang: lang,
                                    helpData: helpData,
                                    betLevel: betLevel,
                                    denom: denom,
                                    moneyConvert: moneyConvert,
                                    currency: currency,
                                    isCurrency: isCurrency,
                                    payTableData: payTableData,
                                    createContent: createContent,
                                    gameId: gameId,
                                    windowDimensions: windowDimensions,
                                    sify: sify,
                                    tify: tify,
                                },
                            })
                    } {
                        helpData.get("line") !== "none" && ( <
                            Ways helpData = {
                                helpData
                            }
                            lang = {
                                lang
                            }
                            windowDimensions = {
                                windowDimensions
                            }
                            createContent = {
                                createContent
                            }
                            gameId = {
                                gameId
                            }
                            />
                        )
                    } <
                    hr className = "caution-hr" / >
                    <
                    p className = "cautions" > {
                        translation["cautions"][lang]
                    } < /p> <
                    /Content> {
                        !is168 && ( <
                            LocaleSelector lang = {
                                lang
                            }
                            popupHandler = {
                                popupHandler
                            }
                            popupState = {
                                popupState
                            }
                            langHandler = {
                                langHandler
                            }
                            popupRef = {
                                popupRef
                            }
                            edited = {
                                helpData.get("edited")
                            }
                            windowDimensions = {
                                windowDimensions
                            }
                            />
                        )
                    } {
                        helpData.get("game_title") && helpData.get("game_name") && ( <
                            GameName windowDimensions = {
                                windowDimensions
                            } > {
                                helpData.getIn(["game_name", lang.toLowerCase()]) && ( <
                                    p > {
                                        lang === "zh-tw" ?
                                        helpData.getIn(["game_name", "tw"]) :
                                            lang === "cn" ?
                                            sify(helpData.getIn(["game_name", "cn"])) :
                                            helpData.getIn(["game_name", lang.toLowerCase()]) ||
                                            helpData.getIn(["game_name", "en"]) ||
                                            sify(helpData.getIn(["game_name", "cn"]))
                                    } <
                                    /p>
                                )
                            } <
                            /GameName>
                        )
                    } <
                    />
                ) : ( <
                    DefaultMsg > Data Not Found < /DefaultMsg>
                )
            ) : ( <
                DefaultMsg > Maintaining... < /DefaultMsg>
            )
        } <
        /AppWrapper> <
        /ThemeProvider>
    );
}

export default App;