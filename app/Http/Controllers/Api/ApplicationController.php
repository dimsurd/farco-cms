<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * POST /api/applications/submit
     * Validates and stores a new job application with file upload.
     */
    public function submit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'occupancy_id' => ['required', 'exists:occupancies,id'],
            'full_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'phone_number' => ['required', 'string', 'max:50'],
            'message'      => ['nullable', 'string'],
            'file'         => ['required', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Store the uploaded file
        $filePath = $request->file('file')->store('applications', 'public');

        $application = Application::create([
            'occupancy_id' => $request->occupancy_id,
            'full_name'    => $request->full_name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'message'      => $request->message,
            'file_path'    => $filePath,
            'created_at'   => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your application has been submitted successfully.',
            'data'    => [
                'id'         => $application->id,
                'created_at' => $application->created_at,
            ],
        ], 201);
    }
}
