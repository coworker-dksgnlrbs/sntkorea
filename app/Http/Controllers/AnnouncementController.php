<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:5000"
        ]);

        $announcements = new Announcement();

        if($request->word)
            $announcements = $announcements->where("title", "LIKE", "%".$request->word."%");

        $announcements = $announcements->orderBy("created_at", "desc")->paginate(9);

        return Inertia::render("Announcements/Index", [
            "word" => $request->word ?? "",
            "announcements" => AnnouncementResource::collection($announcements),
        ]);
    }

    public function show(Announcement $announcement)
    {
        return Inertia::render("Announcements/Show", [
            "announcement" => AnnouncementResource::make($announcement),
        ]);
    }
}
