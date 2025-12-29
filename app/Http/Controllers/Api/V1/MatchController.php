<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMatchRequest;
use App\Http\Requests\UpdateMatchRequest;
use App\Models\PadelMatch;
use App\Traits\ApiResponse;

/**
 * Match Controller
 * Handles match management
 */
class MatchController extends Controller
{
    use ApiResponse;

    /**
     * Get all matches with filtering
     */
    public function index()
    {
        $perPage = request()->get('per_page', 15);
        $courtId = request()->get('court_id');
        $status = request()->get('status');
        $skillLevel = request()->get('skill_level');

        $query = PadelMatch::query();

        if ($courtId) {
            $query->where('court_id', $courtId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($skillLevel) {
            $query->where('skill_level', $skillLevel);
        }

        // Only return upcoming or ongoing matches by default
        if (!$status) {
            $query->whereIn('status', ['open', 'full', 'ongoing'])
                ->where('match_date_time', '>=', now());
        }

        $matches = $query
            ->with(['court', 'creator', 'players'])
            ->orderBy('match_date_time', 'asc')
            ->paginate($perPage);

        return $this->paginated($matches, 'Matches retrieved successfully');
    }

    /**
     * Get a single match
     */
    public function show(PadelMatch $match)
    {
        return $this->success(
            $match->load(['court', 'creator', 'players', 'sets.games']),
            'Match retrieved successfully'
        );
    }

    /**
     * Create a new match
     */
    public function store(CreateMatchRequest $request)
    {
        $user = auth()->user();

        $match = PadelMatch::create(
            array_merge(
                $request->validated(),
                ['creator_id' => $user->id]
            )
        );

        return $this->success(
            $match->load('court', 'creator'),
            'Match created successfully',
            201
        );
    }

    /**
     * Update a match
     */
    public function update(UpdateMatchRequest $request, PadelMatch $match)
    {
        $user = auth()->user();

        // Only creator or admin can update
        if ($match->creator_id !== $user->id && !$user->isAdmin()) {
            return $this->error(
                'You are not authorized to update this match',
                [],
                403
            );
        }

        $match->update($request->validated());

        return $this->success(
            $match->load('court', 'creator', 'players'),
            'Match updated successfully'
        );
    }

    /**
     * Delete a match
     */
    public function destroy(PadelMatch $match)
    {
        $user = auth()->user();

        if ($match->creator_id !== $user->id && !$user->isAdmin()) {
            return $this->error(
                'You are not authorized to delete this match',
                [],
                403
            );
        }

        $match->delete();

        return $this->success(
            [],
            'Match deleted successfully'
        );
    }

    /**
     * Join a match
     */
    public function join(PadelMatch $match)
    {
        $user = auth()->user();

        // Check if user can join
        if (!$match->canUserJoin($user)) {
            $reasons = [];
            if ($match->isFull()) {
                $reasons[] = 'Match is full';
            }
            if ($match->creator_id === $user->id) {
                $reasons[] = 'You are the creator of this match';
            }
            if ($match->players()->where('user_id', $user->id)->exists()) {
                $reasons[] = 'You already joined this match';
            }

            return $this->error(
                'Cannot join match',
                ['reason' => implode(', ', $reasons)],
                400
            );
        }

        // Add player to match
        $match->addPlayer($user);
        $match->increment('current_players');

        // Update status if full
        if ($match->isFull()) {
            $match->update(['status' => 'full']);
        }

        return $this->success(
            $match->load('players'),
            'Joined match successfully'
        );
    }

    /**
     * Leave a match
     */
    public function leave(PadelMatch $match)
    {
        $user = auth()->user();

        $match->removePlayer($user);
        $match->decrement('current_players');

        // Update status back to open if it was full
        if ($match->status->value === 'full' && !$match->isFull()) {
            $match->update(['status' => 'open']);
        }

        return $this->success(
            $match->load('players'),
            'Left match successfully'
        );
    }

    /**
     * Get players in a match
     */
    public function players(PadelMatch $match)
    {
        return $this->success(
            $match->players()->get(),
            'Match players retrieved successfully'
        );
    }

    /**
     * Find open matches nearby
     */
    public function nearby()
    {
        $latitude = request()->get('latitude');
        $longitude = request()->get('longitude');
        $radius = request()->get('radius', 10); // km
        $skillLevel = request()->get('skill_level');

        if (!$latitude || !$longitude) {
            return $this->error(
                'Latitude and longitude are required',
                ['location' => 'Missing coordinates'],
                400
            );
        }

        $query = PadelMatch::where('status', '!=', 'cancelled')
            ->where('match_date_time', '>=', now())
            ->with('court');

        if ($skillLevel) {
            $query->where('skill_level', $skillLevel);
        }

        // Filter by distance
        $matches = $query
            ->get()
            ->map(function ($match) use ($latitude, $longitude, $radius) {
                $distance = $this->calculateDistance(
                    $latitude,
                    $longitude,
                    $match->court->latitude,
                    $match->court->longitude
                );

                if ($distance <= $radius) {
                    $match->distance = $distance;
                    return $match;
                }

                return null;
            })
            ->filter()
            ->sortBy('distance')
            ->values();

        return $this->success(
            $matches,
            'Nearby matches retrieved successfully'
        );
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // Km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
