@component('mail::message')
    <div id="wrap">
        <!-- //main -->
        <main id="main" class="E-mail">
            <div class="E-mail-box" style="padding: 32px 20px;background-color: #e7edf5;width: 100%;max-width: 678px;">
                <div class="w-box" style="width: 100%;padding: 30px 40px;background-color: #fff;">
                    <ul class="ul-1">
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-3" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                회사명
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->company}}
                            </p>
                        </li>
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-2" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                담당자명
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->name}}
                            </p>
                        </li>
                    </ul>
                    <ul class="ul-2">
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-2" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                연락처
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->contact}}
                            </p>
                        </li>
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-3" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                이메일
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->email}}
                            </p>
                        </li>
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-3" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                제작구분
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->type}}
                            </p>
                        </li>
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-3" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                필요서비스
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->service}}
                            </p>
                        </li>
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-3" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                예산
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->budget}}
                            </p>
                        </li>
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-3" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                레퍼런스 사이트
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->url}}
                            </p>
                        </li>
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p Interval-3" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                오픈일정
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878;">
                                {{$qna->opened_at}}
                            </p>
                        </li>
                        <li style="margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #e1e1e1;">
                            <p class="labal-p" style="width: 100%; margin-bottom:8px; font-size: 14px; font-weight:bold; color: #787878;">
                                추가문의사항
                            </p>
                            <p class="content-p" style="font-size: 18px;color: #787878; white-space: pre-line">{{$qna->memo}}</p>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="btns" style="display:flex; align-items: center; justify-content: center; margin-top:40px; margin-bottom:40px;">
                <a href="{{config("app.url")."/qnas/{$qna->id}"}}" style="display:inline-block; padding:10px 20px; background-color:black; color:#fff;">바로가기</a>
            </div>
        </main>
    </div>
@endcomponent
