<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:png,jpg,jpeg,pdf|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('document');
            $filename = 'custom_name_' . time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('user-documents',$filename, 'public');


            return response()->json([
                'status' => 'success',
                'message' => 'Document uploaded successfully',
                'path' => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload document'.$e->getMessage(),
            ], 500);
        }
    }

}
