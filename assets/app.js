import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import i18n from "./plugins/i18n";
import VueResource from "vue-resource";
import VCurrencyField from "v-currency-field";
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required, email } from "vee-validate/dist/rules";

Vue.use(VueResource);
Vue.http.options.root = "/api";

extend("required", required);
extend("email", email);
Vue.component("ValidationProvider", ValidationProvider);
Vue.component("ValidationObserver", ValidationObserver);

Vue.use(VCurrencyField, {
    locale: "pt-BR",
    decimalLength: 2,
    autoDecimalMode: true,
    defaultValue: 0,
});

Vue.http.interceptors.push((request, next) => {
    const token = store.state.auth.token;
    if (token) {
        request.headers.set("Authorization", `Bearer ${token}`);
    }

    next((response) => {
        if (response.status === 401) {
            if (
                request.url.includes("sign-in") ||
                request.url.includes("token/refresh")
            ) {
                store.dispatch("auth/logout");
                if (router.currentRoute.path !== "/login")
                    router.push("/login");
                return;
            }

            return store
                .dispatch("auth/refresh")
                .then((newToken) => {
                    request.headers.set("Authorization", `Bearer ${newToken}`);
                    return Vue.http(request);
                })
                .catch(() => {
                    store.dispatch("auth/logout");
                    router.push("/login");
                });
        }
    });
});

new Vue({
    router,
    store,
    vuetify,
    i18n,
    render: (h) => h(App),
}).$mount("#app");
