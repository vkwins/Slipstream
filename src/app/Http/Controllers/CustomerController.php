<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['category', 'contacts']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = CustomerCategory::all();

        return response()->json([
            'customers' => $customers,
            'categories' => $categories
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:customers',
            'customer_category_id' => 'required|exists:customer_categories,id',
            'start_date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $customer = Customer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'customer' => $customer->load(['category', 'contacts'])
        ]);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json([
            'customer' => $customer->load(['category', 'contacts'])
        ]);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:customers,reference,' . $customer->id,
            'customer_category_id' => 'required|exists:customer_categories,id',
            'start_date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $customer->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'customer' => $customer->load(['category', 'contacts'])
        ]);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ]);
    }
}
