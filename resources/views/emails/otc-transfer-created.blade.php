@component('emails.components.layout', ['title' => 'Your new OTC instruction is created', 'heading' => 'Your new OTC instruction is created', 'showFooter' => false])
    <p>Dear {{ $username }},</p>

    <div style="background-color: #f8f9fa; border-radius: 6px; padding: 20px; margin-bottom: 25px;">
        <h4 style="margin-top: 0; color: #1a2b47; border-bottom: 1px solid #dee2e6; padding-bottom: 10px; margin-bottom: 15px;">Transaction Details</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Reference Code:</td>
                <td style="padding: 8px 0;">{{ $reference_code }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Amount:</td>
                <td style="padding: 8px 0;">{{ $amount }} {{ $currency }}</td>
            </tr>
            @if($target_amount && $target_currency)
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Converting to:</td>
                <td style="padding: 8px 0;">{{ $target_amount }} {{ $target_currency }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; width: 40%; color: #6c757d; font-weight: bold;">Created At:</td>
                <td style="padding: 8px 0;">{{ \Carbon\Carbon::parse($created_at)->format('d-m-Y H:i') }} GMT+8</td>
            </tr>
        </table>
    </div>

    <p>Thank You,<br>
        FDT Support Team</p>

    <p>We are constantly working on improving the Client Portal and your comments and suggestions are most welcome.</p>
@endcomponent
