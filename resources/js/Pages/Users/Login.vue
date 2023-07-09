<template>
    <div class="login subContent">
        <form @submit.prevent="login" @keyup="form.clearErrors()">
            <div class="wrap-auth">
                <h3 class="title">로그인</h3>

                <div class="m-input-wrap type01">
                    <h3 class="m-input-title">폰번호 아이디</h3>

                    <div class="m-input-text type01">
                        <input type="text" v-model="form.contact" @keyup="clearLetter">
                    </div>

                    <div class="m-input-error">{{ form.errors.contact }}</div>
                </div>

                <div class="m-input-wrap type01">
                    <h3 class="m-input-title">비밀번호</h3>

                    <div class="m-input-text type01">
                        <input type="password" v-model="form.password">
                    </div>

                    <div class="m-input-error">{{ form.errors.password }}</div>
                </div>

                <div class="links">
                    <Link href="/register" class="link">회원가입</Link>
                    <Link href="/passwordResets/create" class="link">비밀번호 찾기</Link>
                </div>

                <div class="btns mt-40">
                    <button class="m-btn type04">로그인</button>
                    <a href="/openLoginPop/kakao" class="m-btn type04 kakao">
                        <img src="/img/circleKakao.png" alt="">
                        카카오 1초 로그인
                    </a>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import {Link} from "@inertiajs/inertia-vue";

export default {
    components: {Link},
    data(){
        return {
            form: this.$inertia.form({
                contact:"",
                password: ""
            })
        }
    },

    methods: {
        login() {
            this.form.post("/login", {
                preserveState: true
            })
        },

        clearLetter(){
            this.form.contact = this.form.contact.replace(/-/g, '');
        }
    }
}
</script>
