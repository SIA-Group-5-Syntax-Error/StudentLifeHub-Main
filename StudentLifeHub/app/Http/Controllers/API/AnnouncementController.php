<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement; // Ensure you have an Announcement model in this project

class AnnouncementController extends Controller
{
    /**
     * GET /api/announcements
     * Acting as a local proxy: returns all announcements.
     */
    public function index()
    {
        try {
            $announcements = Announcement::all();
            return response()->json($announcements, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Proxy Error: Failed to retrieve announcements.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/announcements
     * Acting as a local proxy: validates and saves an announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            $announcement = Announcement::create($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Announcement added successfully via proxy',
                'data' => $announcement
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Proxy Error: Failed to create announcement.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/announcements/{id}
     * Acting as a local proxy: fetches a specific record.
     */
    public function show($id)
    {
        try {
            $announcement = Announcement::find($id);

            if (!$announcement) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Announcement not found.'
                ], 404);
            }

            return response()->json($announcement, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT/PATCH /api/announcements/{id}
     * Acting as a local proxy: updates a record.
     */
    public function update(Request $request, $id)
    {
        try {
            $announcement = Announcement::find($id);

            if (!$announcement) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Announcement not found.'
                ], 404);
            }

            $announcement->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Announcement updated successfully',
                'data' => $announcement
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/announcements/{id}
     * Acting as a local proxy: deletes a record.
     */
    public function destroy($id)
    {
        try {
            $announcement = Announcement::find($id);

            if (!$announcement) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Announcement not found.'
                ], 404);
            }

            $announcement->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Announcement deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }
}