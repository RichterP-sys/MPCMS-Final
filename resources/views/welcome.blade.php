<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome — Cooperative Management System</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-white text-gray-800">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3" data-feather="handshake" stroke-width="2.5"></svg>
                <h1 class="text-2xl font-bold">MPCMS</h1>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="#home" class="hover:text-blue-200 transition">Home</a>
                <a href="#about" class="hover:text-blue-200 transition">About Us</a>
                <a href="#services" class="hover:text-blue-200 transition">Services</a>
                <a href="#contact" class="hover:text-blue-200 transition">Contact</a>
            </div>
            <div class="space-x-2">
                <a href="{{ route('user.login') }}" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-blue-50 font-semibold transition">Member Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-4">Welcome to MPCMS</h2>
            <p class="text-xl mb-8">Empowering Communities Through Cooperative Excellence</p>
            <p class="text-lg mb-8 max-w-3xl mx-auto">
                Join thousands of members who trust us to manage their contributions, loans, and financial goals. Our cooperative platform makes it easy to grow your wealth together.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('user.login') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">Member Login</a>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12">About Us</h2>
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <p class="text-lg mb-4">
                        The Cooperative Management System (MPCMS) is dedicated to providing accessible financial services to our community members. Since our inception, we've been committed to helping individuals save, invest, and achieve their financial dreams through cooperative principles.
                    </p>
                    <p class="text-lg mb-4">
                        Our platform combines traditional cooperative values with modern technology, making it easier than ever for members to manage their accounts, request loans, and access detailed financial reports.
                    </p>
                    <p class="text-lg">
                        We believe in transparency, mutual benefit, and community growth. Every member is a stakeholder in our success.
                    </p>
                </div>
                <div class="bg-blue-100 rounded-lg p-8 text-center">
                    <div class="text-5xl font-bold text-blue-600 mb-2">{{ date('Y') - 2020 }}+</div>
                    <p class="text-xl mb-6">Years of Service</p>
                    <div class="space-y-4">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">5000+</p>
                            <p>Active Members</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">₱50M+</p>
                            <p>Total Contributions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12">Our Services</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Service 1: Contributions -->
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="bg-blue-100 rounded-full p-4 inline-block mb-4">
                        <svg class="w-12 h-12 text-blue-600 text-3xl" data-feather="trending-up" stroke-width="2.5"></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Savings & Contributions</h3>
                    <p class="text-gray-600 mb-4">
                        Secure your future with our flexible contribution plans. Save regularly and watch your wealth grow with competitive returns.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Flexible contribution amounts</li>
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Competitive interest rates</li>
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Easy tracking and reporting</li>
                    </ul>
                </div>

                <!-- Service 2: Loans -->
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="bg-green-100 rounded-full p-4 inline-block mb-4">
                        <svg class="w-12 h-12 text-green-600 text-3xl" data-feather="dollar-sign" stroke-width="2.5"></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Loan Services</h3>
                    <p class="text-gray-600 mb-4">
                        Access quick and affordable loans for your needs. Our competitive rates and flexible terms make borrowing simple.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Fast approval process</li>
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Flexible terms (6-36 months)</li>
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Low interest rates</li>
                    </ul>
                </div>

                <!-- Service 3: Reports & Analytics -->
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="bg-purple-100 rounded-full p-4 inline-block mb-4">
                        <svg class="w-12 h-12 text-purple-600 text-3xl" data-feather="line-chart" stroke-width="2.5"></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Reports & Analytics</h3>
                    <p class="text-gray-600 mb-4">
                        Get detailed insights into your financial standing with comprehensive reports and real-time analytics.
                    </p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Real-time account data</li>
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Dividend projections</li>
                        <li><svg class="w-5 h-5 text-green-600 mr-2 inline" data-feather="check" stroke-width="2.5"></svg>Detailed transaction history</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-blue-50">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12">Why Choose MPCMS?</h2>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <svg class="w-16 h-16 text-blue-600 text-4xl mb-4 block" data-feather="lock" stroke-width="2.5"></svg>
                    <h3 class="font-bold text-lg mb-2">Secure</h3>
                    <p class="text-gray-600">Your data is protected with industry-leading security measures.</p>
                </div>
                <div class="text-center">
                    <svg class="w-16 h-16 text-blue-600 text-4xl mb-4 block" data-feather="clock" stroke-width="2.5"></svg>
                    <h3 class="font-bold text-lg mb-2">24/7 Access</h3>
                    <p class="text-gray-600">Access your account anytime, anywhere through our platform.</p>
                </div>
                <div class="text-center">
                    <svg class="w-16 h-16 text-blue-600 text-4xl mb-4 block" data-feather="users" stroke-width="2.5"></svg>
                    <h3 class="font-bold text-lg mb-2">Community</h3>
                    <p class="text-gray-600">Be part of a trusted community of savers and investors.</p>
                </div>
                <div class="text-center">
                    <svg class="w-16 h-16 text-blue-600 text-4xl mb-4 block" data-feather="headphones" stroke-width="2.5"></svg>
                    <h3 class="font-bold text-lg mb-2">Support</h3>
                    <p class="text-gray-600">Dedicated support team ready to assist you anytime.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12">Get In Touch</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Contact Info 1 -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <svg class="w-12 h-12 text-blue-600 text-3xl mb-4 block" data-feather="map-pin" stroke-width="2.5"></svg>
                    <h3 class="font-bold text-lg mb-2">Address</h3>
                    <p class="text-gray-600">
                        123 Cooperative Street<br>
                        Manila, Philippines 1000
                    </p>
                </div>

                <!-- Contact Info 2 -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <svg class="w-12 h-12 text-blue-600 text-3xl mb-4 block" data-feather="phone" stroke-width="2.5"></svg>
                    <h3 class="font-bold text-lg mb-2">Phone</h3>
                    <p class="text-gray-600">
                        +63 (2) 1234-5678<br>
                        <span class="text-sm">Available 9AM - 6PM</span>
                    </p>
                </div>

                <!-- Contact Info 3 -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <svg class="w-12 h-12 text-blue-600 text-3xl mb-4 block" data-feather="mail" stroke-width="2.5"></svg>
                    <h3 class="font-bold text-lg mb-2">Email</h3>
                    <p class="text-gray-600">
                        info@mpcms.org<br>
                        support@mpcms.org
                    </p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="mt-12 max-w-2xl mx-auto bg-gray-50 rounded-lg p-8">
                <h3 class="text-2xl font-bold mb-6 text-center">Send us a Message</h3>
                @if(session('contact_success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-200 rounded-lg text-green-800 text-center">
                        {{ session('contact_success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-4">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Your Name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Your Email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    </div>
                    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    <textarea name="message" placeholder="Your Message" rows="5" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>{{ old('message') }}</textarea>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="font-bold mb-4">About</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Our Team</a></li>
                        <li><a href="#" class="hover:text-white">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Services</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white">Contributions</a></li>
                        <li><a href="#" class="hover:text-white">Loans</a></li>
                        <li><a href="#" class="hover:text-white">Reports</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white">Help Center</a></li>
                        <li><a href="#" class="hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><svg class="w-6 h-6" data-feather="facebook" stroke-width="2.5"></svg></a>
                        <a href="#" class="text-gray-400 hover:text-white"><svg class="w-6 h-6" data-feather="twitter" stroke-width="2.5"></svg></a>
                        <a href="#" class="text-gray-400 hover:text-white"><svg class="w-6 h-6" data-feather="linkedin" stroke-width="2.5"></svg></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Cooperative Management System. All rights reserved.</p>
                <p class="mt-2 text-sm">
                    <a href="#" class="hover:text-white">Privacy Policy</a> | 
                    <a href="#" class="hover:text-white">Terms of Service</a> | 
                    <a href="#" class="hover:text-white">Contact</a>
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({behavior: 'smooth'});
                }
            });
        });
    </script>
</body>
</html>
