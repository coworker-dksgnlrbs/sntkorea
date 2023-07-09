<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "word" => "nullable|string|max:5000"
        ]);

        $documents = new Document();

        if($request->word)
            $documents = $documents->where("title", "LIKE", "%".$request->word."%");

        $documents = $documents->orderBy("created_at", "desc")->paginate(9);

        return Inertia::render("Documents/Index", [
            "word" => $request->word ?? "",
            "documents" => DocumentResource::collection($documents),
        ]);
    }

    public function show(Document $document)
    {
        return Inertia::render("Documents/Show", [
            "document" => DocumentResource::make($document),
        ]);
    }
}
