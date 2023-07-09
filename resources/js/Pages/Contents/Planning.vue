<template>
    <main id="main" class="about_page Planning">
        <div class="container3">
            <div class="about_title_wrap">
                <p class="txt_top">
                    홈페이지 기획
                </p>
                <p class="txt_ct">
                    복잡하게만 느껴지는 홈페이지, <br>
                    필요기능을 정리하고 보기 쉽게 정리해드려요
                </p>
            </div>
        </div>

        <section class="section1">
            <div class="container3">
                <img src="/images/plan/plan_img.png" class="pc_img" alt="">
                <img src="/images/plan/4배.png" class="mb_img" alt="">
            </div>
        </section>

        <section class="section2">

            <div class="img_wrap">
                <img src="/images/plan/plan_img1.png" alt="">
            </div>

            <div class="txt_wrap container3">
                <p class="txt_top">
                    하나의 키워드에 필요한 <br>
                    수많은 기능을 연계해서 알려드립니다.
                </p>
                <p class="txt_bt">
                    전문가가 고객의 사업에 알맞는 솔루션으로 어려웠던 홈페이지 준비를 도와드립니다. <br>
                    디테일한 기능으로 고객서비스를 제공해보세요.<br>
                    편리한 사용과 편리한 운영을 동시에 서비스합니다.
                </p>
            </div>
        </section>

        <contact />

    </main>
</template>
<script>
import Pagination from "../../Components/Pagination";
import Contact from "../../Components/Contact";
import {Link} from '@inertiajs/inertia-vue';
export default {
    components: {Link, Pagination, Contact},

    data(){
        return {

        }
    },

    methods: {

    },

    mounted() {

    }
}
</script>
