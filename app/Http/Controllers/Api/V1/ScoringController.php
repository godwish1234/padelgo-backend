<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PadelMatch;
use App\Models\Set;
use App\Models\Game;
use App\Traits\ApiResponse;

/**
 * Scoring Controller
 * Handles match scoring and set/game management
 */
class ScoringController extends Controller
{
    use ApiResponse;

    /**
     * Get match score/sets/games
     */
    public function show(PadelMatch $match)
    {
        return $this->success(
            $match->load('sets.games'),
            'Match score retrieved successfully'
        );
    }

    /**
     * Create a new set for the match
     */
    public function createSet(PadelMatch $match)
    {
        $user = auth()->user();
        if ($match->creator_id !== $user->id && !$user->isAdmin()) {
            return $this->error(
                'Not authorized to manage this match',
                [],
                403
            );
        }

        $setNumber = $match->sets()->count() + 1;

        $set = $match->sets()->create([
            'set_number' => $setNumber,
            'team_a_games' => 0,
            'team_b_games' => 0,
            'is_completed' => false,
        ]);

        return $this->success(
            $set->load('games'),
            'Set created successfully',
            201
        );
    }

    /**
     * Create a new game in the set
     */
    public function createGame(PadelMatch $match, Set $set)
    {
        $user = auth()->user();
        if ($match->creator_id !== $user->id && !$user->isAdmin()) {
            return $this->error(
                'Not authorized to manage this match',
                [],
                403
            );
        }

        if ($set->is_completed) {
            return $this->error(
                'Cannot add games to a completed set',
                [],
                400
            );
        }

        $gameNumber = $set->games()->count() + 1;

        $game = $set->games()->create([
            'game_number' => $gameNumber,
            'team_a_points' => 0,
            'team_b_points' => 0,
            'is_completed' => false,
        ]);

        return $this->success(
            $game,
            'Game created successfully',
            201
        );
    }

    /**
     * Update game score
     */
    public function updateGameScore(PadelMatch $match, Set $set, Game $game)
    {
        $user = auth()->user();
        if ($match->creator_id !== $user->id && !$user->isAdmin()) {
            return $this->error(
                'Not authorized to update scores',
                [],
                403
            );
        }

        $validated = request()->validate([
            'team_a_points' => ['required', 'integer', 'min:0', 'max:7'],
            'team_b_points' => ['required', 'integer', 'min:0', 'max:7'],
            'is_completed' => ['nullable', 'boolean'],
        ]);

        $game->update($validated);

        return $this->success(
            $game,
            'Game score updated successfully'
        );
    }

    /**
     * Mark a set as completed
     */
    public function completeSet(PadelMatch $match, Set $set)
    {
        $user = auth()->user();
        if ($match->creator_id !== $user->id && !$user->isAdmin()) {
            return $this->error(
                'Not authorized to manage this match',
                [],
                403
            );
        }

        $incompleteGames = $set->games()->where('is_completed', false)->count();
        if ($incompleteGames > 0) {
            return $this->error(
                'All games in the set must be completed first',
                [],
                400
            );
        }

        $set->update(['is_completed' => true]);

        return $this->success(
            $set->load('games'),
            'Set marked as completed'
        );
    }

    /**
     * Mark match as finished
     */
    public function finishMatch(PadelMatch $match)
    {
        $user = auth()->user();
        if ($match->creator_id !== $user->id && !$user->isAdmin()) {
            return $this->error(
                'Not authorized to manage this match',
                [],
                403
            );
        }

        $match->update(['status' => 'finished']);

        return $this->success(
            $match->load('sets.games'),
            'Match marked as finished'
        );
    }
}
