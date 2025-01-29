<div class="max-w-lg mx-auto bg-white rounded-xl shadow-lg p-8">
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                <span class="text-gray-700 font-bold text-xl">1</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Add Bank Account</h1>
        </div>
        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>
    </div>
    <form action="#" method="POST">
        @csrf
        <div class="space-y-6" id="form-fields">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Account Holder Name</label>
                <input type="text" name="account_holder_name" id="account_holder_name" value="{{auth()->user()->profile->getFullName()}}" readonly 
                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-gray-600 focus:ring-2 focus:ring-gray-200 outline-none transition-all text-gray-700 font-medium bg-gray-50" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Bank Name</label>
                <input type="text" name="bank_name" id="bank_name" 
                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-gray-600 focus:ring-2 focus:ring-gray-200 outline-none transition-all text-gray-700 font-medium" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Account Number</label>
                <input type="text" name="account_number" id="account_number" 
                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-gray-600 focus:ring-2 focus:ring-gray-200 outline-none transition-all text-gray-700 font-medium" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">SWIFT Code</label>
                <input type="text" name="ifsc_code" id="ifsc_code" 
                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-gray-600 focus:ring-2 focus:ring-gray-200 outline-none transition-all text-gray-700 font-medium" required>
            </div>
        </div>

        <!-- Review Section (Initially Hidden) -->
        <div class="space-y-4 hidden" id="review-section">
            <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-gray-700 font-bold text-xl">1</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Bank Info</h2>
                </div>

                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Account Holder Name</p>
                        <div class="flex items-center justify-between bg-white p-4 rounded-lg border-2 border-gray-100">
                            <p class="font-bold text-lg text-gray-800" id="review-account-holder-name"></p>
                            <button type="button" onclick="copyToClipboard('review-account-holder-name')" 
                                class="text-gray-600 hover:text-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Bank Name</p>
                        <div class="flex items-center justify-between bg-white p-4 rounded-lg border-2 border-gray-100">
                            <p class="font-bold text-lg text-gray-800" id="review-bank-name"></p>
                            <button type="button" onclick="copyToClipboard('review-bank-name')" 
                                class="text-gray-600 hover:text-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">SWIFT Code</p>
                        <div class="flex items-center justify-between bg-white p-4 rounded-lg border-2 border-gray-100">
                            <p class="font-bold text-lg text-gray-800" id="review-ifsc-code"></p>
                            <button type="button" onclick="copyToClipboard('review-ifsc-code')" 
                                class="text-gray-600 hover:text-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Account No.</p>
                        <div class="flex items-center justify-between bg-white p-4 rounded-lg border-2 border-gray-100">
                            <p class="font-bold text-lg text-gray-800" id="review-account-number"></p>
                            <button type="button" onclick="copyToClipboard('review-account-number')" 
                                class="text-gray-600 hover:text-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 space-y-3">
            <button type="button" id="next-btn" 
                class="w-full bg-black text-white py-4 px-4 rounded-lg hover:bg-gray-800 transition-colors font-bold text-lg shadow-lg shadow-gray-200">
                Next
            </button>
            <button type="submit" id="submit-btn" 
                class="w-full bg-black text-white py-4 px-4 rounded-lg hover:bg-gray-800 transition-colors font-bold text-lg shadow-lg shadow-gray-200 hidden">
                Submit
            </button>
            <button type="button" id="back-btn" 
                class="w-full bg-gray-100 text-gray-700 py-4 px-4 rounded-lg hover:bg-gray-200 transition-colors font-bold text-lg hidden">
                Back to Edit
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formFields = document.getElementById('form-fields');
    const reviewSection = document.getElementById('review-section');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    const backBtn = document.getElementById('back-btn');

    nextBtn.addEventListener('click', function() {
        // Validate form
        const form = document.querySelector('form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Update review section with form values
        document.getElementById('review-account-holder-name').textContent = document.getElementById('account_holder_name').value;
        document.getElementById('review-bank-name').textContent = document.getElementById('bank_name').value;
        document.getElementById('review-account-number').textContent = document.getElementById('account_number').value;
        document.getElementById('review-ifsc-code').textContent = document.getElementById('ifsc_code').value;

        // Show/hide elements
        formFields.classList.add('hidden');
        reviewSection.classList.remove('hidden');
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
        backBtn.classList.remove('hidden');
    });

    backBtn.addEventListener('click', function() {
        // Show/hide elements
        formFields.classList.remove('hidden');
        reviewSection.classList.add('hidden');
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
        backBtn.classList.add('hidden');
    });
});

function copyToClipboard(elementId) {
    const text = document.getElementById(elementId).textContent;
    navigator.clipboard.writeText(text).then(() => {
        // You could add a toast notification here
        console.log('Copied to clipboard');
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
</script>
