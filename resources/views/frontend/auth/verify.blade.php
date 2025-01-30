@extends('_partials.app')
@section('content')

    <section class="container mx-auto px-4 md:p-10 mt-10 relative">
        <h3 class="text-6xl relative z-10">Individual <span class="font-bold">account <br> application</span></h3>
        <img src="{{asset('/assets/images/texcture1.avif')}}"
             class="absolute inset-0 w-full h-full object-cover z-0 opacity-20">
    </section>
    <div class="progress-bar bg-gray-200 h-5 w-full">
        <div class="progress bg-lime-300 w-1/4 h-full"></div>
    </div>
    <section class="container mx-auto px-4 md:p-10 mt-10">
        <div class="flex flex-row justify-start items-start gap-8">
            <div class="flex flex-col gap-2 sticky left-0 top-14 max-w-[18rem]">
                <div class="py-8 px-6 bg-black text-white ">
                    <ul class="flex flex-col gap-6">
                        <li class="flex gap-2 items-center  "><i class="fa-regular fa-address-card"></i>Application
                            Details
                        </li>
                        <li class="flex gap-2 items-center text-primary"><i class="fa-solid fa-fingerprint text-white"></i>Verify
                            Information
                        </li>
                        <li class="flex gap-2 items-center "><i class="fa-regular fa-circle-check"></i>Confirmation</li>
                    </ul>
                </div>
                <div class="py-8 px-6 flex flex-col gap-4 items-center justify-center border ">
                    <div class="circle h-16 w-16 bg-primary  flex items-center justify-center rounded-full">
                        <i class="fal fa-shield-check text-2xl"></i>
                    </div>
                    <h2 class="font-bold text-xl">Secure Application</h2>
                    <p class="text-center">We use SSL encryption to ensure your personal information sent over is secure
                        and can't be intercepted by an attacker.</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border min-w-[25rem]">
                <div class="space-y-6">
                    <!-- Email Verification Section -->
                    <div class="border-b pb-4">
                        <h3 class="text-lg font-semibold mb-4">Email Verification</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" disabled value="{{ auth()->user()->contactDetails->email ?? '' }}" class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none">
                            </div>
                            <div class="flex gap-3">
                                <input type="text" name="email_otp" placeholder="Enter Email OTP" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                <button type="button" id="send-otp-email" onclick="sendEmailOtp(this)" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">Send OTP</button>
                            </div>
                            <button type="button" onclick="verifyEmailOtp()" class="hidden w-full mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700" id="verify-email-btn">Verify Email OTP</button>
                        </div>
                    </div>

                    <!-- Phone Verification Section -->
                    <div class="border-b pb-4">
                        <h3 class="text-lg font-semibold mb-4">Phone Verification</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" disabled value="{{ auth()->user()->contactDetails->phone ?? '' }}" class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none">
                            </div>
                            <div class="flex gap-3">
                                <input type="text" name="phone_otp" placeholder="Enter Phone OTP" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                <button type="button" id="send-otp-phone" onclick="sendPhoneOtp(this)" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">Send OTP</button>
                            </div>
                            <button type="button" onclick="verifyPhoneOtp()" class="hidden w-full mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700" id="verify-phone-btn">Verify Phone OTP</button>
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Identity Verification</h3>
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-700">Upload Identity Document</label>

                            @include('_components.file_picker',['id'=>'identity_document'])
                        </div>
                        <button type="button" id="verify-document" onclick="verifyDocument(this)" class="mt-2 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">Verify Document</button>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('post-script')
<script>
    let emailVerified = {{ auth()->user()->contactDetails->is_email_verified ? 'true' : 'false' }};
    let phoneVerified = {{ auth()->user()->contactDetails->is_phone_verified ? 'true' : 'false' }};
    let documentUploaded = {{ auth()->user()->profile->document_path ? 'true' : 'false' }};



    // Check initial verification status
    function initializeVerificationStatus() {
        if (emailVerified) {
            $('input[name="email_otp"]').prop('disabled', true).addClass('hidden');
            $('#send-otp-email').addClass('hidden');
            $('#verify-email-btn').removeClass('hidden')
                .prop('disabled', true)
                .removeClass('bg-green-600 hover:bg-green-700')
                .addClass('bg-gray-400')
                .text('Email Verified');
        }

        if (phoneVerified) {
            $('input[name="phone_otp"]').prop('disabled', true).addClass('hidden');
            $('#send-otp-phone').addClass('hidden');
            $('#verify-phone-btn').removeClass('hidden')
                .prop('disabled', true)
                .removeClass('bg-green-600 hover:bg-green-700')
                .addClass('bg-gray-400')
                .text('Phone Verified');
        }

        if (documentUploaded) {
            $('#file-upload').addClass('opacity-50 pointer-events-none');
            $('.selected-file').text('Document already uploaded').removeClass('hidden');
        }

        checkAllVerified();
    }

    // Call initialization on page load
    $(document).ready(initializeVerificationStatus);
    function sendEmailOtp(button) {
        if (emailVerified) return;

        const $button = $(button);
        $button.prop('disabled', true);
        const originalText = $button.text();
        $button.text('Sending...');

        $.ajax({
            url: '/verification/send-email-otp',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            success: function(data) {
                if (data.status === 'success') {
                    toastr.success('OTP has been sent to your email', 'Sent successfully');
                    $('#verify-email-btn').removeClass('hidden');
                    startCountdown($button);
                } else {
                    toastr.error(data.message || 'Failed to send OTP');
                    $button.prop('disabled', false).text(originalText);
                }
            },
            error: function(error) {
                console.error('Error:', error);
                toastr.error('Failed to send OTP');
                $button.prop('disabled', false).text(originalText);
            }
        });
    }

    function sendPhoneOtp(button) {
        if (phoneVerified) return;

        const $button = $(button);
        $button.prop('disabled', true);
        const originalText = $button.text();
        $button.text('Sending...');

        $.ajax({
            url: '/verification/send-phone-otp',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            success: function(data) {
                if (data.status === 'success') {
                    toastr.success('OTP has been sent to your phone', 'Sent successfully');
                    $('#verify-phone-btn').removeClass('hidden');
                    startCountdown($button);
                } else {
                    toastr.error(data.message || 'Failed to send OTP');
                    $button.prop('disabled', false).text(originalText);
                }
            },
            error: function(error) {
                console.error('Error:', error);
                toastr.error('Failed to send OTP');
                $button.prop('disabled', false).text(originalText);
            }
        });
    }

    function startCountdown($button) {
        let timeLeft = 60;
        const timer = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timer);
                $button.prop('disabled', false).text('Send OTP');
            } else {
                $button.text(`Resend in ${timeLeft}s`);
                timeLeft--;
            }
        }, 1000);
    }

    function verifyEmailOtp() {
        if (emailVerified) return;

        const otp = $('input[name="email_otp"]').val();
        if (!otp) {
            toastr.error('Please enter OTP');
            return;
        }

        $.ajax({
            url: '/verification/verify-email-otp',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            data: JSON.stringify({ otp }),
            contentType: 'application/json',
            success: function(data) {
                if (data.status === 'success') {
                    toastr.success('Email verified successfully');
                    emailVerified = true;

                    $('input[name="email_otp"]').prop('disabled', true).addClass('hidden');
                    $('#send-otp-email').addClass('hidden');

                    $('#verify-email-btn')
                        .prop('disabled', true)
                        .removeClass('bg-green-600 hover:bg-green-700')
                        .addClass('bg-gray-400')
                        .text('Email Verified');

                    checkAllVerified();
                } else {
                    toastr.error(data.message || 'Invalid OTP');
                }
            },
            error: function(error) {
                console.error('Error:', error);
                toastr.error('Failed to verify OTP');
            }
        });
    }

    function verifyPhoneOtp() {
        if (phoneVerified) return;

        const otp = $('input[name="phone_otp"]').val();
        if (!otp) {
            toastr.error('Please enter OTP');
            return;
        }

        $.ajax({
            url: '/verification/verify-phone-otp',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            data: JSON.stringify({ otp }),
            contentType: 'application/json',
            success: function(data) {
                if (data.status === 'success') {
                    toastr.success('Phone verified successfully');
                    phoneVerified = true;

                    $('input[name="phone_otp"]').prop('disabled', true).addClass('hidden');
                    $('#send-otp-phone').addClass('hidden');

                    $('#verify-phone-btn')
                        .prop('disabled', true)
                        .removeClass('bg-green-600 hover:bg-green-700')
                        .addClass('bg-gray-400')
                        .text('Phone Verified');

                    checkAllVerified();
                } else {
                    toastr.error(data.message || 'Invalid OTP');
                }
            },
            error: function(error) {
                console.error('Error:', error);
                toastr.error('Failed to verify OTP');
            }
        });
    }
    function verifyDocument() {
        if (documentUploaded) return;

        const path = $('input[name="identity_document_path"]').val();
        console.log(path)
        if (!path) {
            toastr.error('Document is not uploaded');
            return;
        }

        $.ajax({
            url: '/verification/verify-document',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            data: JSON.stringify({ path }),
            contentType: 'application/json',
            success: function(data) {
                if (data.status === 'success') {
                    toastr.success(data.message);
                    documentUploaded = true;
                    checkAllVerified();
                } else {
                    toastr.error(data.message || 'File not uploaded yet');
                }
            },
            error: function(error) {
                console.error('Error:', error);
                toastr.error( 'File not uploaded yet');
            }
        });
    }

    function checkAllVerified() {
        if (emailVerified && phoneVerified && documentUploaded) {
            submitVerification()
        }
    }

    function submitVerification() {
        window.location.href = '{{ route("frontend.verification.complete") }}';
    }
</script>
@endsection
