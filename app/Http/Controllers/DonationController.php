<?php
namespace App\Http\Controllers;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Models\Charity;
class DonationController extends Controller
{
public function index(){
$donations=Donation::all();
      return response()->json([
          'status' => true,
          'message' => 'all donation',
          'data' => $donations,
          'pagination' => [
              'current_page' => 1,
              'total_pages' => 1,
              'total_items' => 1,
              'items_per_page' => 1,
              'first_item' => 1,
              'last_item' => 1,
              'has_more_pages' => false,
              'next_page_url' => null,
              'previous_page_url' => null,
          ],
      ], 200);

}
  public function create(Request $request, $charityId)
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
    $user = Auth::guard('api')->user();

    // Check if user is authenticated
    if (!$user) {
        return response()->json(['status' => false,'message' => 'Unauthorized'], 401);
    }

    // Validate that the token matches the latest token
    if ($user->latest_token !== $hashedToken) {
        return response()->json(['status' => false,'message' => 'Unauthorized: Invalid token'], 401);
    }

    // Check if the token has the required capability
    if (!$user->tokenCan('API TOKEN')) {
        return response()->json(['status' => false,'message' => 'Unauthorized: Token does not have the required capability.'], 403);
    }


    $userid = $user->id;
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'phone' => 'required|string|max:15',
      'image' => 'required|image',
      'amount'=> 'required|integer',
      'number_bill'=> 'required|integer',
  ]);

  // Check if validation fails
  if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
  }

    if ($request->hasFile('image')) {
        // Store the image in the 'public/donations_images' directory
        $imagePath = $request->file('image')->store('public/donations_images');
        // Get the URL to the saved image
        $imagePath = Storage::url($imagePath);}
      // Create a new donation entry with the provided data
      $donations = Donation::create([
          'charities_id' => $charityId,
          'name' => $request->name,
          'phone' => $request->phone,
          'image'=>$imagePath,
          'amount'=> $request->amount,
          'number_bill'=>$request->number_bill,
      ]);



      // Prepare the response data
      return response()->json([
          'status' => true,
          'message' => 'You insert donation  successfully',
          'data' => $donations,
          'pagination' => [
              'current_page' => 1,
              'total_pages' => 1,
              'total_items' => 1,
              'items_per_page' => 1,
              'first_item' => 1,
              'last_item' => 1,
              'has_more_pages' => false,
              'next_page_url' => null,
              'previous_page_url' => null,
          ],
      ], 200);
  }

  public function getcharityname(Request $request)
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
    $user = Auth::guard('api')->user();

    // Check if user is authenticated
    if (!$user) {
        return response()->json(['status' => false,'message' => 'Unauthorized'], 401);
    }

    // Validate that the token matches the latest token
    if ($user->latest_token !== $hashedToken) {
        return response()->json(['status' => false,'message' => 'Unauthorized: Invalid token'], 401);
    }

    // Check if the token has the required capability
    if (!$user->tokenCan('API TOKEN')) {
        return response()->json(['status' => false,'message' => 'Unauthorized: Token does not have the required capability.'], 403);
    }

    // Get the user ID
    $userid = $user->id;

$charities=Charity::all();

return response()->json([
    'status' => true,
    'message' => 'name of charity to donate',
    'data' => $charities,
    'pagination' => [
        'current_page' => 1,
        'total_pages' => 1,
        'total_items' => 1,
        'items_per_page' => 1,
        'first_item' => 1,
        'last_item' => 1,
        'has_more_pages' => false,
        'next_page_url' => null,
        'previous_page_url' => null,
    ],
], 200);
  }

public function show($donationId){
$donations=Donation::where('id',$donationId)->get();
return response()->json([
    'status' => true,
    'message' => 'dtailes of  donation',
    'data' => $donations,
    'pagination' => [
        'current_page' => 1,
        'total_pages' => 1,
        'total_items' => 1,
        'items_per_page' => 1,
        'first_item' => 1,
        'last_item' => 1,
        'has_more_pages' => false,
        'next_page_url' => null,
        'previous_page_url' => null,
    ],
], 200);
}

  }
