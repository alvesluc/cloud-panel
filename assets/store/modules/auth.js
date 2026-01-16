import Vue from "vue";

export default {
    namespaced: true,
    state: {
        token: localStorage.getItem("jwt_token") || null,
        refreshToken: localStorage.getItem("refresh_token") || null,
    },
    mutations: {
        SET_TOKENS(state, { token, refresh_token }) {
            state.token = token;
            state.refreshToken = refresh_token;
            localStorage.setItem("jwt_token", token);
            localStorage.setItem("refresh_token", refresh_token);
        },
        CLEAR_AUTH(state) {
            state.token = null;
            state.refreshToken = null;
            localStorage.removeItem("jwt_token");
            localStorage.removeItem("refresh_token");
        },
    },
    actions: {
        async login({ commit }, credentials) {
            const response = await Vue.http.post("/api/sign-in", credentials);
            const data = response.body;

            commit("SET_TOKENS", {
                token: data.token,
                refresh_token: data.refresh_token,
            });
        },
        async refresh({ commit, state }) {
            if (!state.refreshToken) throw new Error("No refresh token");

            const response = await Vue.http.post("/api/token/refresh", {
                refresh_token: state.refreshToken,
            });

            const data = response.body;
            commit("SET_TOKENS", {
                token: data.token,
                refresh_token: data.refresh_token,
            });
            return data.token;
        },
        logout({ commit }) {
            commit("CLEAR_AUTH");
        },
    },
    getters: {
        isAuthenticated: (state) => !!state.token,
    },
};
