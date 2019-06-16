import { setAuthorization } from "./general";

export function login (credentials) {
    return new Promise((resolve, reject) => {
        axios.post('api/auth/login', credentials)
            .then(response => {
                setAuthorization(response.data.access_token);
                resolve(response.data);
            })
            .catch(error => {
                reject(error);
            })
    })
}

export function register (credentials) {
    return new Promise((resolve, reject) => {
        axios.post('api/auth/register', credentials)
            .then(response => {
                resolve(response.data);
            })
            .catch(error => {
                reject(error);
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