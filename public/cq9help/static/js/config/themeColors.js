const is168 = process.env.REACT_APP_SITE_TYPE === "168"

const Colors168 = {
    title: "#61beff",
    bar: "#261d49",
    barText: "#9592cf",
    border: "#372948",
    hr: "#333378",
    totalBetText: "#9592cf",
    cautions: "#7264a9",
}
const ColorsOriginal = {
    title: "#d88c33",
    bar: "#221a0e",
    barText: "#5c4f49",
    border: "#221a0e",
    hr: "#3a2c25",
    totalBetText: "#937645",
    cautions: "#6f5f57",
}
const themeColors = is168 ? Colors168 : ColorsOriginal

export default themeColors