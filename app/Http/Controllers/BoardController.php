<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:5000"
        ]);

        $boards = new Board();

        if($request->word)
            $boards = $boards->where("title", "LIKE", "%".$request->word."%");

        $boards = $boards->orderBy("created_at", "desc")->paginate(9);

        return Inertia::render("Boards/Index", [
            "word" => $request->word ?? "",
            "boards" => BoardResource::collection($boards),
        ]);
    }

    public function show(Board $board)
    {
        return Inertia::render("Boards/Show", [
            "board" => BoardResource::make($board),
        ]);
    }
}
