<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radiology Resident - Medical Education Platform</title>
    <meta name="description" content="Master Radiology with Expert-Led Courses">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center">
                        <i class="fas fa-stethoscope text-blue-600 text-2xl mr-2"></i>
                        <span class="font-bold text-xl text-gray-900">Radiology Resident</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Home</a>
                    <a href="#courses" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Courses</a>
                    <a href="#about" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">About</a>
                    <a href="#contact" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Contact</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-4">
                    <a href="#login" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Login</a>
                    <a href="#register" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Sign Up</a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="mobile-menu-button text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="hero-gradient text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Master Radiology with<br>
                        <span class="text-yellow-300">Expert-Led Courses</span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-gray-100">
                        Advance your radiology knowledge with comprehensive courses designed by leading radiologists
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#courses" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                            Browse Courses
                        </a>
                        <a href="#register" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                            Start Learning
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Status Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">🚀 Platform Status</h2>
                    <div class="inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full">
                        <i class="fas fa-check-circle mr-2"></i>
                        System Online & Ready
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            ✅
                        </div>
                        <div class="text-gray-600">PHP Working</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            ✅
                        </div>
                        <div class="text-gray-600">Database Ready</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            ✅
                        </div>
                        <div class="text-gray-600">Security Enabled</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            🔄
                        </div>
                        <div class="text-gray-600">Building Features</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Coming Soon Features -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Coming Soon
                    </h2>
                    <p class="text-lg text-gray-600">
                        Exciting features being developed for the radiology community
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-48 flex items-center justify-center">
                            <i class="fas fa-x-ray text-white text-4xl"></i>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Interactive X-Ray Cases</h3>
                            <p class="text-gray-600 mb-4">Real patient cases with step-by-step interpretation guidance</p>
                            <div class="text-blue-600 font-semibold">Coming Soon</div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-48 flex items-center justify-center">
                            <i class="fas fa-brain text-white text-4xl"></i>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Neuroimaging Mastery</h3>
                            <p class="text-gray-600 mb-4">Advanced MRI and CT interpretation for brain imaging</p>
                            <div class="text-blue-600 font-semibold">Coming Soon</div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-48 flex items-center justify-center">
                            <i class="fas fa-certificate text-white text-4xl"></i>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Certification Programs</h3>
                            <p class="text-gray-600 mb-4">Professional certificates for continuing medical education</p>
                            <div class="text-blue-600 font-semibold">Coming Soon</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Development Status -->
        <section class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Development Progress</h2>
                </div>
                
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold">Platform Foundation</span>
                            <span class="text-green-600 font-bold">100%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold">User Authentication</span>
                            <span class="text-blue-600 font-bold">25%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 25%"></div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold">Course Management</span>
                            <span class="text-blue-600 font-bold">10%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 10%"></div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold">Admin Dashboard</span>
                            <span class="text-gray-600 font-bold">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gray-400 h-2 rounded-full" style="width: 5%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-stethoscope text-blue-400 text-2xl mr-2"></i>
                    <span class="font-bold text-xl">Radiology Resident</span>
                </div>
                <p class="text-gray-400 mb-4">
                    Building the future of radiology education
                </p>
                <p class="text-gray-500 text-sm">
                    &copy; <?php echo date('Y'); ?> Radiology Resident. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button')?.addEventListener('click', function() {
            alert('Mobile menu coming soon!');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
