@component('emails.components.layout', ['title' => $heading2, 'heading' => $heading2, 'showFooter' => true])

    <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $username }},</p>

    <div style="background-color: #f8f9fa; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px;">
        <h3 style="color: #28a745; margin-top: 0; margin-bottom: 10px;">{{ $heading2 }}</h3>
        <p style="margin: 0;">{{ $message2 }}</p>
    </div>

    <div style="background-color: #f8f9fa; border-radius: 6px; padding: 20px; margin-bottom: 25px;">
        <h4 style="margin-top: 0; color: #1a2b47; border-bottom: 1px solid #dee2e6; padding-bottom: 10px; margin-bottom: 15px;">Transaction Details</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Reference Number:</td>
                <td style="padding: 8px 0;">{{ $reference_number }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Amount:</td>
                <td style="padding: 8px 0;">{{ $amount }} {{ $currency }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Verified At:</td>
                <td style="padding: 8px 0;">{{ \Carbon\Carbon::parse($verified_at)->format('d-m-Y H:i') }} GMT+8</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Verified By:</td>
                <td style="padding: 8px 0;">{{ $verified_by }}</td>
            </tr>
        </table>
    </div>

    <div style="background-color: #e8f4fd; border-radius: 6px; padding: 15px; margin-bottom: 25px;">
        <p style="margin: 0; font-size: 15px;">
            <span style="display: block; color: #dc3545; font-weight: bold;">If you did not initiate this transaction, please contact our customer service team immediately.</span>
        </p>
    </div>

    @include('emails.components.signature')
@endcomponent
