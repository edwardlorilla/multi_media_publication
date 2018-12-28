require('./bootstrap');
window.Vue = require('vue');
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import VueRouter from 'vue-router'
Vue.use(ElementUI, {locale})
Vue.use(VueRouter)
const routes = [
    {
        path: '/',
        component: resolve => require(["./components/Article/index.vue"], resolve),
        children: [
            {
                path: 'articles',
                name: 'view-article',
                component: resolve => require(["./components/Article/view.vue"], resolve),
            },
            {
                path: 'articles/create',
                name: 'create-article',
                component: resolve => require(["./components/Article/create.vue"], resolve),
            },
            {
                path: 'articles/edit/:id',
                name: 'edit-article',
                component: resolve => require(["./components/Article/create.vue"], resolve),
            },
        ]
    },
    {
        path: '/books',
        component: resolve => require(["./components/book/index.vue"], resolve),
        children: [
            {
                path: '',
                name: 'view-book',
                component: resolve => require(["./components/book/view.vue"], resolve),
            },
            {
                path: 'create',
                name: 'create-book',
                component: resolve => require(["./components/book/create.vue"], resolve),
            },
            {
                path: 'edit/:id',
                name: 'edit-book',
                component: resolve => require(["./components/book/create.vue"], resolve),
            },
        ]
    },
    {
        path: '/documents',
        component: resolve => require(["./components/document/index.vue"], resolve),
        children: [
            {
                path: '',
                name: 'view-document',
                component: resolve => require(["./components/document/view.vue"], resolve),
            },
            {
                path: 'create',
                name: 'create-document',
                component: resolve => require(["./components/document/create.vue"], resolve),
            },
            {
                path: 'edit/:id',
                name: 'edit-document',
                component: resolve => require(["./components/document/create.vue"], resolve),
            },
        ]
    },
    {
        path: '/products',
        component: resolve => require(["./components/product/index.vue"], resolve),
        children: [
            {
                path: '',
                name: 'view-product',
                component: resolve => require(["./components/product/view.vue"], resolve),
            },
            {
                path: 'create',
                name: 'create-product',
                component: resolve => require(["./components/product/create.vue"], resolve),
            },
            {
                path: 'edit/:id',
                name: 'edit-product',
                component: resolve => require(["./components/product/create.vue"], resolve),
            }
        ]
    }
];
const router = new VueRouter({
    base: 'home',
    mode: 'history',
    routes
});
$(window).on('load', function () {
    new Vue({
        data(){
            return {
                validate: {
                    required: [
                        {required: true}
                    ]
                },

                store: {
                    state: {
                        user: {
                            notifications: []
                        },
                    },
                    mutations: {
                        handleDeleteNotification(state, data){
                            state.user.notifications.splice(data, 1)
                        }
                    },
                    dispatch(mutation, data = {}){ //$root.store.dispatch
                        this.mutations[mutation](this.state, data)
                    }
                }
            }
        },
        computed:{
            handleNotification(){
                return !!this.store.state.user.notifications.length
            }
        },
        mounted(){
            var vm = this
            axios.get('/api/users/notification').then(function (response) {
                vm.store.state.user = response.data
            })
        },
        router,
        render: h => h(require('./components/App.vue').default)
    }).$mount('#app')
});