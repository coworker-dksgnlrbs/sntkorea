<template>
    <div class="users-edit subContent">
        <div class="wrap">
            <h3 class="subContent-title">
                마이페이지
            </h3>

            <div class="m-dashboard type01">
                <sidebar />

                <div class="m-dashboard-container">
                    <h3 class="title">내 정보 수정</h3>

                    <form @submit.prevent="update">
                        <table class="m-table type02 type-horizon">
                            <tbody>
                            <tr>
                                <th>이름</th>
                                <td>
                                    <div class="m-input-text type01">
                                        <input type="text" v-model="form.name">
                                    </div>

                                    <p class="m-input-error">{{form.errors.name}}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>폰번호 아이디</th>
                                <td>
                                    <div class="m-input-withBtn type02" v-if="form.contact">
                                        <div class="m-input m-input-text type01">
                                            <input type="text" disabled v-model="form.contact">
                                        </div>

                                        <button type="button" class="m-input-btn" @click="form.contact = ''">변경하기</button>
                                    </div>

                                    <input-verify-number @verified="(data) => {form.contact_change = data; form.contact = data;}" v-else />

                                    <p class="m-input-error">{{form.errors.contact_change}}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>비밀번호</th>
                                <td>
                                    <button class="m-btn type05" @click="activePassword = true" v-if="!activePassword">비밀번호 변경</button>
                                    <div class="m-input-text type01" v-else>
                                        <input type="password" placeholder="비밀번호" v-model="form.password">
                                        <input type="password" placeholder="비밀번호 확인" style="margin-top:10px;" v-model="form.password_confirmation">
                                    </div>
                                    <div class="m-input-error">{{ form.errors.password }}</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div style="display:flex; justify-content: flex-end;">
                            <div class="m-input-checkboxes type02 mt-20">
                                <div class="m-input-checkbox">
                                    <input type="checkbox" id="agree_ad" v-model="form.agree_ad">
                                    <label for="agree_ad">
                                        광고성 SMS 수신동의
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="m-box-total type01 mt-40">
                            <button class="m-btn type04">저장하기</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Sidebar from "../../Components/Sidebar";
import {Link} from '@inertiajs/inertia-vue';
import Pagination from "../../Components/Pagination";
import InputVerifyNumber from "../../Components/Form/InputVerifyNumber";
export default {
    components: {Sidebar, Link, Pagination, InputVerifyNumber},
    data(){
        return {
            form: this.$inertia.form({
                name: this.$page.props.user.data.name,
                password: "",
                password_confirmation: "",
                contact:this.$page.props.user.data.contact,
                contact_change : "",
                agree_ad: this.$page.props.user.data.agree_ad
            }),
            activePassword: false
        }
    },
    methods:{
        update(){
            this.form.post("/users/update", {
                preserveScroll: true
            });
        }
    }
}
</script>
