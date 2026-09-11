<footer class="bg-black py-8">
    <hr class="my-20">

    <div class="max-w-7xl mx-auto px-4 flex flex-wrap">
        <!-- Left section -->
        <div class="w-full md:w-1/2 lg:w-1/3 mb-8 md:mb-0">
            <img src="{{asset('assets/images/logo-light.svg?v=1')}}" class="h-20 mb-4">
            <p class="text-gray-500">&copy; {{ date('Y') }} ADLEF HOLDING LLC. All rights reserved.</p>
            <p class="mt-4 text-gray-700">Join our email list to receive articles, tips from industry experts, and more.</p>
            <div class="mt-4 flex">
                <input type="email" placeholder="Enter your email" class="border border-gray-300 px-4 py-2 w-full">
                <button class="bg-black text-white px-4 py-2">
                    <span>&#10140;</span>
                </button>
            </div>
            <p class="mt-4 text-gray-500 text-sm">By submitting this form, you acknowledge that you have reviewed the terms of our <a href="{{ route('frontend.privacy-policy') }}" class="text-gray-700 underline">Privacy Policy</a>.</p>

            <div class="certifications flex gap-4 mt-4">
                <img src="{{asset('assets/images/visa.png')}}" alt="" class="h-6" >
                <img src="{{asset('assets/images/aes.jpg')}}" alt="" class="h-6" >
                <img src="{{asset('assets/images/mastercard.JPG')}}" alt="" class="h-6" >
                <img src="{{asset('assets/images/pci.png')}}" alt="" class="h-6" >
            </div>
        </div>

        <!-- Right section -->
        <div class="w-full md:w-1/2 lg:w-2/3 flex flex-wrap justify-between text-white">
            <div class="mb-6">
                <h3 class="font-bold text-white">Resources</h3>
                <ul class="mt-2 space-y-2">
                    <li><a href="{{ route('frontend.solutions') }}" class="text-white block">Solutions</a></li>
                    <li><a href="{{ route('frontend.api-documentation') }}" class="text-white block">Api Documentation</a></li>
                </ul>
            </div>
            <div class="mb-6">
                <h3 class="font-bold text-white">Company</h3>
                <ul class="mt-2 space-y-2">
                    <li><a href="{{ route('frontend.about') }}" class="text-white block">About Us</a></li>
                    <li><a href="{{ route('frontend.careers') }}" class="text-white block">Careers</a></li>
                    <li><a href="{{ route('frontend.contact-us') }}" class="text-white block">Contact Us</a></li>
                    <li><a href="{{ route('frontend.news-insights') }}" class="text-white block">News & Insights</a></li>
                </ul>
            </div>
            <div class="mb-6">
                <h3 class="font-bold text-white">Legal</h3>
                <ul class="mt-2 space-y-2">
                    <li><a href="{{ route('frontend.privacy-policy') }}" class="text-white block">Privacy Policy</a></li>
                    <li><a href="{{ route('frontend.cookie-policy') }}" class="text-white block">Cookie Policy</a></li>
                    <li><a href="{{ route('frontend.terms-of-use') }}" class="text-white block">Terms of Use</a></li>
                    <li><a href="{{ route('frontend.faq') }}" class="text-white block">FAQ</a></li>
                </ul>
            </div>
            <div class="mb-6">
                <h3 class="font-bold text-white">Social</h3>
                <ul class="mt-2 space-y-2">
                    <li><a href="#" class="text-white block">LinkedIn</a></li>
                    <li><a href="#" class="text-white block">YouTube</a></li>
                    <li><a href="#" class="text-white block">Crunchbase</a></li>
                </ul>
            </div>
        </div>
    </div>
    <p class="mt-4 text-center w-full">Powered by <strong>Adlef Holding LLC</strong></p>

</footer>
