<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::where('user_id', Auth::id())->get();
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email1' => 'nullable|email|max:255',
            'email2' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'url' => 'nullable|url|unique:leads,url',
            'cjobtitle' => 'nullable|string',
            'ccompany' => 'required|string',
            'location' => 'nullable|string',
           
        ]);

        Lead::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'email1' => $request->input('email1'),
            'email2' => $request->input('email2'),
            'phone' => $request->input('phone'),
            'url' => $request->input('url'),
            'cjobtitle' => $request->input('cjobtitle'),           
            'ccompany' => $request->input('ccompany'),
            'location' => $request->input('location'),
            
        ]);

        return redirect()->route('leads.index')->with('success', 'Lead added successfully.');
    }

    public function import(Request $request)
{
    $request->validate([
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

    foreach ($request->input('leads') as $leadData) {
        $leadDataWithUserId = array_merge($leadData, ['user_id' => Auth::id()]);
        Log::info('Importing lead:', $leadDataWithUserId);
        Lead::create($leadDataWithUserId);
    }

    return response()->json(['message' => 'Leads imported successfully.'], 200);
}


public function exportJson()  
{  
    $leads = Lead::where('user_id', Auth::id())->get();  

    return response()->json($leads);  
}  

}
