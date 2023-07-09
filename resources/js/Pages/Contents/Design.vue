<template>
    <main id="main" class="about_page Design">

        <div class="container3">
            <div class="about_title_wrap">
                <p class="txt_top">
                    홈페이지 디자인
                </p>
                <p class="txt_ct">
                    심미성은 기본, 사업에 알맞은 디자인으로 <br>
                    사용자를 생각하는 UI·UX를 제공합니다.
                </p>
                <p class="txt_bt">
                    코워커웹은 모든 요소를 총체적으로 생각하고 연결합니다. <br>
                    이용자도 관리자도 편리한 디자인을 제공하기 위해 고민합니다.
                </p>
            </div>
        </div>

        <section class="section1">
            <div class="container3">
                <img src="/images/design/design_img.png" alt="" class="pc_img">
                <img src="/images/design/design_img_mb.png" alt="" class="mb_img">
            </div>
        </section>

        <section class="section2">
            <div class="container3">
                <div class="img_wrap">
                    <img src="/images/design/design_img1.png" alt="">
                </div>
                <div class="txt_wrap">
                    <p class="txt_top">
                        지나치지 않고 편리함과 <br>
                        다양성을 생각합니다.
                    </p>
                    <p class="txt_bt">
                        홈페이지 제작 시에는 많은 고려사항이 있습니다.<br>
                        주 타겟층, 브랜드 컨셉, 사이트의 특성 등 셀 수 없이 많은데<br>
                        사용기기마저 점점 많아져서 세심하게 제작할수록 오래 사용하실 수 있어요.<br>
                        전문가가 꼼꼼히 체크하고 제작하겠습니다.
                    </p>
                </div>
            </div>
        </section>

        <section class="section3">
            <div class="container3">
                <div class="img_wrap">
                    <img src="/images/design/design_img2.png" alt="" class="pc_img">
                    <img src="/images/design/design_img2_mb.png" alt="" class="mb_img">
                </div>
                <div class="img_wrap">
                    <img src="/images/design/design_img3.png" alt="" class="pc_img">
                    <img src="/images/design/design_img3_mb.png" alt="" class="mb_img">
                </div>
            </div>
        </section>

        <section class="section5">
            <div class="img_wrap">
                <img src="/images/design/design_img4.png" alt="" class="pc_img">
            </div>
            <img src="/images/design/design_img4_mb.png" alt="" class="mb_img">
        </section>



        <contact />

    </main>


</template>
<script>
import Contact from "../../Components/Contact";

import Pagination from "../../Components/Pagination";
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
