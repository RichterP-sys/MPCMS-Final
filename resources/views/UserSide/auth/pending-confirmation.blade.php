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

                    <!-- Profile Status Progress Box -->
                    <div class="mt-6 bg-white rounded-xl shadow-lg p-4">
                        <div class="flex flex-col space-y-4">
                            <!-- Step 1: Registration -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="h-1 bg-green-500 rounded"></div>
                                </div>
                                <span class="ml-3 text-xs font-bold text-gray-900">Registration</span>
                            </div>

                            <!-- Step 2: Profile Completion -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="h-1 bg-green-500 rounded"></div>
                                </div>
                                <span class="ml-3 text-xs font-bold text-gray-900">Completed</span>
                            </div>

                            <!-- Step 3: Admin Approval -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center text-white flex-shrink-0 animate-pulse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="h-1 bg-gradient-to-r from-yellow-500 to-gray-300 rounded"></div>
                                </div>
                                <span class="ml-3 text-xs font-bold text-yellow-600">Waiting</span>
                            </div>

                            <!-- Step 4: Account Active -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="h-1 bg-gray-300 rounded"></div>
                                </div>
                                <span class="ml-3 text-xs font-bold text-gray-500">Pending</span>
                            </div>
                        </div>
                    </div>
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
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('user.logout') }}" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-white/30 text-sm font-semibold rounded-lg text-white bg-white/10 hover:bg-white/20 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
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
