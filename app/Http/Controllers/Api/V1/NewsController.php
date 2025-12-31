<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * News Controller
 * Handles news read operations for frontend users
 */
class NewsController extends Controller
{
    use ApiResponse;

    /**
     * Get all news with pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $tag = $request->query('tag');

        $query = News::query()->orderBy('created_at', 'desc');

        // Filter by tag if provided
        if ($tag) {
            $query->where('tag', $tag);
        }

        $news = $query->paginate($perPage);

        return $this->paginated($news, 'News retrieved successfully');
    }

    /**
     * Get a single news item
     */
    public function show($id)
    {
        $news = News::find($id);

        if (!$news) {
            throw new ResourceNotFoundException('News not found');
        }

        return $this->success($news, 'News retrieved successfully');
    }

    /**
     * Get all unique tags
     */
    public function tags()
    {
        $tags = News::whereNotNull('tag')
            ->distinct()
            ->pluck('tag')
            ->filter()
            ->values();

        return $this->success($tags, 'Tags retrieved successfully');
    }
}
