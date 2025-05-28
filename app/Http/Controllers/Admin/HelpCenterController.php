<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpCenterCategory;
use App\Models\HelpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpCenterController extends Controller
{
    /**
     * Display help requests dashboard.
     */
    public function index()
    {
        $helpRequests = HelpRequest::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => HelpRequest::count(),
            'open' => HelpRequest::where('status', 'open')->count(),
            'in_progress' => HelpRequest::where('status', 'in_progress')->count(),
            'resolved' => HelpRequest::where('status', 'resolved')->count(),
        ];

        return view('admin.help-center.index', compact('helpRequests', 'stats'));
    }

    /**
     * Display a specific help request.
     */
    public function show(HelpRequest $helpRequest)
    {
        $helpRequest->load(['user', 'category', 'respondedBy']);
        
        return view('admin.help-center.show', compact('helpRequest'));
    }

    /**
     * Update help request status and response.
     */
    public function update(Request $request, HelpRequest $helpRequest)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_response' => 'nullable|string'
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->filled('admin_response')) {
            $updateData['admin_response'] = $request->admin_response;
            $updateData['responded_at'] = now();
            $updateData['responded_by'] = Auth::id();
        }

        $helpRequest->update($updateData);

        return redirect()->route('admin.help-center.show', $helpRequest)
            ->with('success', 'Help request updated successfully.');
    }

    /**
     * Display categories management.
     */
    public function categories()
    {
        $categories = HelpCenterCategory::orderBy('sort_order')->get();
        
        return view('admin.help-center.categories', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        HelpCenterCategory::create($request->all());

        return redirect()->route('admin.help-center.categories')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Update a category.
     */
    public function updateCategory(Request $request, HelpCenterCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $category->update($request->all());

        return redirect()->route('admin.help-center.categories')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete a category.
     */
    public function destroyCategory(HelpCenterCategory $category)
    {
        if ($category->helpRequests()->count() > 0) {
            return redirect()->route('admin.help-center.categories')
                ->with('error', 'Cannot delete category with existing help requests.');
        }

        $category->delete();

        return redirect()->route('admin.help-center.categories')
            ->with('success', 'Category deleted successfully.');
    }
} 