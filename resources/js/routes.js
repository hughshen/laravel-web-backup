import VueRouter from 'vue-router';

// Components
import Login from './views/Site/Login.vue';
import Config from './views/Site/Config.vue';
import NotFound from './views/Site/NotFound.vue';
import UserList from './views/User/Index.vue';
import UserEdit from './views/User/Edit.vue';
import UserCreate from './views/User/Create.vue';
import PostList from './views/Post/Index.vue';
import PostEdit from './views/Post/Edit.vue';
import PostCreate from './views/Post/Create';
import TermList from './views/Term/Index.vue';
import TermEdit from './views/Term/Edit.vue';
import TermCreate from './views/Term/Create.vue';

let routes = [
    {
        path: '/config',
        component: Config,
        meta: { middlewareAuth: true }
    },
    {
        path: '/users',
        component: UserList,
        meta: { middlewareAuth: true }
    },
    {
        path: '/users/edit/:id',
        component: UserEdit,
        meta: { middlewareAuth: true }
    },
    {
        path: '/users/create',
        component: UserCreate,
        meta: { middlewareAuth: true }
    },
    {
        path: '/posts',
        component: PostList,
        meta: { middlewareAuth: true }
    },
    {
        path: '/posts/edit/:id',
        component: PostEdit,
        meta: { middlewareAuth: true }
    },
    {
        path: '/posts/create',
        component: PostCreate,
        meta: { middlewareAuth: true }
    },
    {
        path: '/terms',
        component: TermList,
        meta: { middlewareAuth: true }
    },
    {
        path: '/terms/edit/:id',
        component: TermEdit,
        meta: { middlewareAuth: true }
    },
    {
        path: '/terms/create',
        component: TermCreate,
        meta: { middlewareAuth: true }
    },
    {
        path: '/404',
        component: NotFound,
        meta: { middlewareAuth: true }
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '*',
        redirect: '/404',
        meta: { middlewareAuth: true }
    }
];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {
        if (!auth.check()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });

            return;
        }
    }

    next();
})

export default router;