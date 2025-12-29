<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update Match Request
 * Validates match update data
 */
class UpdateMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'match_date_time' => ['nullable', 'date_format:Y-m-d H:i:s', 'after:now'],
            'max_players' => ['nullable', 'integer', 'min:2', 'max:20'],
            'skill_level' => ['nullable', 'in:beginner,intermediate,advanced'],
            'match_type' => ['nullable', 'in:friendly,competitive'],
            'status' => ['nullable', 'in:open,full,ongoing,finished,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
