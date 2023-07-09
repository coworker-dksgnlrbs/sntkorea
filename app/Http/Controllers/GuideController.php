<?php

namespace App\Http\Controllers;

use App\Http\Resources\GuideResource;
use App\Models\Guide;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:5000"
        ]);

        $guides = new Guide();

        if($request->word)
            $guides = $guides->where("title", "LIKE", "%".$request->word."%");

        $guides = $guides->orderBy("created_at", "desc")->paginate(9);

        return Inertia::render("Guides/Index", [
            "word" => $request->word ?? "",
            "guides" => GuideResource::collection($guides),
        ]);
    }

    public function show(Guide $guide)
    {
        return Inertia::render("Guides/Show", [
            "guide" => GuideResource::make($guide),
        ]);
    }
}
