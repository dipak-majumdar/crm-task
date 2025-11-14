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
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function contactToMerge($id)
    {
        try {
            
            $contacts = Contact::where('is_merged', 0)
            ->whereNull('merged_into')
            ->where('id', '!=', $id)
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Contacts fetched successfully.',
                'contacts' => $contacts,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTrashedList()
    {
        try {
            $contacts = Contact::onlyTrashed()->get();

            // dd($contacts);
            return view('trashed-list', compact('contacts'));

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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
                'message' => $e->getMessage(),
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

            // unlink(public_path($contact->profile_image));
            // unlink(public_path($contact->additional_file));

            $oldImg = $contact->profile_image;
            $oldFile = $contact->additional_file;

            // Handle file uploads
            $profileImagePath = null;
            $additionalFilePath = null;

            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            }else{
                $profileImagePath = $contact->profile_image;
            }

            if ($request->hasFile('additional_file')) {
                $additionalFilePath = $request->file('additional_file')->store('additional_files', 'public');
            }else{
                $additionalFilePath = $contact->additional_file;
            }

            $contact->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'gender' => $validatedData['gender'],
                'profile_image' => $profileImagePath,
                'additional_file' => $additionalFilePath,
            ]);

            if ($request->hasFile('profile_image') && $oldImg) {
                $oldPath = str_replace('storage/', '', $oldImg); // Remove 'storage/' prefix if it exists
                if (file_exists(storage_path('app/public/' . $oldPath))) {
                    unlink(storage_path('app/public/' . $oldPath));
                }
            }

            if ($request->hasFile('additional_file') && $oldFile) {
                $oldPath = str_replace('storage/', '', $oldFile); // Remove 'storage/' prefix if it exists
                if (file_exists(storage_path('app/public/' . $oldPath))) {
                    unlink(storage_path('app/public/' . $oldPath));
                }
            }

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
                'status' => true,
                'message' => 'Contact Updated successfully!',
                'contact' => $contact,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function mergeContacts(Request $request)
    {
        try {
            $request->validate([
                'master_contact_id' => 'numeric|required',
                'merge-contact' => 'numeric|required',
                'keep' => 'string|required|in:both,master',
            ]);
            
            $masterContact = Contact::find($request->input('master_contact_id'));
            $mergeContact = Contact::find($request->input('merge-contact'));

            if ($masterContact->id == $mergeContact->id) {
                throw new \Exception('You cannot merge a contact with itself.');
            }

            if ($request->input('keep') == 'master') {
                $masterContact->is_merged = true;
                $mergeContact->merged_into = $masterContact->id;
            } else if ($request->input('keep') == 'both') {
                $masterContact->is_merged = true;
                $mergeContact->merged_into = $masterContact->id;

                // Update phone if different
                if ($masterContact->phone != $mergeContact->phone) {
                    $masterContact->phone = $mergeContact->phone 
                    ? ($masterContact->phone 
                        ? $masterContact->phone . ', ' . $mergeContact->phone
                        : $mergeContact->phone)
                    : $masterContact->phone;
                }

                // Update email if different
                if ($masterContact->email != $mergeContact->email) {
                    $masterContact->email = $mergeContact->email 
                    ? ($masterContact->email 
                        ? $masterContact->email . ', ' . $mergeContact->email
                        : $mergeContact->email)
                    : $masterContact->email;
                }

                // Update gender if different
                if ($masterContact->gender != $mergeContact->gender) {
                    $masterContact->gender = $mergeContact->gender 
                    ? ($masterContact->gender 
                        ? $masterContact->gender . ', ' . $mergeContact->gender
                        : $mergeContact->gender)
                    : $masterContact->gender;
                }

                // Update profile_image if different
                if ($masterContact->profile_image != $mergeContact->profile_image) {
                    $masterContact->profile_image = $mergeContact->profile_image 
                    ? ($masterContact->profile_image 
                        ? $masterContact->profile_image . ', ' . $mergeContact->profile_image 
                        : $mergeContact->profile_image)
                    : $masterContact->profile_image;
                }

                // Update profile_image if different
                if ($masterContact->profile_image != $mergeContact->profile_image) {
                    $masterContact->profile_image = $masterContact->profile_image ? $masterContact->profile_image : $mergeContact->profile_image;
                }

                // Update additional_file if different
                if ($masterContact->additional_file != $mergeContact->additional_file) {
                    $masterContact->additional_file = $mergeContact->additional_file
                    ? ($masterContact->additional_file 
                        ? $masterContact->additional_file . ', ' . $mergeContact->additional_file
                        : $mergeContact->additional_file)
                    : $masterContact->additional_file;
                }

                // Update custom fields
                // Get all custom fields for both contacts
                $masterCustomFields = $masterContact->customFields()->get()->keyBy('field_name');
                $mergeCustomFields = $mergeContact->customFields()->get();

                foreach ($mergeCustomFields as $mergeField) {
                    $fieldName = $mergeField->field_name;
                    
                    // If master already has this field
                    if ($masterCustomFields->has($fieldName)) {
                        $masterField = $masterCustomFields[$fieldName];
                        
                        // If values are different, append the merge value
                        if ($masterField->field_value != $mergeField->field_value) {
                            $masterField->update([
                                'field_value' => $masterField->field_value ? $masterField->field_value . ', ' . $mergeField->field_value : $mergeField->field_value
                            ]);
                        }
                        // If values are the same, no update needed
                    } 
                    // If master doesn't have this field, create it
                    else {
                        $masterContact->customFields()->create([
                            'field_name' => $fieldName,
                            'field_value' => $mergeField->field_value
                        ]);
                    }
                }
                
                $masterContact->save();
                $mergeContact->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Contacts merged successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
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
                'message' =>  $e->getMessage(),
            ], 500);
        }
    }
}
