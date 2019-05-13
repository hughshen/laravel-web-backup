class Auth {
    constructor() {
        this.token = window.localStorage.getItem('token');

        let user = window.localStorage.getItem('user');
        this.user = user ? JSON.parse(user) : null;

        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
            // this.profile();
        }
    }

    login (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        this.token = token;
        this.user = user;

        Event.$emit('userLoggedIn');
    }

    logout () {
        window.localStorage.removeItem('token');
        window.localStorage.removeItem('user');

        axios.defaults.headers.common['Authorization'] = null;

        this.token = null;
        this.user = null;

        Event.$emit('userLoggedOut');
    }

    profile() {
        api.call('get', 'profile')
            .then(({data}) => {
                this.user = data.data.user;
            });
    }

    check () {
        return !! this.token;
    }
}

export default Auth;