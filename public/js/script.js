$(document).ready(function(){
    //헤더 푸터 컴포넌트
    $('#header').load('components/header.html', function() {
        $(".header-wrap .gnb> li").hover(function(){
            $(this).children('.gnb-sub').stop().slideToggle(200);
        });

        $('.sitemap-open').click(function(){
            $('#site_map').fadeIn(100);
            $("html").css("overflow" , "hidden");
        });

        $('#site_map .close').click(function(){
            $('#site_map').fadeOut(100);
            $("html").css("overflow" , "visible");
        });

        $(window).resize(function(){
            if (window.innerWidth > 768) {
                $(".sitemap-list").removeClass("m");
                }else{
                    $(".sitemap-list").addClass("m");
                }
        }).resize();
        $(".sitemap-list.m li> p").click(function () {
            $(this).toggleClass("open");
            $(this).siblings(".sitemap-sub-list").stop().slideToggle();
        })
        
    });

    $('#footer').load('components/footer.html');

    //서브메뉴 선택
    $(".sub-top-map .sub-menu-list li .show").click(function(){
        $(this).siblings('.sub-menu-select').stop().slideToggle(200);
        $(this).parent('li').toggleClass('active');
    });
})