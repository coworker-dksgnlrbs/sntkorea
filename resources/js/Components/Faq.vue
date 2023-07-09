<template>
    <div :class="active ? 'm-board active' : 'm-board'" @click="active = !active">
        <div class="head">
            <div class="left">
                <p class="date">{{ faq.created_at }}</p>

                <h3 class="title">
                    {{ faq.question }}
                </h3>
            </div>

            <div class="icon">
                <img src="/img/arrowDown-white.png" alt="" class="active">
                <img src="/img/arrowDown-gray.png" alt="" class="inactive">
            </div>
        </div>

        <div class="content" v-html="faq.answer"></div>
    </div>
</template>
<script>

export default {
    props: ["faq"],
    data(){
        return {
            active: false
        }
    },

    methods: {

    },

    computed: {

    },

    mounted() {

    }
}
</script>
