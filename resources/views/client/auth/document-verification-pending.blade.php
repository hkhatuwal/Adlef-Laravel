@extends('client._layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-file-alt fa-4x text-warning"></i>
                    </div>
                    <h2 class="mb-4">Document Verification Pending</h2>
                    <p class="lead">
                        Your documents are currently under review by our team. This process typically takes 1-2 business days.
                    </p>
                    <p>
                        You will receive an email notification once your documents have been verified. Thank you for your patience.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('client.client-logout') }}" class="btn btn-outline-secondary">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 