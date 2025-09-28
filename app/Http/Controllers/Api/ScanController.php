<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\ScanResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ScanController extends Controller
{
    /**
     * Process the captured image and analyze skin
     */
    public function captureAndAnalyze(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|string', // Base64 image data
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image data',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Decode base64 image
            $imageData = $request->input('image');

            // Remove data URL prefix if present
            if (strpos($imageData, 'data:image') === 0) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }

            $imageData = base64_decode($imageData);

            if (!$imageData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image format'
                ], 422);
            }

            // Generate unique filename
            $filename = 'scans/' . Str::uuid() . '.png';
            // Store image
            Storage::disk('public')->put($filename, $imageData);

            // Create submission record
            $submission = Submission::create([
                'scanned_photo' => $filename,
                'name' => 'Anonymous', // Will be updated later via PersonalizedRoutine form
                'email' => 'anonymous@temp.com', // Will be updated later
                'phone' => '', // Will be updated later
            ]);

            // Generate AI analysis results (simulated)
            $this->generateScanResults($submission->id);

            return response()->json([
                'success' => true,
                'submission_uuid' => $submission->uuid,
                'message' => 'Image analyzed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get scan results for a submission
     */
    public function getResults($uuid)
    {
        try {
            $submission = Submission::with('scanResults')->where('uuid', $uuid)->first();

            if (!$submission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission not found'
                ], 404);
            }

            // Format results for frontend
            $scanResults = [];
            foreach ($submission->scanResults as $result) {
                $scanResults[$this->getConcernKey($result->concern)] = [
                    'name' => $result->concern,
                    'score' => $result->score,
                    'description' => $result->explanation
                ];
            }


            return response()->json([
                'success' => true,
                'submission' => [
                    'id' => $submission->id,
                    'uuid' => $submission->uuid,
                    'scanned_photo_url' => $submission->scanned_photo_url,
                    'name' => $submission->name,
                    'email' => $submission->email,
                    'phone' => $submission->phone,
                ],
                'scan_results' => $scanResults
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve results: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate simulated AI scan results
     */
    private function generateScanResults($submissionId)
    {
        $concerns = [
            'Redness' => 'redness',
            'Texture' => 'texture',
            'Oiliness' => 'oiliness',
            'Dryness' => 'dryness',
            'Pore Visibility' => 'poreVisibility',
            'Dark Circles' => 'darkCircles',
            'Acne Scarring' => 'acneScarring'
        ];

        foreach ($concerns as $concernName => $key) {
            $score = rand(1, 5);

            ScanResult::create([
                'submission_id' => $submissionId,
                'concern' => $concernName,
                'score' => $score,
                'explanation' => $this->getScoreDescription($score)
            ]);
        }
    }

    /**
     * Get description based on score
     */
    private function getScoreDescription($score)
    {
        $descriptions = [
            1 => 'Excellent - No concerns detected',
            2 => 'Good - Minor areas for improvement',
            3 => 'Fair - Some attention needed',
            4 => 'Needs attention - Moderate concerns',
            5 => 'High priority - Requires immediate care'
        ];

        return $descriptions[$score] ?? 'Unknown score';
    }

    /**
     * Convert concern name to key
     */
    private function getConcernKey($concernName)
    {
        $mapping = [
            'Redness' => 'redness',
            'Texture' => 'texture',
            'Oiliness' => 'oiliness',
            'Dryness' => 'dryness',
            'Pore Visibility' => 'poreVisibility',
            'Dark Circles' => 'darkCircles',
            'Acne Scarring' => 'acneScarring'
        ];

        return $mapping[$concernName] ?? strtolower(str_replace(' ', '', $concernName));
    }
}
