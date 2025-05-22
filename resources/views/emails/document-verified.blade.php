@component('emails.components.layout', ['title' => 'Identity Document Verification Successful', 'heading' => 'Identity Document Verification Successful', 'showFooter' => false])

    <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $username }},</p>

    <div style="background-color: #f8f9fa; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px;">
        <h3 style="color: #28a745; margin-top: 0; margin-bottom: 10px;">{{ $heading2 }}</h3>
        <p style="margin: 0;">{{ $message2 }}</p>
    </div>

    <div style="background-color: #f8f9fa; border-radius: 6px; padding: 20px; margin-bottom: 25px;">
        <h4 style="margin-top: 0; color: #1a2b47; border-bottom: 1px solid #dee2e6; padding-bottom: 10px; margin-bottom: 15px;">Verification Details</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Document Type:</td>
                <td style="padding: 8px 0;">{{ $document_type }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Verified At:</td>
                <td style="padding: 8px 0;">{{ $verified_at }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Verified By:</td>
                <td style="padding: 8px 0;">{{ $verified_by }}</td>
            </tr>
        </table>
    </div>

    <div style="background-color: #e8f4fd; border-radius: 6px; padding: 15px; margin-bottom: 25px;">
        <p style="margin: 0; font-size: 15px;">
            <span style="display: block; margin-bottom: 10px;">Your account has now been fully verified. You can access all platform features.</span>
            <span style="display: block; font-weight: bold;">Thank you for your patience during the verification process.</span>
        </p>
    </div>

    @include('emails.components.signature')
@endcomponent 