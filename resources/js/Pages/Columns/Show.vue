<template>
    <main id="main" class="column">

        <div class="column_dt_wrap container3">
            <div class="column_dt">
                <div class="Thumbnail_wrap">
                    <img :src="item.img.url" alt="" v-if="item.img">
                </div>
                <div class="title_wrap">
                    <p v-html="item.title"></p>
                </div>
                <div class="editor_wrap">
                    <div class="ql-snow">
                        <div class="ql-editor">
                            <div v-html="item.detail"></div>
                        </div>
                    </div>
                </div>
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
