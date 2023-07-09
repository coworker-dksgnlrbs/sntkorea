<template>
    <main id="main" class="about_page Backend">

        <div class="container3">
            <div class="about_title_wrap">
                <p class="txt_top">
                    홈페이지 백엔드
                </p>
                <p class="txt_ct">
                    필요한 기능을 넣고, 간편하고 꼼꼼하게 <br>
                    홈페이지를 관리해보세요.
                </p>
                <p class="txt_bt">
                    코워커웹은 홈페이지 관리자를 직접 제작합니다. <br>
                    고객이 원하시는 기능을 관리자에 제공하고 편리함을 상향시킵니다.
                </p>
            </div>
        </div>

        <section class="section1">
            <div class="container3">
                <img src="/images/back/backend_img.png" alt="">
            </div>
        </section>

        <section class="section2">
            <div class="container3">
                <div class="flex_wrap">
                    <div class="txt_wrap">
                        <p class="txt_top">
                            직접 필요기능을 넣고 <br>
                            체험해보세요!
                        </p>
                        <p class="txt_bt">
                            아래 내용은 설명을 위한 예시입니다.
                        </p>
                    </div>
                    <ul class="Function_list">
                        <li class="Choice">통계</li>
                        <li>베너 관리</li>
                        <li>회원 정보</li>
                        <li>갤러리</li>
                        <li>문자서비스</li>
                        <li>결제</li>
                        <li>업로드</li>
                        <li>문의사항</li>
                    </ul>
                </div>
                <div class="Manager_wrap">
                    <img src="/images/back/Manager_bg1.png" alt="">

                    <ul class="add_Function_list">
                        <li class="add watch">통계</li>
                        <li>베너 관리</li>
                        <li>회원 정보</li>
                        <li>갤러리</li>
                        <li>문자서비스</li>
                        <li>결제</li>
                        <li>업로드</li>
                        <li>문의사항</li>
                    </ul>

                    <div class="watch_img_wrap">
                        <img class="watch_img" src="/images/back/backend_img1.png" alt="">
                        <img src="/images/back/backend_img2.png" alt="">
                        <img src="/images/back/backend_img3.png" alt="">
                        <img src="/images/back/backend_img4.png" alt="">
                        <img src="/images/back/backend_img5.png" alt="">
                        <img src="/images/back/backend_img6.png" alt="">
                        <img src="/images/back/backend_img7.png" alt="">
                        <img src="/images/back/backend_img8.png" alt="">
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
        $(".Function_list li").click(function () {
            $(this).toggleClass("Choice");
            // console.log($(this).index())
            if($(this).hasClass("Choice")){
                $(".add_Function_list li").eq($(this).index()).addClass("add");
                $(".add_Function_list li").eq($(this).index()).addClass("watch").siblings().removeClass("watch");

                $(".watch_img_wrap img").eq($(this).index()).addClass("watch_img").siblings().removeClass("watch_img");
            }else{
                $(".add_Function_list li").eq($(this).index()).removeClass("add");
                $(".add_Function_list li").eq($(this).index()).removeClass("watch");

                $(".watch_img_wrap img").eq($(this).index()).removeClass("watch_img");

                $(".add_Function_list .add").eq(0).addClass("watch").removeClass("watch");
            }
        })

        $(".add_Function_list li").click(function () {
            // console.log($(".watch_img_wrap").eq($(this).index()));
            $(this).addClass("watch").siblings().removeClass("watch");
            $(".watch_img_wrap img").eq($(this).index()).addClass("watch_img").siblings().removeClass("watch_img");
        })
    }
}
</script>
