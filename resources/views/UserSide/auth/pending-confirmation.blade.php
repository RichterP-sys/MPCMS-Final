@extends('UserSide.layouts.guest')

@section('content')
@include('UserSide.partials.theme-config')
<div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-blue-500 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Panel - Welcome Card -->
            <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-2xl p-8 text-white flex flex-col justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider mb-4 opacity-90">LARAVEL ACCOUNT</p>
                    <h1 class="text-4xl font-bold mb-4">Welcome back, there</h1>
                    <p class="text-white/80 text-lg">
                        Complete your profile to unlock personalized features and faster approvals.
                    </p>
                </div>
                
                <div class="mt-auto pt-8">
                    <div class="space-y-2">
                        <p class="text-sm">
                            <span class="font-semibold">Profile status:</span> 
                            <span class="text-yellow-300">Pending completion</span>
                        </p>
                        <p class="text-sm">
                            <span class="font-semibold">Email:</span> 
                            <span>{{ auth('member')->user()?->email ?? 'justine@gmail.com' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Profile Form -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 flex flex-col max-h-[85vh]">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Complete your profile</h2>
                    <p class="text-gray-600">Hi there, confirm your details to finish setup.</p>
                </div>

                <div class="overflow-y-auto flex-1 pr-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <form method="POST" action="#" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <!-- Profile Photo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Profile Photo</label>
                            <input type="file" name="profile_photo" accept="image/*" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" placeholder="First Name" 
                                   value="{{ auth('member')->user()?->first_name ?? '' }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" placeholder="Last Name" 
                                   value="{{ auth('member')->user()?->last_name ?? '' }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" placeholder="Phone Number" 
                                   value="{{ auth('member')->user()?->phone ?? '' }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                            <input type="text" name="address" placeholder="Address" 
                                   value="{{ auth('member')->user()?->address ?? '' }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Nature of Work -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nature of Work</label>
                            <input type="text" name="nature_of_work" placeholder="Self-employed, Farmer, etc." 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Employer or Business -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Employer or Business</label>
                            <input type="text" name="employer" placeholder="Employer or Business Name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Date of Employment -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Employment</label>
                            <input type="date" name="employment_date" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- TIN Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">TIN Number</label>
                            <input type="text" name="tin_number" placeholder="123-456-789-000" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- SSS/GSIS Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">SSS/GSIS Number</label>
                            <input type="text" name="sss_gsis_number" placeholder="01-2345678-9" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <p class="text-xs text-gray-500 mb-3">
                                By submitting, you agree to our terms and conditions.
                            </p>
                            <button type="submit" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                                Complete Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
