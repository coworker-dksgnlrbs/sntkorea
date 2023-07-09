<template>
    <main id="main" class="about_page front">

        <div class="container3">
            <div class="about_title_wrap">
                <p class="txt_top">
                    홈페이지 프론트
                </p>
                <p class="txt_ct">
                    어떤 기기로 확인해도 같은 서비스를 제공받을 수 있도록 <br>
                    반응형을 기본으로 제작합니다.
                </p>
                <p class="txt_bt">
                    반응형은 기본! 자연스러운 모션까지 템플릿에서 만나볼 수 없는 디테일을 만나보세요.
                </p>
            </div>
        </div>

        <section class="section1">

        </section>
        <section class="section2">
            <div class="container3">
                <div class="flex_wrap">
                    <div class="flex_le">
                        <div class="phone_wrap">
                            <img src="/images/front/Mockup.png" alt="">
                            <video src="/images/front/front_mp4.mp4" loop autoplay muted></video>
                        </div>
                    </div>
                    <div class="flex_ri">
                        <img src="/images/front/front_txt_bg.png" alt="">
                        <div class="txt_wrap">
                            <p class="txt_top">
                                자연스러운 변화와 <br>
                                다양한 모션 지원으로<br>
                                역동적인 홈페이지를 <br>
                                구현해 드립니다.
                            </p>
                            <p class="txt_bt">
                                유용한 모션과 합리적인 운용으로 <br>
                                고객의 사용성을 높일 수 있습니다.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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
