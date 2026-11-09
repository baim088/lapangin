<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Services\CourtAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourtController extends Controller
{
    public function __construct(
        protected CourtAvailabilityService $availabilityService
    ) {}

    /**
     * Display court catalog.
     */
    public function index(): View
    {
        $courts = Court::with('venue')->get();

        return view('lapangan', compact('courts'));
    }

    /**
     * Display court details and schedule.
     */
    public function show(string $slug, Request $request): View
    {
        $court = Court::with('venue')
            ->where('slug', $slug)
            ->firstOrFail();

        $date = $request->query('date', Carbon::now()->toDateString());
        $availability = $this->availabilityService->getAvailability($court, $date);

        return view('lapangan-detail', compact('court', 'date', 'availability', 'slug'));
    }

    /**
     * Return hourly slot availability as JSON for Alpine.js.
     */
    public function slots(string $slug, Request $request): JsonResponse
    {
        $court = Court::with('venue')
            ->where('slug', $slug)
            ->firstOrFail();

        $date = $request->query('date', Carbon::now()->toDateString());
        $availability = $this->availabilityService->getAvailability($court, $date);

        return response()->json($availability);
    }
}
