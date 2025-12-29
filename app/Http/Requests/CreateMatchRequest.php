<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Create Match Request
 * Validates match creation data
 */
class CreateMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'court_id' => ['required', 'exists:courts,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'match_date_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:now'],
            'max_players' => ['required', 'integer', 'min:2', 'max:20'],
            'skill_level' => ['required', 'in:beginner,intermediate,advanced'],
            'match_type' => ['required', 'in:friendly,competitive'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'court_id.required' => 'Court ID is required',
            'court_id.exists' => 'Court not found',
            'title.required' => 'Match title is required',
            'match_date_time.required' => 'Match date and time are required',
            'match_date_time.after' => 'Match must be scheduled for a future date',
            'max_players.required' => 'Max players is required',
            'max_players.min' => 'Minimum 2 players required',
            'skill_level.required' => 'Skill level is required',
            'match_type.required' => 'Match type is required',
        ];
    }
}
