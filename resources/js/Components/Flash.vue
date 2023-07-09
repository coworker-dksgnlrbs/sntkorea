<template>
    <div v-if="activated">
        <div v-if="$page.props.flash.error" class="m-flash type01 error animated fadeInUp">
            {{ $page.props.flash.error }}
        </div>

        <div v-if="$page.props.flash.success" class="m-flash type01 success animated fadeInUp">
            {{ $page.props.flash.success }}
        </div>
    </div>
</template>
<script>

export default {
    components: {},

    data(){
        return {
            activated: false,
            timer: null
        }
    },

    methods: {

    },

    mounted() {

    },

    watch: {
        '$page.props.error': {
            deep:true,
            handler() {
                let self = this;

                if(this.timer){
                    clearTimeout(this.timer);

                    this.timer = null;
                }

                this.activated = true;

                this.timer = setTimeout(function(){
                    self.activated = false;
                }, 5000);
            }
        },
        '$page.props.success': {
            deep:true,
            handler() {
                let self = this;

                if(this.timer){
                    clearTimeout(this.timer);

                    this.timer = null;
                }

                this.activated = true;

                this.timer = setTimeout(function(){
                    self.activated = false;
                }, 5000);
            }
        }
    }
}
</script>
