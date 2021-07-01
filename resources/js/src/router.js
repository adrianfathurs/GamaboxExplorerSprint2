/*=========================================================================================
  File Name: router.js
  Description: Routes for vue-router. Lazy loading is enabled.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import store from '@/store/store.js'
import Vue from 'vue'
import Router from 'vue-router'
import NProgress from 'nprogress'

Vue.use(Router)

const router = new Router({
    mode: "history",
    base: "/",
    scrollBehavior() {
        return {
            x: 0,
            y: 0
        };
    },
    routes: [{
            // =============================================================================
            // MAIN LAYOUT ROUTES
            // =============================================================================
            path: "",
            component: () => import("./layouts/main/Main.vue"),
            children: [
                // =============================================================================
                // Theme Routes
                // =============================================================================
                {
                    path: "/",
                    name: "home",
                    component: () => import("./views/Home.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/dataset",
                    name: "dataset",
                    component: () => import("./views/Dataset.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/mydataset",
                    name: "mydataset",
                    component: () => import("./views/MyDataset.vue"),
                    meta: {
                        rule: "free",
                        authRequired: true,
                        emailRequired: true
                    }
                },
                {
                    path: "/insight",
                    name: "insight",
                    component: () => import("./views/AboutUs.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/mapdetail",
                    name: "mapdetail",
                    component: () => import("./views/Map/Map.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/map",
                    name: "map",
                    component: () => import("./views/Map/MapAll.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/maploading",
                    name: "MapLoading",
                    component: () => import("./views/Map/MapLoading.vue")
                },
                {
                    path: "/analytic",
                    name: "analytic",
                    component: () => import("./views/Analytic/Analytic.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/detailedpage",
                    name: "detailed-page",
                    component: () => import("./views/DetailedPage.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/adminpage",
                    name: "admin-page",
                    component: () => import("./views/AdminPage.vue"),
                    meta: {
                        rule: "free",
                        authRequired: true,
                        adminRequired: true
                    }
                },
                {
                    path: "/superuserpage",
                    name: "superuser-page",
                    component: () => import("./views/SuperuserPage.vue"),
                    meta: {
                        rule: "free",
                        authRequired: true,
                        superuserRequired: true
                    }
                },
                {
                    path: "/profile",
                    name: "profile-page",
                    component: () => import("./views/UserProfile.vue"),
                    meta: {
                        rule: "free",
                        authRequired: true
                    }
                },
                {
                    path: "/dashboard",
                    name: "dashboard",
                    component: () => import("./views/CDMS.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/detailedArticle",
                    name: "detailedArticle-page",

                    component: () => import("./views/DetailedArticle.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                }
            ]
        },
        // =============================================================================
        // FULL PAGE LAYOUTS
        // =============================================================================
        {
            path: "",
            component: () => import("@/layouts/full-page/FullPage.vue"),
            children: [
                // =============================================================================
                // PAGES
                // =============================================================================
                {
                    path: "/pages/login",
                    name: "page-login",
                    component: () => import("@/views/pages/Login fire.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/pages/verify_email",
                    name: "page-verify",
                    component: () => import("@/views/pages/VerifyEmail.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/pages/register",
                    name: "page-register",
                    component: () => import("@/views/pages/Register fire.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/pages/error-404",
                    name: "page-error-404",
                    component: () => import("@/views/pages/Error404.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/pages/unverified",
                    name: "unverified",
                    component: () =>
                        import("@/views/pages/Emailnotverified.vue"),
                    meta: {
                        rule: "public",
                        authRequired: false
                    }
                },
                {
                    path: "/pages/forgot-password",
                    name: "page-forgot-password",
                    component: () => import("@/views/pages/ForgotPassword.vue"),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/reset-password/:token",
                    name: "page-reset-password",
                    component: () => import("@/views/pages/ResetPassword.vue"),
                    meta: {
                        rule: "editor"
                    }
                }
            ]
        },
        // Redirect to 404 page, if no match found
        {
            path: "/pages/error-404",
            name: "page-error-404",
            component: () => import("@/views/pages/Error404.vue"),
            meta: {
                rule: "public",
                authRequired: false
            }
        },
        {
            path: "/pages/unverified",
            name: "unverified",
            component: () => import("@/views/pages/Emailnotverified.vue"),
            meta: {
                rule: "public",
                authRequired: false
            }
        },
        {
            path: "/pages/forgot-password",
            name: "page-forgot-password",
            component: () => import("@/views/pages/ForgotPassword.vue"),
            meta: {
                rule: "editor"
            }
        },
        {
            path: "/reset-password/:token",
            name: "page-reset-password",
            component: () => import("@/views/pages/ResetPassword.vue"),
            meta: {
                rule: "editor"
            }
        },
        {
            path: "/auth/:provider/callback",
            component: {
                template: '<div class="auth-component"></div>'
            }
        }
    ]
});

router.afterEach(() => {
    // Remove initial loading
    NProgress.done()
    // const appLoading = document.getElementById('loading-bg')
    // if (appLoading) {
    //   appLoading.style.display = 'none'
    // }
})

router.beforeEach((to, from, next) => {
    NProgress.start()


    var userInfo = JSON.parse(localStorage.getItem('userInfo'));
    if (!userInfo) {
        var userInfo = store.state.AppActiveUser;
    }
    console.log(userInfo);
    // If auth required, check login. If login fails redirect to login page
    if (to.meta.authRequired) {
        if (!userInfo.email) {
            router.push('/pages/error-404')
        }
    }

    if (to.meta.adminRequired) {
        if (userInfo.role !== 'admin') {
            router.push('/pages/error-404')
        }
    }

    if (to.meta.superuserRequired) {
        if (userInfo.role !== 'superuser') {
            router.push('/pages/error-404')
        }
    }

    if (to.meta.emailRequired) {
        if (to.name == 'mydataset') {
            if (!userInfo.emailVerified) {
                router.push('/pages/unverified')
            }
        } else if (!userInfo.emailVerified && userInfo.role === 'user') {
            router.push('/pages/error-404')
        }
    }


    // if ((to.path == '/pages/register' || to.path == '/pages/login')) {
    //   if (store.state.AppActiveUser.email) {
    //       router.push('/')
    //   }
    // }

    return next()
    // Specify the current path as the customState parameter, meaning it
    // will be returned to the application after auth
    // auth.login({ target: to.path });
})


export default router
