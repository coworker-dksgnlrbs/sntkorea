<?php

namespace App\Http\Controllers;

use App\Http\Resources\QnaResource;
use App\Models\Qna;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QnaController extends Controller
{
    public function index()
    {
        $qnas = auth()->user()->qnas()->orderBy("created_at", "desc")->paginate(5);

        return Inertia::render("Qnas/Index",[
            "qnas" => QnaResource::collection($qnas)
        ]);
    }

    public function create()
    {
        return Inertia::render("Qnas/Create");
    }

    public function show(Qna $qna)
    {
        return Inertia::render("Qnas/Show", [
            "item" => QnaResource::make($qna)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([

        ]);

        $qna = Qna::create($request->except("files"));

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $qna->addMedia($file)->toMediaCollection("files", "s3");
            }
        }

        return redirect()->back()->with("success", "성공적으로 처리되었습니다.");
    }
}
