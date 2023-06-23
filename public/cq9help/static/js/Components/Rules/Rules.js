import React from "react"

//components
import Rule from "./Rule"

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