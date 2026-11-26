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
     * Display court catalog with category, region, and search filtering + pagination.
     */
    public function index(Request $request): View
    {
        $category = $request->query('category', 'all');
        $region = $request->query('region', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = Court::with('venue')->orderByDesc('id');

        if ($category !== 'all') {
            $mappedCategory = $category === 'minisoccer' ? 'mini_soccer' : $category;
            $query->where('sport_category', $mappedCategory);
        }

        if ($region !== 'all') {
            $query->whereHas('venue', function ($q) use ($region) {
                $q->where('address', 'like', '%'.$region.'%');
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('surface_type', 'like', '%'.$search.'%')
                    ->orWhereHas('venue', function ($vq) use ($search) {
                        $vq->where('name', 'like', '%'.$search.'%')
                            ->orWhere('address', 'like', '%'.$search.'%');
                    });
            });
        }

        $courts = $query->paginate(15)->withQueryString();

        return view('lapangan', compact('courts', 'category', 'region', 'search'));
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
