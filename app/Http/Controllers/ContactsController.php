<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function index()
    {
        return view('list');
    }

    public function getList()
    {
        try {
            $contacts = Contact::all();

            return response()->json([
                'success' => true,
                'message' => 'Contacts fetched successfully.',
                'contacts' => $contacts,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the contacts.',
            ], 500);
        }
    }

    public function getContact($id)
    {
        try {
            $contact = Contact::with('customFields')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Contacts fetched successfully.',
                'contact' => $contact,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the contact.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'gender' => 'required|string|in:Male,Female,Other',
                'profile_image' => 'nullable|image|max:2048',
                'additional_file' => 'nullable|file|max:5120', // 5MB max
            ]);

            // Handle file uploads
            $profileImagePath = null;
            $additionalFilePath = null;

            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            }

            if ($request->hasFile('additional_file')) {
                $additionalFilePath = $request->file('additional_file')->store('additional_files', 'public');
            }

            // Create the contact first
            $contact = Contact::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'gender' => $validatedData['gender'],
                'profile_image' => $profileImagePath,
                'additional_file' => $additionalFilePath,
                'is_merged' => false,
                'merged_into' => null,
            ]);

            // Save custom fields if any
            if ($request->has('custom_fields')) {
                foreach ($request->input('custom_fields') as $field) {
                    if (! empty($field['name']) && ! is_null($field['value'])) {
                        $contact->customFields()->create([
                            'field_name' => $field['name'],
                            'field_value' => $field['value'],
                        ]);
                    }
                }
            }

            // For this example, we'll just redirect back with a success message
            return response()->json([
                'success' => true,
                'message' => 'Contact saved successfully!',
                'contact' => $contact,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the contact.',
            ], 500);

        }
    }

    public function updateContact(Request $request, $id)
    {
        try {
            // dd($id);
            $contact = Contact::with('customFields')->where('id', $id)->first();
            
             $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'gender' => 'required|string|in:Male,Female,Other',
                'profile_image' => 'nullable|image|max:2048',
                'additional_file' => 'nullable|file|max:5120', // 5MB max
            ]);

            // Handle file uploads
            $profileImagePath = null;
            $additionalFilePath = null;

            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            }

            if ($request->hasFile('additional_file')) {
                $additionalFilePath = $request->file('additional_file')->store('additional_files', 'public');
            }

            $contact->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'gender' => $validatedData['gender'],
                'profile_image' => $profileImagePath,
                'additional_file' => $additionalFilePath,
            ]);

            // Delete all existing custom fields
            $contact->customFields()->delete();

            // Create new records for submitted fields
            if ($request->has('custom_fields')) {
                $contact->customFields()->createMany(
                    collect($request->input('custom_fields'))
                        ->filter(fn($field) => !empty($field['name']))
                        ->map(fn($field) => [
                            'field_name' => $field['name'],
                            'field_value' => $field['value'] ?? ''
                        ])
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Contact Updated successfully!',
                'contact' => $contact,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the contact.',
            ], 500);
        }
    }

    public function deleteContact($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contact deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the contact.',
            ], 500);
        }
    }
}
