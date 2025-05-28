<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HelpCenterCategory;
use App\Models\HelpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HelpCenterController extends Controller
{
    /**
     * Display the help center form.
     */
    public function index()
    {
        $categories = HelpCenterCategory::active()->ordered()->get();

        return view('frontend.help-center.index', compact('categories'));
    }

    /**
     * Store a new help request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'help_center_category_id' => 'required|exists:help_center_categories,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif'
        ]);

        $attachments = [];

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('help-center-attachments', 'public');
                $attachments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ];
            }
        }

        $helpRequest = HelpRequest::create([
            'user_id' => Auth::id(),
            'help_center_category_id' => $request->help_center_category_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'attachments' => $attachments
        ]);

        return redirect()->route('frontend.help-center.show', $helpRequest)
            ->with('success', 'Your help request has been submitted successfully. We will get back to you soon.');
    }

    /**
     * Display a specific help request.
     */
    public function show(HelpRequest $helpRequest)
    {
        // Ensure user can only view their own requests
        if ($helpRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this help request.');
        }

        return view('frontend.help-center.show', compact('helpRequest'));
    }

    /**
     * Display user's help requests.
     */
    public function myRequests()
    {
        $helpRequests = HelpRequest::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.help-center.my-requests', compact('helpRequests'));
    }
}
