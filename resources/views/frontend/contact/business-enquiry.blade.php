@extends('_partials.app',['title' => 'ADLEF GROUP | HOME','description' => 'Power your finances with our personalized platform designed for Multi-asset servicing, seamlessly connecting traditional and digital financial ecosystems','image' => asset('assets/images/savings.svg'),'isDark' => true])
@section('content')
    <section class="bg-black">
        <div class="container p-6 py-20 mx-auto">

            <div class="alerts py-3">
                @if ($errors->any())
                    <div class="bg-red-400 rounded p-5">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li  class="text-white">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(\Illuminate\Support\Facades\Session::has('success'))
                    <div class="bg-green-500 rounded p-5">
                        <li class="text-white">Your enquiry has been submitted successfully</li>
                    </div>
                @endif
            </div>


            <h1 class="text-5xl mb-8 text-white leading-normal">Let's make <br> something big. <br> Together.</h1>
            <form class="space-y-6" action="{{route('frontend.contact-us.business-enquiry')}}" method="POST">
                @csrf
                <div>
                    <h2 class="text-lg font-medium mb-4 text-white">Your contact information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="first_name" placeholder="First Name"
                               class="bg-gray-800 text-white p-3 rounded w-full">
                        <input type="text" name="last_name" placeholder="Last Name"
                               class="bg-gray-800 text-white p-3 rounded w-full">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <input type="text" name="business_name" placeholder="Business or Company Name"
                               class="bg-gray-800 text-white p-3 rounded w-full">
                        <input type="text" name="job_title" placeholder="Job Title"
                               class="bg-gray-800 text-white p-3 rounded w-full">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <input type="email" name="business_email" placeholder="Business Email"
                               class="bg-gray-800 text-white p-3 rounded w-full">
                        <input type="tel" name="phone" placeholder="Phone"
                               class="bg-gray-800 text-white p-3 rounded w-full">
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-medium mb-4 text-white">About your project</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <label class="flex items-center text-white">
                            <input type="checkbox" name="fx_services" class="form-checkbox bg-gray-800 text-white mr-2">
                            <span>FX Services</span>
                        </label>
                        <label class="flex items-center text-white">
                            <input type="checkbox" name="asset_servicing"
                                   class="form-checkbox bg-gray-800 text-white mr-2">
                            <span>Asset Servicing</span>
                        </label>
                        <label class="flex items-center text-white">
                            <input type="checkbox" name="settlement_clearing"
                                   class="form-checkbox bg-gray-800 text-white mr-2">
                            <span>Settlement & Clearing</span>
                        </label>
                        <label class="flex items-center text-white">
                            <input type="checkbox" name="treasury_services"
                                   class="form-checkbox bg-gray-800 text-white mr-2">
                            <span>Treasury Services</span>
                        </label>
                        <label class="flex items-center text-white">
                            <input type="checkbox" name="other" class="form-checkbox bg-gray-800 text-white mr-2">
                            <span>Other</span>
                        </label>
                    </div>
                </div>

                <div>
                    <textarea name="project_description" placeholder="Tell us a bit about your project..."
                              class="bg-gray-800 text-white p-3 rounded w-full h-24"></textarea>
                </div>

                <div>
                    <label class="flex items-center text-white">
                        How did you hear about this
                    </label>
                    <select name="how_did_you_hear" class="bg-gray-800 text-white p-3 rounded w-full">
                        <option disabled="disabled" selected="selected" value="">Select an option…</option>
                        <option value="Google">Google
                        </option>
                        <option value="Social media">Social media
                        </option>
                        <option value="Conference or event">Conference or event
                        </option>
                        <option value="Referred by someone">Referred by someone
                        </option>
                        <option value="Other">Other
                        </option>
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-white text-black py-3 px-6 rounded font-medium">Send Message
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
