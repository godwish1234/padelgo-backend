<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtSchedule;

class CourtService
{
    /**
     * Get court by ID with schedules
     */
    public function getCourtById(int $id): ?Court
    {
        return Court::query()
            ->active()
            ->with([
                'partnerLocation.partner',
                'activeSchedules' => function ($query) {
                    $query->upcoming();
                }
            ])
            ->find($id);
    }

    /**
     * Get schedules for a specific court
     */
    public function getSchedulesByCourt(int $courtId, int $perPage = 20)
    {
        return CourtSchedule::query()
            ->active()
            ->where('court_id', $courtId)
            ->upcoming()
            ->with(['court.partnerLocation.partner'])
            ->paginate($perPage);
    }
}
