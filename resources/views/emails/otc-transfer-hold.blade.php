@component('emails.components.layout', ['title' => $heading2, 'heading' => $heading2, 'showFooter' => false])

    <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $username }},</p>

    <div style="background-color: #f8f9fa; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
        <h3 style="color: #ffc107; margin-top: 0; margin-bottom: 10px;">{{ $heading2 }}</h3>
        <p style="margin: 0;">{{ $message2 }}</p>
    </div>

    <div style="background-color: #f8f9fa; border-radius: 6px; padding: 20px; margin-bottom: 25px;">
        <h4 style="margin-top: 0; color: #1a2b47; border-bottom: 1px solid #dee2e6; padding-bottom: 10px; margin-bottom: 15px;">Transaction Details</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Reference Code:</td>
                <td style="padding: 8px 0;">{{ $reference_code }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Amount:</td>
                <td style="padding: 8px 0;">{{ number_format($amount, 3) }} {{ $currency }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Created At:</td>
                <td style="padding: 8px 0;">{{ \Carbon\Carbon::parse($created_at)->format('d-m-Y H:i') }} GMT+8</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Hold Reason:</td>
                <td style="padding: 8px 0;">{{ $reason }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Held By:</td>
                <td style="padding: 8px 0;">{{ $held_by }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Held At:</td>
                <td style="padding: 8px 0;">{{ \Carbon\Carbon::parse($held_at)->format('d-m-Y H:i') }} GMT+8</td>
            </tr>
        </table>
    </div>

    <div style="background-color: #fff3cd; border-radius: 6px; padding: 15px; margin-bottom: 25px;">
        <p style="margin: 0; font-size: 15px;">
            Your transaction is currently on hold. Our team will review it and take appropriate action.
            If you have any questions, please contact our customer support.
        </p>
    </div>

    @include('emails.components.signature')
@endcomponent 