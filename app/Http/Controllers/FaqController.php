<?php

namespace App\Http\Controllers;

use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::orderBy("created_at", 'desc')->paginate(10);

        return Inertia::render("Faqs/Index", [
            "faqs" => FaqResource::collection($faqs)
        ]);
    }
}
