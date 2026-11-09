<?php

namespace App\Http\Controllers;

use App\Enums\CourtStatus;
use App\Enums\SportCategory;
use App\Models\Court;
use App\Models\Venue;
use App\Services\CourtAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected CourtAvailabilityService $availabilityService
    ) {}

    /**
     * Display landing page with dynamic venue and court catalog.
     */
    public function index(): View
    {
        $venue = Venue::first();
        $courts = Court::with('venue')->get();

        // Representative courts for quick booking
        $futsalCourt = $courts->firstWhere('sport_category', SportCategory::Futsal);
        $badmintonCourt = $courts->firstWhere('sport_category', SportCategory::Badminton);
        $miniSoccerCourt = $courts->firstWhere('sport_category', SportCategory::MiniSoccer);
        $padelCourt = $courts->firstWhere('sport_category', SportCategory::Padel);

        return view('welcome', compact('venue', 'courts', 'futsalCourt', 'badmintonCourt', 'miniSoccerCourt', 'padelCourt'));
    }

    /**
     * JSON endpoint for interactive quick booking schedule on landing page.
     */
    public function quickSlots(Request $request): JsonResponse
    {
        $sportParam = $request->query('sport', 'futsal');
        $date = $request->query('date', Carbon::now()->toDateString());

        $category = match ($sportParam) {
            'badminton' => SportCategory::Badminton,
            'minisoccer' => SportCategory::MiniSoccer,
            'padel' => SportCategory::Padel,
            default => SportCategory::Futsal,
        };

        $court = Court::where('sport_category', $category)
            ->where('status', CourtStatus::Active)
            ->first();

        if (! $court) {
            $court = Court::where('sport_category', $category)->firstOrFail();
        }

        $availability = $this->availabilityService->getAvailability($court, $date);

        return response()->json($availability);
    }
}
