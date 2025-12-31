<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\NearestLocationRequest;
use App\Http\Requests\SearchLocationRequest;
use App\Http\Resources\CourtResource;
use App\Http\Resources\PartnerLocationResource;
use App\Services\PartnerLocationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Partner Location Controller
 * Handles partner location-related endpoints
 */
class PartnerLocationController extends Controller
{
    use ApiResponse;

    public function __construct(private PartnerLocationService $locationService) {}

    /**
     * Get all partner locations
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $locations = $this->locationService->getAllLocations($perPage);

        return $this->paginated(
            $locations->setCollection(
                $locations->getCollection()->map(fn($location) => new PartnerLocationResource($location))
            ),
            'Partner locations retrieved successfully'
        );
    }

    /**
     * Get partner location by ID
     */
    public function show($id)
    {
        $location = $this->locationService->getLocationById($id);

        if (!$location) {
            throw new ResourceNotFoundException('Partner location not found');
        }

        return $this->success(
            new PartnerLocationResource($location),
            'Partner location retrieved successfully'
        );
    }

    /**
     * Search partner locations by city
     */
    public function search(SearchLocationRequest $request)
    {
        $city = $request->input('city');
        $perPage = $request->input('per_page', 10);
        
        $locations = $this->locationService->searchByCity($city, $perPage);

        return $this->paginated(
            $locations->setCollection(
                $locations->getCollection()->map(fn($location) => new PartnerLocationResource($location))
            ),
            'Partner locations retrieved successfully'
        );
    }

    /**
     * Get nearest partner locations
     */
    public function nearest(NearestLocationRequest $request)
    {
        $latitude = $request->input('lat');
        $longitude = $request->input('lng');
        $radiusKm = $request->input('radius_km', 10);
        $perPage = $request->input('per_page', 10);

        $locations = $this->locationService->getNearestLocations(
            $latitude,
            $longitude,
            $radiusKm,
            $perPage
        );

        return $this->paginated(
            $locations->setCollection(
                $locations->getCollection()->map(fn($location) => new PartnerLocationResource($location))
            ),
            'Nearest partner locations retrieved successfully'
        );
    }

    /**
     * Get courts for a specific partner location
     */
    public function courts($id)
    {
        $courts = $this->locationService->getCourtsByLocation($id);

        if ($courts === null) {
            throw new ResourceNotFoundException('Partner location not found');
        }

        return $this->success(
            CourtResource::collection($courts),
            'Courts retrieved successfully'
        );
    }
}
