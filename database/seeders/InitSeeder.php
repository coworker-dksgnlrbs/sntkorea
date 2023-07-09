<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Board;
use App\Models\Category;
use App\Models\Column;
use App\Models\Document;
use App\Models\Facility;
use App\Models\Guide;
use App\Models\Information;
use App\Models\Management;
use App\Models\Notice;
use App\Models\PayMethod;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\Concerns\Has;
use Symfony\Component\Console\Question\Question;

class InitSeeder extends Seeder
{
    protected $imgs = [
        "/images/example1.jpg",
        "/images/example2.jpg",
        "/images/example3.jpg",
        "/images/example4.jpg",
        "/images/example5.jpg",
        "/images/example6.jpg",
        "/images/example7.jpg",
        "/images/example8.jpg",
        "/images/example9.jpg",
        "/images/example10.jpg",
        "/images/example11.jpg",
        "/images/example12.jpg",
        "/images/example13.jpg",
        "/images/example14.jpg",
        "/images/example15.jpg",
        "/images/example16.jpg",
    ];

    protected $user;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        DB::table("media")->truncate();
        Banner::truncate();
        Review::truncate();
        Notice::truncate();
        Column::truncate();
        Portfolio::truncate();
        DB::statement("SET foreign_key_checks=1");

        $this->createBanners();

        $this->createReviews();

        $this->createColumns();

        $this->createPortfolios();
    }

    public function createBanners()
    {
        $items = [
            [
                "title" => "원하는 대로 커스텀하고 <br>관리와 서비스를 쉽게 이용해보세요!",
                "description" => "반응형은 기본! 자연스러운 모션까지 템플릿에서 만나볼 수 없는 디테일을 만나보세요.",
                "img" => "/images/main/mainbanner.png"
            ]
        ];

        foreach($items as $item){
            $createdItem = Banner::create(Arr::except($item, "img"));

            $createdItem->addMedia(public_path($item["img"]))->preservingOriginal()->toMediaCollection("img", "s3");
        }
    }

    public function createReviews()
    {
        $items = [
            [
                "title" => "퀄리티가 정말 높아요!",
                "description" => "홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.",
                "name" => "윤이서"
            ],
            [
                "title" => "유지보수까지 완벽해요",
                "description" => "홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.",
                "name" => "신형준"
            ],
            [
                "title" => "기획까지 해줘서 너무 편했어요",
                "description" => "홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.홈페이지 제작에서 끝나는 것이 아닌, 고객의 마음을 헤아리고 사업자의 마음을 헤아립니다.
                                    서로에게 필요한 UI · UX , 기능, SEO 최적화 까지 노력을 전달하는 코워커웹입니다.",
                "name" => "이향주"
            ],
        ];

        foreach($items as $item){
            $createdItem = Review::create($item);
        }
    }

    public function createColumns()
    {
        $items = [
            [
                "title" => "[1] 홈페이지 제작, 전문가와 함께하세요.<br/>코워커웹이 말하는 홈페이지 제작.",
                "description" => '코워커웹은 고객과 함께 성장합니다.<br>
											"지식채용플랫폼 코워커넷"을 운영하며 공감하고 고민합니다.<br>
											고객의 마음을 헤아리는 코워커넷이 되겠습니다.',
                "detail" => "테스트내용",
                "img" => "/images/portfolio_dt1.jpg"
            ],

            [
                "title" => "[2] 홈페이지 제작, 전문가와 함께하세요.<br/>코워커웹이 말하는 홈페이지 제작.",
                "description" => '코워커웹은 고객과 함께 성장합니다.<br>
											"지식채용플랫폼 코워커넷"을 운영하며 공감하고 고민합니다.<br>
											고객의 마음을 헤아리는 코워커넷이 되겠습니다.',
                "detail" => "테스트내용",
                "img" => "/images/portfolio_dt1.jpg"
            ],
        ];

        foreach($items as $item){
            $createdItem = Column::create(Arr::except($item, "img"));

            $createdItem->addMedia(public_path($item["img"]))->preservingOriginal()->toMediaCollection("img", "s3");
        }
    }

    public function createPortfolios()
    {
        $items = [
            [
                "category" => "RESPONSIVE WEB",
                "company" => "회사명",
                "title" => "온라인 셀러를 위한 이미지 편집 OCTO STUDIO <br>반응형 웹 홈페이지 제작",
                "description" => '해외상품을 너무 쉽게 만날 수 있는 요즘, 이미지로 신뢰감을 올려보세요.
고객의 구매 의사 결정에 번역은 가장 중요한 요소입니다.
상승하는 구매전환율을 광고하기 위한 옥토스튜디오의 홈페이지를 제작했습니다.',
                "pc" => "/images/portfolio_dt1.jpg",
                "mobile" => "/images/portfolio_dt1.jpg",
                "imgs" => "/images/portfolio_dt1.jpg",
                "started_at" => Carbon::now(),
                "finished_at" => Carbon::now()->addDays(3),
            ],

            [
                "category" => "RESPONSIVE WEB",
                "company" => "회사명",
                "title" => "Korket' 반응형 웹 디자인",
                "description" => '해외상품을 너무 쉽게 만날 수 있는 요즘, 이미지로 신뢰감을 올려보세요.
고객의 구매 의사 결정에 번역은 가장 중요한 요소입니다.
상승하는 구매전환율을 광고하기 위한 옥토스튜디오의 홈페이지를 제작했습니다.',
                "pc" => "/images/portfolio_dt1.jpg",
                "mobile" => "/images/portfolio_dt1.jpg",
                "imgs" => "/images/portfolio_dt1.jpg",
                "started_at" => Carbon::now(),
                "finished_at" => Carbon::now()->addDays(3),
            ],

            [
                "category" => "RESPONSIVE WEB",
                "company" => "회사명",
                "title" => "우리뷰",
                "description" => '해외상품을 너무 쉽게 만날 수 있는 요즘, 이미지로 신뢰감을 올려보세요.
고객의 구매 의사 결정에 번역은 가장 중요한 요소입니다.
상승하는 구매전환율을 광고하기 위한 옥토스튜디오의 홈페이지를 제작했습니다.',
                "pc" => "/images/portfolio_dt1.jpg",
                "mobile" => "/images/portfolio_dt1.jpg",
                "imgs" => "/images/portfolio_dt1.jpg",
                "started_at" => Carbon::now(),
                "finished_at" => Carbon::now()->addDays(3),
            ],

            [
                "category" => "RESPONSIVE WEB",
                "company" => "회사명",
                "title" => "겔튼",
                "description" => '해외상품을 너무 쉽게 만날 수 있는 요즘, 이미지로 신뢰감을 올려보세요.
고객의 구매 의사 결정에 번역은 가장 중요한 요소입니다.
상승하는 구매전환율을 광고하기 위한 옥토스튜디오의 홈페이지를 제작했습니다.',
                "pc" => "/images/portfolio_dt1.jpg",
                "mobile" => "/images/portfolio_dt1.jpg",
                "imgs" => "/images/portfolio_dt1.jpg",
                "started_at" => Carbon::now(),
                "finished_at" => Carbon::now()->addDays(3),
            ],
        ];

        foreach($items as $item){
            $createdItem = Portfolio::create(Arr::except($item, ["pc", "mobile", "imgs"]));

            $createdItem->addMedia(public_path($item["pc"]))->preservingOriginal()->toMediaCollection("pc", "s3");
            $createdItem->addMedia(public_path($item["mobile"]))->preservingOriginal()->toMediaCollection("mobile", "s3");
            $createdItem->addMedia(public_path($item["imgs"]))->preservingOriginal()->toMediaCollection("imgs", "s3");
        }
    }
}
