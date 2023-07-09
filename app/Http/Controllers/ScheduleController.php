<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "year" => "nullable|integer",
            "month" => "nullable|integer",
        ]);

        $request["year"] = $request->year ?? (int) Carbon::now()->format("Y");
        $request["month"] = $request->month ?? (int) Carbon::now()->format("m");

        $startedAt = Carbon::now()
            ->setYear($request->year)
            ->setMonth($request->month)
            ->startOfMonth()
            ->startOfDay();

        $finishedAt = Carbon::now()
            ->setYear($request->year)
            ->setMonth($request->month)
            ->endOfMonth()
            ->endOfDay();

        $schedules = Schedule::where("started_at", ">=", $startedAt)
                ->where("finished_at", "<=", $finishedAt)
                ->latest()
                ->paginate(9);

        return Inertia::render("Schedules/Index", [
            "schedules" => ScheduleResource::collection($schedules),
            "year" => $request->year,
            "month" => $request->month,
        ]);
    }
}
