<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourtRequest;
use App\Http\Requests\UpdateCourtRequest;
use App\Http\Resources\CourtResource;
use App\Http\Resources\CourtScheduleResource;
use App\Models\Court;
use App\Services\CourtService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Court Controller
 * Handles court management
 */
class CourtController extends Controller
{
    use ApiResponse;

    public function __construct(private ?CourtService $courtService = null) {}

    /**
     * Get all courts with pagination and filtering
     */
    public function index()
    {
        $perPage = request()->get('per_page', 15);
        $city = request()->get('city');
        $latitude = request()->get('latitude');
        $longitude = request()->get('longitude');
        $radius = request()->get('radius', 50); // km

        $query = Court::where('is_active', true);

        // Filter by city
        if ($city) {
            $query->where('city', 'ilike', "%$city%");
        }

        // Filter by location radius (simple distance calculation)
        if ($latitude && $longitude) {
            $query->selectRaw(
                "*, (6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance"
            )
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        } else {
            $query->orderBy('name');
        }

        $courts = $query->paginate($perPage);

        return $this->paginated($courts, 'Courts retrieved successfully');
    }

    /**
     * Get a single court
     */
    public function show(Court $court)
    {
        return $this->success(
            $court->load('partner', 'adminUser', 'matches'),
            'Court retrieved successfully'
        );
    }

    /**
     * Create a new court (admin only)
     */
    public function store(CreateCourtRequest $request)
    {
        $court = Court::create($request->validated());

        return $this->success(
            $court->load('partner', 'adminUser'),
            'Court created successfully',
            201
        );
    }

    /**
     * Update a court
     */
    public function update(UpdateCourtRequest $request, Court $court)
    {
        // Check if user is court admin or super admin
        $user = auth()->user();
        if (!$user->isAdmin() && $court->admin_user_id !== $user->id) {
            return $this->error(
                'You are not authorized to update this court',
                [],
                403
            );
        }

        $court->update($request->validated());

        return $this->success(
            $court->load('partner', 'adminUser'),
            'Court updated successfully'
        );
    }

    /**
     * Delete a court
     */
    public function destroy(Court $court)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $court->admin_user_id !== $user->id) {
            return $this->error(
                'You are not authorized to delete this court',
                [],
                403
            );
        }

        $court->delete();

        return $this->success(
            [],
            'Court deleted successfully'
        );
    }

    /**
     * Search courts nearby
     */
    public function nearby()
    {
        $latitude = request()->get('latitude');
        $longitude = request()->get('longitude');
        $radius = request()->get('radius', 10); // km
        $limit = request()->get('limit', 10);

        if (!$latitude || !$longitude) {
            return $this->error(
                'Latitude and longitude are required',
                ['location' => 'Missing coordinates'],
                400
            );
        }

        $courts = Court::where('is_active', true)
            ->selectRaw(
                "*, (6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance"
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->limit($limit)
            ->get();

        return $this->success(
            $courts,
            'Nearby courts retrieved successfully'
        );
    }

    /**
     * Get schedules for a specific court
     */
    public function schedules($id, Request $request)
    {
        if (!$this->courtService) {
            $this->courtService = app(CourtService::class);
        }

        $perPage = $request->query('per_page', 20);
        $schedules = $this->courtService->getSchedulesByCourt($id, $perPage);

        if ($schedules->isEmpty() && $schedules->currentPage() === 1) {
            // Check if court exists
            $court = $this->courtService->getCourtById($id);
            if (!$court) {
                throw new ResourceNotFoundException('Court not found');
            }
        }

        return $this->paginated(
            $schedules->setCollection(
                $schedules->getCollection()->map(fn($schedule) => new CourtScheduleResource($schedule))
            ),
            'Court schedules retrieved successfully'
        );
    }
}

