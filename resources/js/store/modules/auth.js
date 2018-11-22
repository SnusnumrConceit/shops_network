export default {
    namespaced: true,

    state: {
        token: localStorage.getItem('token') || '',
        user: {}
    },

    mutations: {
        set(state, { type_state, value }) {
            state[type_state] = value;
        }
    },

    actions: {
        setUser({commit}, value) {
            commit('set', {
                type_state: 'user',
                value: value
            })
        },

        setToken({commit}) {
            commit('set', {
                type_state: 'token',
                value: localStorage.getItem('token') || ''
            })
        }
    },

    getters: {
        //isAuthenticated: state => !!state,
        isAuthenticated(state) {
            return !!state.token;
        },

        getUser(state) {
            return state.user;
        }
    }
}