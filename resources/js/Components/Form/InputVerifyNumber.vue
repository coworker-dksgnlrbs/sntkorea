<template>
    <div class="m-input-wrap type01">
        <div class="m-input-withBtn type02" v-if="mode === 'beforeSend'">
            <div class="m-input m-input-text type01">
                <input type="text" v-model="form.contact" @keyup="clearLetter" placeholder="- 없이 숫자만">
            </div>

            <button type="button" class="m-input-btn" @click="store">인증번호발송</button>
        </div>
        <p class="m-input-error">{{form.errors.contact}}</p>

        <div class="m-input-withBtn type02" v-if="mode === 'afterSend'">
            <div class="m-input m-input-text type01">
                <input type="text" v-model="form.number" @keyup="clearLetter" placeholder="인증번호">
            </div>

            <button type="button" class="m-input-btn" @click="update">인증하기</button>
        </div>
        <p class="m-input-error">{{form.errors.number}}</p>
    </div>

</template>
<script>


export default {
    props: {
        url: {
            default: "/verifyNumbers"
        }
    },

    data(){
        return {
            form: this.$inertia.form({
                contact: "",
                number: ""
            }),

            mode: "beforeSend",
        }
    },

    methods: {
        store(){
            this.form.post(this.url, {
                onSuccess: (response) => {
                    this.mode = "afterSend";
                },
                preserveScroll: true
            });
        },

        update(){
            this.form.patch(this.url, {
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit("verified", this.form.contact);
                }
            })
        },

        clearLetter(){
            this.form.contact = this.form.contact.replace(/-/g, '');
        },
    }
}
</script>
