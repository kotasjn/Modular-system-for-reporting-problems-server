export function login (credentials) {
    return new Promise((res, rej) => {
        axios.post('/auth/login', credentials)
            .then((response) => {
                res(response.data);
            })
            .catch((err) => {
                rej("Wrong credentials")
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