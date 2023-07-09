<template>
    <section>
        <div class="container">
            <div class="sub-view">
                <div class="view-head">
                    <h2>{{ board.title }}</h2>
                    <time>{{ board.created_at }}</time>
                </div>
                <div class="view-body">
                    <div class="view-body-detail">
                        <div class="ql-snow">
                            <div class="ql-editor">
                                <div class="editor-wrap" v-html="board.description"></div>
                            </div>
                        </div>
                    </div>
                    <div class="view-body-file">
                        <ul>
                            <li v-for="(file, index) in board.files" :key="index"><a :href="file.url" download>{{ file.name }}</a></li>
                        </ul>
                    </div>
                    <div class="view-body-related">
                        <a href="#" class="list" @click="back">목록으로</a>
                        <ul>
                            <li><Link :href="`/news/boards/${board.prev.id}`" v-if="board.prev">{{ board.prev.title }}</Link></li>
                            <li><Link :href="`/news/boards/${board.next.id}`" v-if="board.next">{{ board.next.title }}</Link></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import {Link} from '@inertiajs/inertia-vue';
export default {
    components: {Link},

    data(){
        return {
            board: this.$page.props.board.data
        }
    },

    methods: {
        back(){
            window.history.back();
        }
    },

    mounted() {

    }
}
</script>
