<?php

namespace App\Exports;

use App\Models\UserActivity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class ActivitiesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = UserActivity::query()
            ->where('user_id', auth()->id())
            ->latest();

        // Apply filters
        if ($this->request->filled('type')) {
            $query->where('activity_type', $this->request->type);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $this->request->start_date);
        }

        if ($this->request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $this->request->end_date);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Date & Time',
            'Type',
            'Reference',
            'Amount',
            'Currency',
            'Status',
            'Fee',
            'Network Fee',
            'Exchange Rate',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->created_at->format('Y-m-d H:i:s'),
            ucfirst(str_replace('_', ' ', $activity->activity_type)),
            $activity->reference_number,
            $activity->amount ? number_format($activity->amount, 8) : '-',
            $activity->currency_symbol ?? '-',
            ucfirst($activity->status),
            isset($activity->metadata['fee']) ? number_format($activity->metadata['fee'], 8) : '-',
            isset($activity->metadata['network_fee']) ? number_format($activity->metadata['network_fee'], 8) : '-',
            isset($activity->metadata['exchange_rate']) ? number_format($activity->metadata['exchange_rate'], 8) : '-',
        ];
    }
} 