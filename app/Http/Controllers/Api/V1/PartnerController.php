<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Services\PartnerService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Partner Controller
 * Handles partner-related endpoints
 */
class PartnerController extends Controller
{
    use ApiResponse;

    public function __construct(private PartnerService $partnerService) {}

    /**
     * Get all partners with their locations
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $partners = $this->partnerService->getAllPartners($perPage);

        return $this->paginated(
            $partners->setCollection(
                $partners->getCollection()->map(fn($partner) => new PartnerResource($partner))
            ),
            'Partners retrieved successfully'
        );
    }

    /**
     * Get partner by ID
     */
    public function show($id)
    {
        $partner = $this->partnerService->getPartnerById($id);

        if (!$partner) {
            throw new ResourceNotFoundException('Partner not found');
        }

        return $this->success(
            new PartnerResource($partner),
            'Partner retrieved successfully'
        );
    }
}
