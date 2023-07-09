<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>{{config("app.name")}}</title>
    <link rel="shortcut icon" href="{{asset('/img/favicon.ico')}}">
    <link href="{{ asset('css/swiper.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/common.css?v='.\Carbon\Carbon::now()->format("Y-m-d H:i:s")) }}" rel="stylesheet" />
    <link href="{{ asset('css/style.css?v='.\Carbon\Carbon::now()->format("Y-m-d H:i:s")) }}" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">

    <!-- 노바 텍스트 에디터 스타일 -->
    <link href="https://cdn.quilljs.com/1.3.4/quill.snow.css" rel="stylesheet">

    <!-- 카카오 주소찾기 -->
    <script src="//unpkg.com/aos@next/dist/aos.js"></script>
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script src="{{ asset('js/jquery.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ asset('js/swiper.min.js') }}" defer></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/smooth-scrollbar/8.3.1/smooth-scrollbar.js" defer></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/smooth-scrollbar/8.3.1/plugins/overscroll.js" defer></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/TweenMax.min.js" defer></script>

    <!-- 아임포트 -->
    <script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js"></script>

    <script src="//unpkg.com/aos@next/dist/aos.js"></script>
    <script src="//unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

    <script src="{{ mix('/js/app.js') }}" defer></script>
</head>
<body>
@inertia

</body>
</html>
