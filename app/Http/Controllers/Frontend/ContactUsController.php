<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormSubmitted;
use App\Mail\ContactFormSubmittedForAdmin;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function businessEnquiry()
    {

        return view('frontend.contact.business-enquiry');
    }

    public function contactUs()
    {
        return view('frontend.contact.contact-us');

    }

    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'business_email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'fx_services' => 'nullable',
            'asset_servicing' => 'nullable',
            'settlement_clearing' => 'nullable',
            'treasury_services' => 'nullable',
            'other' => 'nullable',
            'project_description' => 'nullable|string',
            'how_did_you_hear' => 'nullable|string|max:255',
        ]);


        $data = $request->all();
        $data['fx_services'] = $request->get('fx_services') == "on";
        $data['asset_servicing'] = $request->get('asset_servicing') == "on";
        $data['settlement_clearing'] = $request->get('settlement_clearing') == "on";
        $data['treasury_services'] = $request->get('treasury_services') == "on";
        $data['other'] = $request->get('other') == "on";
        $contact = Contact::create($data);
        Mail::to($request->business_email)->send(new ContactFormSubmitted($contact));
        Mail::to("gsharma170@gmail.com")->send(new ContactFormSubmittedForAdmin($contact));

        return redirect()->back()->with('success', 'Contact form submitted successfully!');
    }
}
