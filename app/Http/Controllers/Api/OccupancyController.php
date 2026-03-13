<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Occupancy;
use Illuminate\Http\JsonResponse;

class OccupancyController extends Controller
{
    /**
     * GET /api/occupancies
     * Returns all active job postings with department and location names.
     */
    public function index(): JsonResponse
    {
        $occupancies = Occupancy::query()
            ->where('status', 'active')
            ->with(['department:id,name', 'location:id,name'])
            ->select([
                'id',
                'job_title',
                'department_id',
                'location_id',
                'work_mode',
                'job_description',
                'status',
                'created_at',
            ])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($occupancy) => [
                'id'              => $occupancy->id,
                'job_title'       => $occupancy->job_title,
                'department'      => $occupancy->department?->name,
                'location'        => $occupancy->location?->name,
                'work_mode'       => $occupancy->work_mode,
                'job_description' => $occupancy->job_description,
                'created_at'      => $occupancy->created_at,
            ]);

        return response()->json([
            'success' => true,
            'data'    => $occupancies,
        ]);
    }
}
