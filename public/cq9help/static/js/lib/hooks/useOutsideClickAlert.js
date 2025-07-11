import {
    useEffect
} from "react"
import noop from "lodash"

const useOutsideClickAlert = (ref, onOutsideClick = noop) => {
    useEffect(() => {
        function handleClickOutside(event) {
            if (ref.current && !ref.current.contains(event.target)) {
                onOutsideClick()
            }
        }
        // Bind the event listener
        document.addEventListener("mousedown", handleClickOutside)
        return () => {
            // Unbind the event listener on clean up
            document.removeEventListener("mousedown", handleClickOutside)
        }
    }, [onOutsideClick, ref])
}

export default useOutsideClickAlert