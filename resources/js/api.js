class Api {
    constructor () {}

    call (requestType, url, data = null) {
        if (!url.startsWith('http') || url.startsWith('/')) {
            url = '/api/' + url;
        }

        return new Promise((resolve, reject) => {
            axios[requestType](url, data)
                .then(response => {
                    resolve(response);
                })
                .catch(({response}) => {
                    if (response.status === 401) {
                        auth.logout();
                    }
                    reject(response);
                });
        });
    }
}

export default Api;