<template>
    <main id="main" class="portfolio_dt">

        <div class="portfolio_dt_img_wrap">
            <img :src="item.top.url" alt="" v-if="item.top">
            <a :href="item.url" class="link_btn" target="_blank" v-if="item.url">바로가기 <i class="xi-external-link"></i></a>
        </div>

        <div class="txt_wrap container3">
            <p class="txt_top">
                {{ item.category }}
            </p>
            <p class="txt_ct" v-html="item.title"></p>
            <p class="txt_bt" v-html="item.description"></p>
        </div>

        <div class="portfolio_dt_img_wrap" v-for="(img, index) in item.imgs" :key="index">
            <img :src="img.url" alt="">
        </div>

        <div class="title_wrap container3">
            <h2>Project</h2>
            <p class="title_p">
                {{ item.title }}
            </p>
            <p class="date_p">
                {{ item.started_at }} ~ {{item.finished_at}}
            </p>
        </div>

    </main>
</template>
<script>
import Pagination from "../../Components/Pagination";
import {Link} from '@inertiajs/inertia-vue';
import Empty from "../../Components/Empty";
export default {
    components: {Link, Pagination, Empty},

    data(){
        return {
            item: this.$page.props.item.data,
            form: this.$inertia.form({
                page: 1
            }),
        }
    },

    methods: {
        filter(){

        },
    },

    mounted() {

    }
}
</script>
