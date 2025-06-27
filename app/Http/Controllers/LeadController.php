<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel; // Add this line
use App\Imports\LeadsImport; // Add this line
use Illuminate\Support\Facades\Log; // Add this line for logging

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::where('user_id', Auth::id())->latest()->paginate(10);
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
            'designation' => 'nullable|string',
            'company' => 'required|string',
            'location' => 'nullable|string',
        ]);

        Lead::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'email1' => $request->input('email1'),
            'email2' => $request->input('email2'),
            'phone' => $request->input('phone'),
            'url' => $request->input('url'),
            'designation' => $request->input('designation'),
            'company' => $request->input('company'),
            'location' => $request->input('location'),
        ]);

        return redirect()->route('leads.index')->with('success', 'Lead added successfully.');
    }

    // This method will display the import form
    public function showImportForm()
    {
        return view('leads.import');
    }

    // This method will handle the file upload and import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv|max:10240', // Max 10MB file size
        ]);

        try {
            Excel::import(new LeadsImport, $request->file('file'));
            return redirect()->route('leads.index')->with('success', 'Leads imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . ' (Value: ' . implode(', ', $failure->values()) . ')';
            }
            return redirect()->back()->with('error', 'Import failed due to validation errors.')->withErrors($errors);
        } catch (\Exception $e) {
            Log::error('Lead import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during import: ' . $e->getMessage());
        }
    }


    public function exportJson()
    {
        $leads = Lead::where('user_id', Auth::id())->get();

        return response()->json($leads);
    }


// Search
    public function ajaxSearch(Request $request)
    {
        $search = $request->input('q');

        $query = Lead::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email1', 'like', "%$search%")
                    ->orWhere('email2', 'like', "%$search%")
                    ->orWhere('designation', 'like', "%$search%")
                    ->orWhere('company', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%")
                    ->orWhere('url', 'like', "%$search%");
            });
        }

        return response()->json($query->limit(50)->get());
    }
}