<?php

namespace App\Services;

use App\Models\PartnerLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PartnerLocationService
{
    /**
     * Get all active partner locations with relationships
     */
    public function getAllLocations(int $perPage = 10): LengthAwarePaginator
    {
        return PartnerLocation::query()
            ->active()
            ->with([
                'partner' => function ($query) {
                    $query->active();
                },
                'activeCourts'
            ])
            ->whereHas('partner', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get partner location by ID with all relationships
     */
    public function getLocationById(int $id): ?PartnerLocation
    {
        return PartnerLocation::query()
            ->active()
            ->with([
                'partner' => function ($query) {
                    $query->active();
                },
                'activeCourts.activeSchedules' => function ($query) {
                    $query->upcoming()->limit(10);
                }
            ])
            ->whereHas('partner', function ($query) {
                $query->where('is_active', true);
            })
            ->find($id);
    }

    /**
     * Search partner locations by city
     */
    public function searchByCity(string $city, int $perPage = 10): LengthAwarePaginator
    {
        return PartnerLocation::query()
            ->active()
            ->byCity($city)
            ->with([
                'partner' => function ($query) {
                    $query->active();
                },
                'activeCourts'
            ])
            ->whereHas('partner', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('city', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get nearest partner locations using Haversine formula
     */
    public function getNearestLocations(
        float $latitude,
        float $longitude,
        float $radiusKm = 10,
        int $perPage = 10
    ): LengthAwarePaginator {
        $locations = PartnerLocation::query()
            ->active()
            ->with([
                'partner' => function ($query) {
                    $query->active();
                },
                'activeCourts'
            ])
            ->whereHas('partner', function ($query) {
                $query->where('is_active', true);
            })
            ->get()
            ->filter(function ($location) use ($latitude, $longitude, $radiusKm) {
                $distance = $location->distanceFrom($latitude, $longitude);
                $location->distance = $distance;
                return $distance <= $radiusKm;
            })
            ->sortBy('distance')
            ->values();

        // Manual pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $locations->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $currentItems,
            $locations->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    /**
     * Get courts for a specific partner location
     */
    public function getCourtsByLocation(int $locationId)
    {
        $location = PartnerLocation::query()
            ->active()
            ->with(['activeCourts.activeSchedules' => function ($query) {
                $query->upcoming()->limit(5);
            }])
            ->find($locationId);

        return $location?->activeCourts;
    }
}
