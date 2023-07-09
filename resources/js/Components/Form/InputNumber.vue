<template>
    <div class="m-input-number type01">
        <button type="button" class="m-input-btn" @click="decrease">
            <img src="/img/minus.png" alt="">
        </button>
        <input type="number">
        <p class="text">{{number}}</p>
        <button type="button" class="m-input-btn" @click="increase">
            <img src="/img/plus.png" alt="">
        </button>
    </div>
</template>
<script>


export default {
    props: {
        min: {
            default : 1
        },
        max: {
            default : 99
        },
        default: {
            default: 1
        }
    },
    data(){
        return {
            number: this.default,
        }
    },

    methods: {
        increase(){
            if(this.number == this.max)
                return alert(`최대 ${this.max}까지만 설정할 수 있습니다.`);

            this.number += 1;

            this.$emit("change", this.number);
        },

        decrease(){
            if(this.number == this.min)
                return alert(`최소 ${this.min}까지만 설정할 수 있습니다.`);

            this.number -= 1;

            this.$emit("change", this.number);
        }
    }
}
</script>
