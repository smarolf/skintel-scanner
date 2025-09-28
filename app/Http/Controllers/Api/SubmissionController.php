<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Product;
use App\Models\ProductRecommendation;
use App\Mail\CustomerAnalysisResultMail;
use App\Mail\InternalAnalysisNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    /**
     * Update submission with PersonalizedRoutine form data
     */
    public function updatePersonalizedRoutine(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $submission = Submission::where('uuid', $uuid)->first();

            if (!$submission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission not found'
                ], 404);
            }

            $updateData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone', ''),
            ];

            $submission->update($updateData);

            // Create product recommendations for each concern
            $this->createProductRecommendations($submission);

            // Send emails after creating recommendations
            $this->sendAnalysisEmails($submission);

            return response()->json([
                'success' => true,
                'message' => 'Submission updated successfully',
                'submission' => $submission->fresh(),
                'redirect_url' => route('recommendations', ['uuid' => $submission->uuid])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get submission details
     */
    public function show($uuid)
    {
        try {
            $submission = Submission::with('scanResults')->where('uuid', $uuid)->first();

            if (!$submission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'submission' => $submission
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create product recommendations for each concern type
     */
    private function createProductRecommendations(Submission $submission)
    {
        // Define all available concern types
        $concerns = [
            'redness',
            'texture', 
            'oiliness',
            'dryness',
            'poreVisibility',
            'darkCircles',
            'acneScarring'
        ];

        // Clear existing recommendations for this submission to avoid duplicates
        ProductRecommendation::where('submission_id', $submission->id)->delete();

        // Create one recommendation per concern
        foreach ($concerns as $concern) {
            // Get one active product for this concern type
            $product = Product::where('concern_type', $concern)
                             ->where('is_active', true)
                             ->first();

            if ($product) {
                ProductRecommendation::create([
                    'product_id' => $product->id,
                    'submission_id' => $submission->id,
                ]);
            }
        }
    }

    /**
     * Send analysis emails to customer and internal team
     */
    private function sendAnalysisEmails(Submission $submission)
    {
        try {
            // Load scan results for email templates
            $submission->load('scanResults');
            
            // Generate recommendations URL
            $recommendationsUrl = url(route('recommendations', ['uuid' => $submission->uuid]));

            // Send email to customer
            Mail::to($submission->email)->send(
                new CustomerAnalysisResultMail($submission, $recommendationsUrl)
            );

            // Send email to internal team
            $internalEmail = env('INTERNAL_EMAIL_SUBMISSION');
            if ($internalEmail) {
                Mail::to($internalEmail)->send(
                    new InternalAnalysisNotificationMail($submission)
                );
            }

        } catch (\Exception $e) {
            // Log email sending errors but don't fail the main process
            \Log::error('Failed to send analysis emails: ' . $e->getMessage());
        }
    }

    /**
     * Get product recommendations for a submission
     */
    public function getRecommendations($uuid)
    {
        try {
            $submission = Submission::with(['productRecommendations.product'])
                                  ->where('uuid', $uuid)
                                  ->first();

            if (!$submission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'submission' => $submission,
                'recommendations' => $submission->productRecommendations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recommendations: ' . $e->getMessage()
            ], 500);
        }
    }
}
