import axios from 'axios'
import {
    API_URL
} from "./config/getUrl"
axios.defaults.withCredentials = true

const TOKEN = "fbfbf3a5a4d168940fa2c0516725439ef4f5d46fa6805653b2dc59308de65f0e"
export const apiGetHelp = (gameId, lang) => {
    return axios.get(
        `${API_URL}/frontend/help/?game_id=${gameId}&lang=${lang}`, {
            headers: {
                "token": TOKEN
            }
        }
    )
}

export const apiGetPayTable = (gameId) => {
    return axios.get(
        `${API_URL}/frontend/pay_table/?game_id=${gameId}`, {
            headers: {
                "token": TOKEN
            }
        }
    )
}