import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";
import HomeView from "./views/HomeView.vue";
import LoginView from "./views/LoginView.vue";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/login",
            name: "Login",
            component: LoginView,
            meta: { public: true },
        },
        {
            path: "/",
            name: "Home",
            component: HomeView,
        },
    ],
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = store.getters["auth/isAuthenticated"];
    const isPublic = to.matched.some((record) => record.meta.public);

    if (!isPublic && !isAuthenticated) {
        return next("/login");
    }

    if (isAuthenticated && to.path === "/login") {
        return next("/");
    }

    next();
});

export default router;
