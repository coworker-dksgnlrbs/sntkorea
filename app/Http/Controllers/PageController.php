<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerResource;
use App\Http\Resources\BoardResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\FacilityResource;
use App\Http\Resources\NoticeResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\UserResource;
use App\Models\Banner;
use App\Models\Notice;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "category_id" => "nullable|integer"
        ]);

        $banners = Banner::latest()->paginate(12);

        $reviews = Review::latest()->paginate(30);

        return Inertia::render("Index", [
            "banners" => BannerResource::collection($banners),
            "reviews" => ReviewResource::collection($reviews),
        ]);
    }

    public function front()
    {
        return Inertia::render("Contents/Front");
    }

    public function design()
    {
        return Inertia::render("Contents/Design");
    }

    public function backend()
    {
        return Inertia::render("Contents/Backend");
    }

    public function planning()
    {
        return Inertia::render("Contents/Planning");
    }

}
