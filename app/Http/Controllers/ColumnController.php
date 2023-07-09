<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColumnResource;
use App\Models\Column;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ColumnController extends Controller
{
    public function index()
    {
        $items = Column::latest()->paginate(16);

        return Inertia::render("Columns/Index", [
            "items" => ColumnResource::collection($items)
        ]);
    }

    public function show(Column $column)
    {
        return Inertia::render("Columns/Show", [
            "item" => ColumnResource::make($column)
        ]);
    }
}
