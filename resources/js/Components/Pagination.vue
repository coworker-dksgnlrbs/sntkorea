<template>
    <div class="pagination_wrap">
        <ul class="pagination">
            <li class="prev">
                <a href="#" @click.prevent="prev">
                    <i class="xi-angle-left"></i>
                </a>
            </li>
            <li :class="pageClass(page)" v-for="page in pages" :key="page"><a href="" @click.prevent="paginate(page)">{{page}}</a></li>
            <li class="next">
                <a href="#" @click.prevent="next">
                    <i class="xi-angle-right"></i>
                </a>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props:["meta"],

    computed: {
        pages(){
            let i = 1;
            let pages = [];

            for(i = 1; i <= this.meta.last_page; i++){
                pages.push(i);
            }

            return pages;
        }
    },

    methods: {
        pageClass(page){
            return this.meta.current_page == page
                ? "m-pagination-page now"
                : "m-pagination-page";
        },

        paginate(page){
            this.$emit("paginate", page);
        },

        first(){
            this.paginate(1);
        },

        prev(){
            if(this.meta.current_page > 1)
                this.paginate(parseInt(this.meta.current_page) - 1);
        },

        next(){
            if(this.meta.current_page < this.meta.last_page)
                this.paginate(parseInt(this.meta.current_page) + 1);
        },

        last(){
            this.paginate(this.meta.last_page);
        }
    }

}
</script>
