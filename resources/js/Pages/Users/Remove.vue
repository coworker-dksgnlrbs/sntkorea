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

                    <form @submit.prevent="remove">
                        <table class="m-table type02 type-horizon">
                            <tbody>
                            <tr>
                                <th>탈퇴사유</th>
                                <td>
                                    <div class="m-input-textarea type01">
                                        <textarea v-model="form.reason"></textarea>
                                    </div>

                                    <p class="m-input-error">{{form.errors.reason}}</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="m-box-total type01 mt-40">
                            <button class="m-btn type04" style="background-color:red;">탈퇴하기</button>
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
                reason: "",
            }),
        }
    },
    methods:{
        remove(){
            this.form.delete("/users", {
                preserveScroll: true
            });
        }
    }
}
</script>
