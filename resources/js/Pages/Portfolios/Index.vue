<template>
    <main id="main" class="portfolio">
        <div class="portfolio_list">
            <div class="container3">
                <h2>코워커웹과 함께</h2>
                <div class="grid">
                    <div class="grid-item" v-for="item in items.data" :key="item.id">
                        <a :href="`/portfolios/${item.id}`" class="item_wrap">
                            <div class="img_wrap">
                                <img :src="item.pc.url" alt="" v-if="item.pc">
                                <img :src="item.mobile.url" class="mb_img" alt="" v-if="item.mobile">
                            </div>
                            <div class="txt_wrap">
                                <p>{{ item.category }}</p>
                                <p class="title" v-html="item.title"></p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <pagination :meta="items.meta" @paginate="(page) => {form.page = page; filter()}" />
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
            form: this.$inertia.form({
                page: 1
            }),
            items: this.$page.props.items
        }
    },

    methods: {
        filter(){
            this.form.get("/portfolios", {
                preserveScroll: false,
                preserveState: false,
                replace: true
            });
        },
    },

    mounted() {
        setTimeout(function(){
            var msnry = new Masonry( '.grid', {
                itemSelector: '.grid-item',
                // gutter: 80,
                transitionDuration: '0.2s', //위치 전환 시간
                fitWidth: true,
            });

        }, 100);

    }
}
</script>
