<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagementResource;
use App\Models\Management;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagementController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:5000"
        ]);

        $managements = new Management();

        if($request->word)
            $managements = $managements->where("title", "LIKE", "%".$request->word."%");

        $managements = $managements->orderBy("created_at", "desc")->paginate(9);

        return Inertia::render("Managements/Index", [
            "word" => $request->word ?? "",
            "managements" => ManagementResource::collection($managements),
        ]);
    }

    public function show(Management $management)
    {
        return Inertia::render("Managements/Show", [
            "management" => ManagementResource::make($management),
        ]);
    }
}
