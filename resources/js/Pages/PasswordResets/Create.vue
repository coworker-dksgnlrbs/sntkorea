<template>
    <div class="passwordReset-create subContent">
        <form @submit.prevent="submit" @keyup="form.clearErrors()">
            <div class="wrap-auth">
                <h3 class="title">비밀번호 초기화</h3>

                <div class="m-input-wrap type01">
                    <h3 class="m-input-title">폰번호 아이디</h3>

                    <div class="m-input-text type01">
                        <input type="text" v-model="form.contact" @keyup="clearLetter">
                    </div>

                    <div class="m-input-error">{{ form.errors.contact }}</div>
                </div>

                <div class="btns mt-40">
                    <button class="m-btn type04">비밀번호 초기화 문자발송</button>
                </div>
            </div>
        </form>

    </div>
</template>
<script>
export default {
    data() {
        return {
            form: this.$inertia.form({
                contact: "",
            })
        }
    },

    methods: {
        submit() {
            this.form.post("/passwordResets");
        },
        clearLetter(){
            this.form.contact = this.form.contact.replace(/-/g, '');
        }
    }
}
</script>
