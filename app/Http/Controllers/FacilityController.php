<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\FacilityResource;
use App\Models\Category;
use App\Models\Facility;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "category_id" => "nullable|integer"
        ]);

        $category = null;

        if($request->category_id)
            $category = Category::find($request->category_id);

        $facilities = new Facility();

        if($request->category_id)
            $facilities = $facilities->where("category_id", $request->category_id);

        $facilities = $facilities->orderBy("created_at", "desc")->paginate(9);

        $categories = Category::orderBy("id", "asc")->paginate(30);

        return Inertia::render("Facilities/Index", [
            "category_id" => $request->category_id ?? "",
            "categories" => CategoryResource::collection($categories),
            "category" => $category ? CategoryResource::make($category) : "",
            "facilities" => FacilityResource::collection($facilities),
        ]);
    }

    public function show(Facility $facility)
    {
        $categories = Category::orderBy("id", "asc")->paginate(30);

        $category = $facility->category;

        return Inertia::render("Facilities/Show", [
            "category" => CategoryResource::make($category),

            "categories" => CategoryResource::collection($categories),

            "facility" => FacilityResource::make($facility),
        ]);
    }
}
