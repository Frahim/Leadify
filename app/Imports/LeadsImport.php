<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeadsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Ensure that the 'url' is unique if it exists
        $url = isset($row['url']) ? trim($row['url']) : null;
        if ($url) {
            $existingLead = Lead::where('url', $url)->first();
            if ($existingLead) {
                // You can choose to skip, update, or log this.
                // For now, we'll skip creating a new lead if the URL already exists.
                return null;
            }
        }

        return new Lead([
            'user_id' => Auth::id(), // Assign the authenticated user's ID
            'name' => $row['name'] ?? null,
            'email1' => $row['email1'] ?? null,
            'email2' => $row['email2'] ?? null,
            'phone' => $row['phone'] ?? null,
            'url' => $url,
            'designation' => $row['designation'] ?? null,
            'company' => $row['company'] ?? null,
            'location' => $row['location'] ?? null,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email1' => 'nullable|email:rfc,dns|max:255', // Improved email validation
            'email2' => 'nullable|email:rfc,dns|max:255', // Improved email validation
            'phone' => 'nullable|string|max:20',
            // URL uniqueness will be handled in the model method, but you can add a basic validation here too.
            'url' => 'nullable|string',
            'designation' => 'nullable|string',
            'company' => 'nullable|string',
            'location' => 'nullable|string',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'email1.email' => 'The Email 1 must be a valid email address.',
            'email2.email' => 'The Email 2 must be a valid email address.',
            // Add more custom messages as needed
        ];
    }
}