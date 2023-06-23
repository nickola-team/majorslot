import {
    linesList,
    waysList
} from "../config/waysLinesList"

const waysOrLines = (value) => {
    const isLines = linesList.includes(value)
    const isWays = waysList.includes(value)

    let ans = ""
    if (isWays) {
        ans = "ways"
    } else if (isLines) {
        ans = "lines"
    }
    return ans
}

export default waysOrLines