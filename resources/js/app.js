require('./bootstrap');
import "babel-polyfill";
import Vue from 'vue';
import {createInertiaApp} from '@inertiajs/inertia-vue';
import {InertiaProgress} from '@inertiajs/progress';
import Layout from "./Pages/Layout";

window.dayOfWeeks = [
    "일",
    "월",
    "화",
    "수",
    "목",
    "금",
    "토",
];

createInertiaApp({
    resolve: name => {
        const page = require(`./Pages/${name}`).default;
        page.layout = page.layout || Layout;
        return page;
    },
    setup({el, App, props}) {
        new Vue({
            render: h => h(App, props),
        }).$mount(el)
    },
});

InertiaProgress.init();
