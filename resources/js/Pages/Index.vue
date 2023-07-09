<template>
    <main id="main" class="main">

        <section class="section1">
            <div class="swiper mySwiper-section1">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" v-for="banner in banners.data" :key="banner.id">
                        <div class="bg_wrap">
                            <img :src="banner.img.url" alt="" v-if="banner.img">
                        </div>
                        <div class="txt_wrap">
                            <p class="txt_top" v-html="banner.title"></p>
                            <p class="txt_bt" v-text="banner.description"></p>
                        </div>
                    </div>
                </div>
                <!-- <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div> -->
                <div class="swiper-pagination-section1"></div>
            </div>
        </section>

        <section class="section2">
            <div class="container">
                <div class="h2_wrap" data-aos="fade-up">
                    <h2 class="h2_top">PORTFOLIO</h2>
                    <p class="p_bt">
                        언제 어디서나 유용하고 다양해진 기기만큼 <br>
                        중요해진 반응형 홈페이지 제작
                    </p>
                </div>

                <div class="wrap"   data-aos="fade-up">
                    <div class="box1">
                        <!--main-->
                        <div class="swiper-container main-slide">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="/images/main/main_st2.png" alt="">
                                    <div class="txt_wrap">
                                        <a class="a_wrap" href="">
                                            <p class="title">MONSTER CLINIC</p>
                                            <div class="sub_title">
                                                <p>1:1 맞춤 관리 전문 피부과 몬스터클리닉</p>
                                                <p>반응형 웹 홈페이지 제작</p>
                                            </div>
                                            <div class="icon_wrap">
                                                <i class="xi-arrow-right"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <img src="/images/main/main_st2.png" alt="">
                                    <div class="txt_wrap">
                                        <a class="a_wrap" href="">
                                            <p class="title">MONSTER CLINIC</p>
                                            <div class="sub_title">
                                                <p>1:1 맞춤 관리 전문 피부과 몬스터클리닉</p>
                                                <p>반응형 웹 홈페이지 제작</p>
                                            </div>
                                            <div class="icon_wrap">
                                                <i class="xi-arrow-right"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <img src="/images/main/main_st2.png" alt="">
                                    <div class="txt_wrap">
                                        <a class="a_wrap" href="">
                                            <p class="title">MONSTER CLINIC</p>
                                            <div class="sub_title">
                                                <p>1:1 맞춤 관리 전문 피부과 몬스터클리닉</p>
                                                <p>반응형 웹 홈페이지 제작</p>
                                            </div>
                                            <div class="icon_wrap">
                                                <i class="xi-arrow-right"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box2">
                        <div class="swiper-container sub-slide">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img src="/images/main/main_st2.png" alt=""></div>
                            </div>
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img src="/images/main/main_st2.png" alt=""></div>
                            </div>
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img src="/images/main/main_st2.png" alt=""></div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination-section2"></div>
                </div>
            </div>
        </section>

        <section class="section3">
            <div class="container">
                <div class="wrap">
                    <div class="container2">
                        <div class="txt_wrap">
                            <p class="txt_top">
                                <span>코워커웹이 제작한</span> <br>
                                더 많은 홈페이지를 확인해보세요!
                            </p>
                            <a href="/portfolios">자세히 보기</a>
                        </div>
                        <img src="/images/main/main_banner3.png" alt="">
                    </div>
                </div>
            </div>
        </section>

        <section class="section4"   data-aos="fade-up">
            <div class="container2">
                <div class="content_wrap">
                    <div class="swiper mySwiper-section4 item_wrap">

                        <div class="swiper-pagination-section4"></div>

                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="item Active">
                                    <div class="txt_wrap">
                                        <p class="txt_top">홈페이지 기획</p>
                                        <p class="txt_ct">홈페이지 제작에 최적화하여 <br>
                                            자료를 재 가공합니다.</p>
                                        <p class="txt_bt">플로우 차트, 사이트 맵, 기획서 등 <br>
                                            고객 사업의 내용을 홈페이지제작에 맞게 재가공합니다.</p>
                                        <a href="/contents/planning">기획 자세히 보기</a>
                                    </div>
                                    <div class="img_wrap">
                                        <img src="/images/main/plan.png" alt="">
                                    </div>
                                    <a href="/contents/planning">기획 자세히 보기</a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="item Active">
                                    <div class="txt_wrap">
                                        <p class="txt_top">홈페이지 디자인</p>
                                        <p class="txt_ct">남다르지만 <br>
                                            사업에 최적화된 디자인</p>
                                        <p class="txt_bt">고객이 찾던 디자인, 고객이 생각한 디자인을 넘어 <br>
                                            고객에게 필요한 디자인을 제공합니다.</p>
                                        <a href="/contents/design">디자인 자세히 보기</a>
                                    </div>
                                    <div class="img_wrap">
                                        <img src="/images/main/design.png" alt="">
                                    </div>
                                    <a href="/contents/design">디자인 자세히 보기</a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="item Active">
                                    <div class="txt_wrap">
                                        <p class="txt_top">홈페이지 프론트엔드</p>
                                        <p class="txt_ct">어떤 기기로 확인해도 <br>
                                            완벽한 레이아웃</p>
                                        <p class="txt_bt">반응형은 기본! 자연스러운 모션까지 <br>
                                            템플릿에서 만나볼 수 없는 디테일을 만나보세요.</p>
                                        <a href="/contents/front">프론트엔드 자세히 보기</a>
                                    </div>
                                    <div class="img_wrap">
                                        <img src="/images/main/frontend.png" alt="">
                                    </div>
                                    <a href="/contents/front">프론트엔드 자세히 보기</a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="item Active">
                                    <div class="txt_wrap">
                                        <p class="txt_top">홈페이지 백엔드</p>
                                        <p class="txt_ct">완벽한 커스텀 <br>
                                            간편한 관리</p>
                                        <p class="txt_bt">특별한 기능을 추가하고 원하는 내용도 관리해보세요. <br>
                                            코워커웹은 커스텀이 가능합니다.</p>
                                        <a href="/contents/backend">백엔드 자세히 보기</a>
                                    </div>
                                    <div class="img_wrap">
                                        <img src="/images/main/backend.png" alt="">
                                    </div>
                                    <a href="/contents/backend">백엔드 자세히 보기</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="section5">
            <div class="container2">
                <div class="txt_wrap"   data-aos="fade-up">
                    <p class="txt_top">
                        홈페이지 디자인 변경 후 <br>
                        <span>상승하는 이용자 수</span>를 <br>
                        확인해보세요
                    </p>
                    <p class="txt_bt">
                        홈페이지 제작 혹은 리뉴얼을 고민중이신가요? <br>
                        코워커웹의 많은 고객이 디자인 변경 후 <br>
                        이용자 상승을 경험하셨습니다!
                    </p>
                </div>
                <img src="/images/main/graph.png" alt="" data-aos="fade-up">
            </div>
        </section>

        <section class="section6"   data-aos="fade-up">
            <div class="container2">
                <div class="h2_wrap">
                    <h2 class="h2_top">반응형 홈페이지</h2>
                    <p class="p_bt">
                        어느 문으로 들어가도 <br>
                        같은 서비스를 받을 수 있도록
                    </p>
                </div>

                <div class="swiper mySwiper-section6">

                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="/images/main/PC.png" alt="">
                            <img src="/images/main/PC_M.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img src="/images/main/PAD.png" alt="">
                            <img src="/images/main/PAD_M.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img src="/images/main/MB.png" alt="">
                            <img src="/images/main/MB_M.png" alt="">
                        </div>
                    </div>

                    <div class="swiper-pagination-section6"></div>

                    <button id="autoplayButton" class="stop"></button>
                </div>
            </div>
        </section>

        <section class="section7">
            <div class="container2">
                <div class="txt_wrap">
                    <p class="txt_top">
                        홈페이지, <br>
                        어떻게 준비해야할지 막막하신가요?
                    </p>
                    <p class="txt_bt">
                        <span>간단한 문답</span>으로 고객님의 홈페이지 제작을 <br> 체계화할 수 있어요!
                    </p>
                    <a href="/qnas/create">1분 문답 시작하기</a>
                </div>
                <img src="/images/main/banner2.png" alt="">
            </div>
        </section>

        <section class="section8">
            <div class="container2">
                <div class="h2_wrap"   data-aos="fade-up">
                    <h2 class="h2_top">코워커웹 후기</h2>
                    <p class="p_bt">
                        후기로 만나보는 코워커웹 <br>
                    </p>
                </div>
                <div class="swiper mySwiper-section8"   data-aos="fade-up">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="review in reviews.data" :key="review.id">
                            <p class="tilte_p" v-html="review.title"></p>
                            <p class="txt_ct" v-text="review.description"></p>
                            <div class="bottom_wrap">
                                <p>{{ review.name[0] }}**님</p>
                                <ul class="star_wrap">
                                    <li><i class="xi-star"></i></li>
                                    <li><i class="xi-star"></i></li>
                                    <li><i class="xi-star"></i></li>
                                    <li><i class="xi-star"></i></li>
                                    <li><i class="xi-star"></i></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-wrap">
                        <div class="section8-button-prev"><i class="xi-angle-left"></i></div>
                        <div class="section8-button-next"><i class="xi-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section9">
            <div class="container">
                <div class="h2_wrap"   data-aos="fade-up">
                    <h2 class="h2_top">코워커웹 협력사</h2>
                    <p class="p_bt">
                        함께하고 있는 든든한 파트너사
                    </p>
                </div>
                <div class="logo_list_wrap">
                    <div class="logo_bar_wrap">
                        <img src="/images/main/logo_1.svg" alt="">
                        <img src="/images/main/logo_1.svg" alt="">
                    </div>
                    <div class="logo_bar_wrap logo_bar2">
                        <img src="/images/main/logo_2.svg" alt="">
                        <img src="/images/main/logo_2.svg" alt="">
                    </div>
                </div>
            </div>
        </section>

        <div class="contact_btn_wrap">
            <a href="/qnas/create" class="contact_btn One_minute">
                <img src="/images/main/One_minute.png" alt="">
                <p>1분 문답</p>
            </a>
            <a href="/qnas/create" class="contact_btn">
                <img src="/imagesain/contact.png" alt="">
                <p>문의하기</p>
            </a>
        </div>

    </main>
</template>
<script>

export default {
    components: {},

    data() {
        return {
            banners: this.$page.props.banners,
            reviews: this.$page.props.reviews,

            form: this.$inertia.form({

            }),
        }
    },

    methods: {
        filter(){

        },

    },

    mounted() {
        $( document ).ready( function() {
            AOS.init();
        } );

        var swiper = new Swiper(".mySwiper-section1", {
            spaceBetween: 30,
            speed: 600,
            effect: "fade",
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination-section1",
                type: "progressbar",
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
        });

        var swiperTopNum = $('.main-slide').find('.swiper-slide');
        var swiperSubNum = $('.sub-slide').find('.sub-slide');

        var mainSlide = new Swiper('.main-slide', {
            // loop: true,	//슬라이드 반복
            speed: 600,
            pagination: {
                el: ".swiper-pagination-section2",
                clickable: true,
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            loopedSlides: swiperTopNum.length
        });

        var subSlide = new Swiper('.sub-slide', {
            // loop: true,	//슬라이드 반복
            direction: "vertical",
            loopedSlides: swiperSubNum.length //loop 시 파라미터 duplicate 개수
        });


        mainSlide.controller.control = subSlide;
        subSlide.controller.control = mainSlide;

        // section4
        var bullet = ['기획', '디자인', '프론트', '백엔드'];
        var swiper = new Swiper(".mySwiper-section4", {
            spaceBetween: 30,
            effect: "fade",
            pagination: {
                el: '.swiper-pagination-section4',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<div class="' + className + '">' + (bullet[index]) + '</div>';
                }
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
        });

        // section6
        var swiper1 = new Swiper(".mySwiper-section6", {
            speed: 600,
            loop: true,	//슬라이드 반복
            effect: "fade",
            pagination: {
                el: ".swiper-pagination-section6",
                clickable: true,
            },
            autoplay: {
                delay: 1000,
                disableOnInteraction: false,
            },
        });

        // autoplay 버튼 클릭 이벤트 핸들러
        var autoplayButton = document.getElementById("autoplayButton");
        var autoplayEnabled = true; // autoplay 상태 저장 변수

        autoplayButton.addEventListener("click", function() {
            if (autoplayEnabled) {
                swiper1.autoplay.stop(); // autoplay 중지
                $(this).addClass("start");
                $(this).removeClass("stop");
            } else {
                swiper1.autoplay.start(); // autoplay 시작
                $(this).removeClass("start");
                $(this).addClass("stop");
            }

            autoplayEnabled = !autoplayEnabled; // autoplay 상태 변경
        });

        // section8
        var swiper = new Swiper(".mySwiper-section8", {
            slidesPerView: 3,
            spaceBetween: 36,
            loop: true,	//슬라이드 반복
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".section8-button-next",
                prevEl: ".section8-button-prev",
            },
            breakpoints: {
                1024: {
                    slidesPerView: 2,  //브라우저가 1024보다 클 때
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,  //브라우저가 768보다 클 때
                    spaceBetween: 20,
                },
            },
        });
    }
}
</script>
