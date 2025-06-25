<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//use App\Models\PersonalAccessToken; 
use Laravel\Sanctum\PersonalAccessToken;

class LeadController extends Controller
{
    // Get all leads for the authenticated user
    public function index()
    {
        $user = Auth::user();
        $leads = $user->leads; // Get leads for the authenticated user

        return response()->json($leads);
    }

    public function import(Request $request)
{
    // Validate the incoming JSON data
    $validatedData = $request->validate([
        'leads' => 'required|array',
        'leads.*.name' => 'nullable|string|max:255',
        'leads.*.email1' => 'nullable|email1|max:255',
        'leads.*.email2' => 'nullable|email2|max:255',        
        'leads.*.phone' => 'nullable|string|max:20',
        'leads.*.url' => 'nullable|string',
        'leads.*.cjobtitle' => 'nullable|string',
        'leads.*.ccompany' => 'nullable|string',
        'leads.*.location' => 'nullable|string',
        
    ]);

    // Retrieve the authenticated user based on the token
    try {
        $token = request()->bearerToken(); // Assuming token is sent in Authorization header
        $tokenModel = PersonalAccessToken::findToken($token); // Find the token using Sanctum

        if (!$tokenModel) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $user = $tokenModel->tokenable; // This will return the related user model
    } catch (\Exception $e) {
        return response()->json(['error' => 'An internal server error occurred'], 500);
    }

    // Loop through the leads array and create leads for the user
    foreach ($validatedData['leads'] as $leadData) {
        $user->leads()->create($leadData);
    }

    return response()->json(['message' => 'Leads imported successfully'], 201);
}

public function export()  
    {  
        $user = Auth::user();  
        $leads = $user->leads()->get()->toArray(); // Convert to array for JSON encoding

        // Create a filename for the JSON file  
        $filename = 'leads_' . date('Ymd_His') . '.json';  

        // Set headers for download  
        $headers = [  
            'Content-Type' => 'application/json',  
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',  
        ];  

        return response()->stream(  
            function() use ($leads) {  
                echo json_encode($leads);  
            },  
            200,  
            $headers  
        );  
    }

} 