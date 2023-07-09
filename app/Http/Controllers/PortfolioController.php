<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColumnResource;
use App\Http\Resources\PortfolioResource;
use App\Models\Column;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index()
    {
        $items = Portfolio::latest()->paginate(16);

        return Inertia::render("Portfolios/Index", [
            "items" => PortfolioResource::collection($items)
        ]);
    }

    public function show(Portfolio $portfolio)
    {
        return Inertia::render("Portfolios/Show", [
            "item" => PortfolioResource::make($portfolio)
        ]);
    }
}
