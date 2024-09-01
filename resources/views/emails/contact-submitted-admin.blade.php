<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
</head>
<body>
<h1>New form submission from , {{ $contact->first_name }}!</h1>
<p>You have received new submission with the following details:</p>

<ul>
    <li><strong>First Name:</strong> {{ $contact->first_name }}</li>
    <li><strong>Last Name:</strong> {{ $contact->last_name }}</li>
    <li><strong>Business Name:</strong> {{ $contact->business_name }}</li>
    <li><strong>Job Title:</strong> {{ $contact->job_title }}</li>
    <li><strong>Business Email:</strong> {{ $contact->business_email }}</li>
    <li><strong>Phone:</strong> {{ $contact->phone }}</li>
    <li><strong>FX Services:</strong> {{ $contact->fx_services ? 'Yes' : 'No' }}</li>
    <li><strong>Asset Servicing:</strong> {{ $contact->asset_servicing ? 'Yes' : 'No' }}</li>
    <li><strong>Settlement & Clearing:</strong> {{ $contact->settlement_clearing ? 'Yes' : 'No' }}</li>
    <li><strong>Treasury Services:</strong> {{ $contact->treasury_services ? 'Yes' : 'No' }}</li>
    <li><strong>Other:</strong> {{ $contact->other ? 'Yes' : 'No' }}</li>
    <li><strong>Project Description:</strong> {{ $contact->project_description }}</li>
    <li><strong>How did you hear about us:</strong> {{ $contact->how_did_you_hear }}</li>
</ul>

<p>We will get back to you as soon as possible.</p>
</body>
</html>
