import { setAuthorization } from "./general";

export function login (credentials) {
    return new Promise((res, rej) => {
        axios.post('api/auth/login', credentials)
            .then((response) => {
                setAuthorization(response.data.access_token);
                res(response.data);
            })
            .catch((err) => {
                rej("Špatné přihlašovací údaje")
            })
    })
}

export function register (credentials) {
    return new Promise((res, rej) => {
        axios.post('api/auth/register', credentials)
            .then((response) => {
                res(response.data);
            })
            .catch((err) => {
                rej("Špatné nebo chybějící údaje")
            })
    })
}

export function getLocalUser () {
    const userStr = localStorage.getItem("user");

    if(!userStr) {
        return null;
    }

    return JSON.parse(userStr);
}