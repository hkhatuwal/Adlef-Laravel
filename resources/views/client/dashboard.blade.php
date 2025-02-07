@extends('client.layouts.app')

@section('page-title', 'Asset Holdings')

@section('content')
<div class="space-y-6">
    <!-- Portfolio Value Card -->
    <div class="bg-white rounded-lg shadow p-6 mt-4">
        <div class="space-y-4">
            <h2 class="text-sm text-gray-600 uppercase">Total Portfolio Value</h2>
            <div class="text-3xl font-bold">${{ number_format($totalValue, 2) }} USD</div>
            <div class="text-sm text-gray-600">{{ $assetClassesCount }} ASSET CLASSES</div>
        </div>
    </div>


</div>
@endsection


