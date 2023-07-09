<template>
    <section>
        <div class="container">
            <div class="sub-search">
                <form @submit.prevent="filter">
                    <input type="text" placeholder="검색어를 입력해주세요." v-model="form.word">
                    <button class="submit">검색</button>
                </form>
            </div>
            <div class="sub-press">
                <ul>
                    <li v-for="board in boards.data" :key="board.id">
                        <a :href="board.url" target="_blank">
                            <time class="sanai month">{{board.month}}<br>{{ board.day }}</time>
                            <time class="year"><b>{{ board.year }}</b></time>
                            <p>
                                <span class="new" v-if="board.new == 1">NEW</span>
                                {{ board.title }}
                            </p>
                            <small v-if="board.company">{{ board.company }}</small>
                        </a>
                    </li>
                </ul>

                <empty v-if="boards.data.length === 0" />
            </div>
            <pagination :meta="boards.meta" @paginate="(page) => {form.page = page; filter()}" />
        </div>
    </section>
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
                word: this.$page.props.word,
                page: 1
            }),
            boards: this.$page.props.boards
        }
    },

    methods: {
        filter(){
            this.form.get("/news/boards", {
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
