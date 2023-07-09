<template>
    <main id="main" class="column">

        <div class="column_list_wrap">
            <div class="container3">
                <h2>코워커웹이 말하는 홈페이지</h2>
                <ul class="column_list">
                    <li v-for="item in items.data" :key="item">
                        <a :href="`/columns/${item.id}`">
                            <img :src="item.img.url" alt="" v-if="item.img">
                            <div class="txt_wrap">
                                <div class="txt_box">
                                    <p class="txt_top" v-html="item.title"></p>
                                    <p class="txt_ct" v-html="item.description"></p>
                                </div>
                                <p class="date_p">
                                    {{ item.created_at }}
                                </p>
                            </div>
                        </a>
                    </li>
                </ul>

                <pagination :meta="items.meta" @paginate="(page) => {form.page = page; filter()}" />

            </div>
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
            this.form.get("/columns", {
                preserveScroll: false,
                preserveState: false,
                replace: true
            });
        },
    },

    mounted() {

    }
}
</script>
