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
        $venue = Venue::orderByDesc('id')->first();
        $courts = Court::with('venue')
            ->where('status', CourtStatus::Active)
            ->orderByDesc('id')
            ->get();

        if ($courts->isEmpty()) {
            $courts = Court::with('venue')->orderByDesc('id')->get();
        }

        // Representative courts for quick booking (prioritizing real Jakarta PDF venues)
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
            ->orderByDesc('id')
            ->first();

        if (! $court) {
            $court = Court::where('sport_category', $category)->orderByDesc('id')->firstOrFail();
        }

        $availability = $this->availabilityService->getAvailability($court, $date);

        return response()->json($availability);
    }
}
