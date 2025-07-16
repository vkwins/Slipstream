<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function index(): JsonResponse
    {
        $contacts = Contact::with('customer')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'contacts' => $contacts
        ]);
    }

    public function show(Contact $contact): JsonResponse
    {
        return response()->json([
            'contact' => $contact->load('customer')
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255'
        ]);

        $contact = Contact::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'contact' => $contact
        ]);
    }

    public function update(Request $request, Contact $contact): JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255'
        ]);

        $contact->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully',
            'contact' => $contact
        ]);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully'
        ]);
    }
}
