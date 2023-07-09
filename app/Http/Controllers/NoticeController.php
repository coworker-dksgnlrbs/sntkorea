<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Http\Resources\NoticeResource;
use App\Models\Event;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:5000"
        ]);

        $notices = new Notice();

        if($request->word)
            $notices = $notices->where("title", "LIKE", "%".$request->word."%");

        $notices = $notices->orderBy("created_at", "desc")->paginate(9);

        return Inertia::render("Notices/Index", [
            "word" => $request->word ?? "",
            "notices" => NoticeResource::collection($notices),
        ]);
    }

    public function show(Notice $notice)
    {
        return Inertia::render("Notices/Show", [
            "notice" => NoticeResource::make($notice),
        ]);
    }
}
