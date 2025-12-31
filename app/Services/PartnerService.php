<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Pagination\LengthAwarePaginator;

class PartnerService
{
    /**
     * Get all active partners with their locations
     */
    public function getAllPartners(int $perPage = 10): LengthAwarePaginator
    {
        return Partner::query()
            ->active()
            ->with(['activeLocations'])
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get partner by ID with all relationships
     */
    public function getPartnerById(int $id): ?Partner
    {
        return Partner::query()
            ->active()
            ->with(['activeLocations.activeCourts'])
            ->find($id);
    }
}
