<?php

namespace App\Http\Controllers;

use App\Models\assign_orders_volunteer;
use Illuminate\Http\Request;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AssignOrdersVolunteerController extends Controller
{

  public function indexVolunteersname(Request $request)
  {
      $token = $request->header('Authorization');
      if (!$token) {
          return response()->json([
              'status' => false,
              'message' => 'Authorization token not provided.',
          ], 401);
      }

      // Remove 'Bearer ' from the token string
      $token = str_replace('Bearer ', '', $token);
      $hashedToken = hash('sha256', $token);

      // Retrieve the authenticated user
      $charity = Auth::guard('api-charities')->user();

      // Check if user is authenticated
      if (!$charity) {
          return response()->json([
              'status' => false,
              'message' => 'Unauthorized',
          ], 401);
      }

      // Validate that the token matches the latest token
      if ($charity->latest_token !== $hashedToken) {
          return response()->json([
              'status' => false,
              'message' => 'Unauthorized: Invalid token',
          ], 401);
      }

      // Check if the token has the required capability
      if (!$charity->tokenCan('Charity Access Token')) {
          return response()->json([
              'status' => false,
              'message' => 'Unauthorized: Token does not have the required capability.',
          ], 403);
      }

      // Get the user ID
      $charityid = $charity->id;
      $volunteernames = Volunteer::where('charities_id', $charityid)
          ->where('status', 'accept')
          ->select('id', 'full_name')
          ->get();

      return response()->json([
          'status' => true,
          'message' => 'get the names successfully',
          'data' => $volunteernames,
          'pagination' => [
              'current_page' => 1,
              'total_pages' => 1,
              'total_items' => $volunteernames->count(),
              'items_per_page' => $volunteernames->count(),
          ],
      ], 200);
  }

    public function assignordersvolunteer(Request $request, $volunteerId, $beneficiaryId)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization token not provided.',
            ], 401);
        }

        // Remove 'Bearer ' from the token string
        $token = str_replace('Bearer ', '', $token);
        $hashedToken = hash('sha256', $token);

        // Retrieve the authenticated user
        $charity = Auth::guard('api-charities')->user();

        // Check if user is authenticated
        if (!$charity) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Validate that the token matches the latest token
        if ($charity->latest_token !== $hashedToken) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Invalid token',
            ], 401);
        }

        // Check if the token has the required capability
        if (!$charity->tokenCan('Charity Access Token')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Token does not have the required capability.',
            ], 403);
        }

        // Get the user ID
        $charityid = $charity->id;

        // Retrieve education details
        $education_charities = DB::table('beneficiary__education')
            ->where('beneficiaries_id', $beneficiaryId)
            ->first();

        // Retrieve health details
        $health_charities = DB::table('beneficiary__healths')
            ->where('beneficiaries_id', $beneficiaryId)
            ->first();

        // Retrieve relief details
        $relief_charities = DB::table('beneficiary__reliefs')
            ->where('beneficiaries_id', $beneficiaryId)
            ->first();

        // Retrieve lifehood details
        $lifehood_charities = DB::table('beneficiary__lifehoods')
            ->where('beneficiaries_id', $beneficiaryId)
            ->first();

        // Check status
        $status = null;
        if ($education_charities) {
            $status = $education_charities->status;
        } elseif ($health_charities) {
            $status = $health_charities->status;
        } elseif ($relief_charities) {
            $status = $relief_charities->status;
        } elseif ($lifehood_charities) {
            $status = $lifehood_charities->status;
        }

        if ($status == 'approved') {
            $assign = assign_orders_volunteer::create([
                'beneficiaries_id' => $beneficiaryId,
                'charities_id' => $charityid,
                'volunteers_id' => $volunteerId,
                'description' => $request->description,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'assign order successfully',
                'data' => $assign,
                'pagination' => [
                    'current_page' => 1,
                    'total_pages' => 1,
                    'total_items' => 1,
                    'items_per_page' => 1,
                ],
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Beneficiary not approved.',
            ], 400);
        }
    }

}
