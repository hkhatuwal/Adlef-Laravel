@extends('_partials.app',['title' => 'ADLEF GROUP | Contact Us','description' => 'Power your finances with our personalized platform designed for Multi-asset servicing, seamlessly connecting traditional and digital financial ecosystems','image' => asset('assets/images/savings.svg'),'isDark' => true])
@section('content')
    <div class="container mx-auto p-6 py-24">
        <!-- Heading Section -->
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Hello. How may we assist you?</h1>
            <p class="text-lg text-gray-600">Explore the categories below and select an option so we can put you in touch with the right people.</p>
        </div>

        <!-- Inquiry Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <!-- Customer Inquiries -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-xl font-semibold mb-4">Customer Inquiries</h2>
                <p class="text-gray-600 mb-4">If you have questions about an existing account, please sign into send us a secure message.</p>
                <a href="#" class="text-blue-500 font-semibold hover:underline">SIGN IN TO CLIENT PORTAL →</a>
            </div>
            <!-- Business Inquiries -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-xl font-semibold mb-4">Business Inquiries</h2>
                <p class="text-gray-600 mb-4">We'd love to hear about your project and work with you for the best solution.</p>
                <a href="{{route('frontend.contact-us.business-enquiry')}}" class="text-blue-500 font-semibold hover:underline">CONTACT B2B SALES →</a>
            </div>
            <!-- Press Inquiries -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-xl font-semibold mb-4">User Inquiries</h2>
                <p class="text-gray-600 mb-4">Visit our press page to learn about our recent announcements or to connect with our PR team.</p>
                <a href="{{route('frontend.contact-us.business-enquiry')}}" class="text-blue-500 font-semibold hover:underline">CREATE INQUIRIES →</a>

            </div>
        </div>

        <!-- Footer Section -->
        <div class="bg-gray-800 p-6 mt-8">
            <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 ">
                <!-- General Questions -->
                <div class="text-center md:text-left">
                    <h3 class="font-semibold text-lg">General Questions</h3>
                    <p class="text-gray-600">For general inquiries, please email us at <a href="mailto:hello@adlefgroup.com" class="text-blue-500 hover:underline">hello@adlefgroup.com</a> or Whats app +1 209 890 0004.</p>
                </div>
                <!-- Mailing Address -->
                <div class="text-center md:text-left">
                    <h3 class="font-semibold text-lg">Mailing Address</h3>
                    <p class="text-gray-600">

                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
