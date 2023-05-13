import { createRouter, createWebHistory } from "vue-router";
import LoginPage from "./components/LoginPage.vue";
import LoggedPage from "./components/LoggedPage.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "LoginPage",
      props: true,
      component: LoginPage,
    },
    {
      path: "/loggedin",
      name: "loggedin",
      state: {},
      component: LoggedPage,
    },
  ],
});

export default router;
