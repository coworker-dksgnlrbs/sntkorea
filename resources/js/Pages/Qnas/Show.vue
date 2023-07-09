<template>
    <main id="main" class="contact">

        <div class="container3">
            <h2>문의하기</h2>
            <div class="contact_list_wrap">

                <div class="contact_item">
                    <div class="labal_wrap">
                        <p>회사명 <span>*</span></p>
                    </div>
                    <div class="input_wrap">
                        <input type="text" v-model="item.company">
                    </div>
                </div>

                <div class="contact_item">
                    <div class="labal_wrap">
                        <p>성명 <span>*</span></p>
                    </div>
                    <div class="input_wrap">
                        <input type="text" v-model="item.name">
                    </div>
                </div>

                <div class="contact_item">
                    <div class="labal_wrap">
                        <p>연락처 <span>*</span></p>
                    </div>
                    <div class="input_wrap">
                        <input type="text" v-model="item.contact">
                    </div>
                </div>

                <div class="contact_item">
                    <div class="labal_wrap">
                        <p>이메일 <span>*</span></p>
                    </div>
                    <div class="input_wrap">
                        <input type="text" v-model="item.email">
                    </div>
                </div>

                <div class="contact_item" style="width: 100%;">
                    <div class="labal_wrap">
                        <p>제작구분</p>
                    </div>
                    <div class="input_wrap">
                        <ul class="ck_ul">
                            <li>
                                <input type="radio" name="radio1" value="신규제작" id="a" v-model="item.type">
                                <label for="a">신규제작</label>
                            </li>
                            <li>
                                <input type="radio" name="radio1" value="리뉴얼" id="b" v-model="item.type">
                                <label for="b">리뉴얼</label>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="contact_item" style="width: 100%;">
                    <div class="labal_wrap">
                        <p>필요서비스</p>
                    </div>
                    <div class="input_wrap">
                        <ul class="ck_ul">
                            <li>
                                <input type="radio" name="radio2" id="웹사이트" value="웹사이트 (반응형)" v-model="item.service">
                                <label for="웹사이트">웹사이트 (반응형)</label>
                            </li>
                            <li>
                                <input type="radio" name="radio2" id="어플리케이션" value="어플리케이션"  v-model="item.service">
                                <label for="어플리케이션">어플리케이션</label>
                            </li>
                            <li>
                                <input type="radio" name="radio2" id="SEO" value="SEO 최적화 (검색엔진)"  v-model="item.service">
                                <label for="SEO">SEO 최적화 (검색엔진)</label>
                            </li>
                            <li>
                                <input type="radio" name="radio2" id="유지보수" value="유지보수"  v-model="item.service">
                                <label for="유지보수">유지보수</label>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="contact_item">
                    <div class="labal_wrap">
                        <p>프로젝트 예산 <span>*</span></p>
                    </div>
                    <div class="input_wrap">
                        <input type="number" v-model="item.budget">
                        <p class="Unit">만원</p>
                    </div>
                </div>

                <div class="contact_item">
                    <div class="labal_wrap">
                        <p>오픈일정 <span>*</span></p>
                    </div>
                    <div class="input_wrap">
                        <input type="text" v-model="item.opened_at">
                    </div>
                </div>

                <div class="contact_item" style="width: 100%;">
                    <div class="labal_wrap">
                        <p>레퍼런스 사이트</p>
                    </div>
                    <div class="input_wrap">
                        <input type="text" v-model="item.url">
                    </div>
                </div>

                <div class="contact_item" style="width: 100%;">
                    <div class="labal_wrap">
                        <p>첨부파일</p>
                    </div>
                    <div class="input_wrap">
                        <input-files title="파일 첨부" :default="item.files" :onlyShow="true" />
                    </div>
                </div>

                <div class="contact_item textarea_item" style="width: 100%;">
                    <div class="labal_wrap">
                        <p>추가 문의사항</p>
                    </div>
                    <div class="input_wrap textarea_wrap">
                        <textarea name="" id="" v-model="item.memo"></textarea>
                    </div>
                </div>
            </div>
        </div>

    </main>
</template>
<script>
import {Link} from "@inertiajs/inertia-vue";
import InputFiles from '../../Components/InputFiles';

export default {
    components: {Link, InputFiles},
    data(){
        return {
            agree: false,
            item : this.$inertia.page.props.item.data,
        }
    },

    methods: {
        store() {
            if(!this.agree)
                return "약관에 동의해주세요.";

            if(!this.checkNeed())
                return "필수입력란을 확인해주세요.";

            this.item.post("/qnas", {
                onSuccess: () => {
                    alert("성공적으로 접수되었습니다!");

                    window.history.back();
                }
            })
        },

        checkNeed() {
            if(!this.item.company)
                return false;

            if(!this.item.name)
                return false;

            if(!this.item.email)
                return false;

            if(!this.item.contact)
                return false;

            if(!this.item.opened_at)
                return false;

            if(!this.item.budget)
                return false;

            return true;
        },

        changeFiles(data){
            this.item.files = data;
        },
    }
}
</script>
