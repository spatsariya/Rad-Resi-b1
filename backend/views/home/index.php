<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Radiology Resident'; ?></title>
    <meta name="description" content="<?php echo $description ?? 'Master Radiology with Expert-Led Courses'; ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
                    <a href="/courses" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Courses</a>
                    <a href="/about" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">About</a>
                    <a href="/contact" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Contact</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Dashboard</a>
                        <a href="/logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Login</a>
                        <a href="/register" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Sign Up</a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="mobile-menu-button text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600">Home</a>
                <a href="/courses" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600">Courses</a>
                <a href="/about" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600">About</a>
                <a href="/contact" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600">Contact</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/dashboard" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600">Dashboard</a>
                    <a href="/logout" class="block px-3 py-2 text-base font-medium text-red-600 hover:text-red-700">Logout</a>
                <?php else: ?>
                    <a href="/login" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600">Login</a>
                    <a href="/register" class="block px-3 py-2 text-base font-medium text-blue-600 hover:text-blue-700">Sign Up</a>
                <?php endif; ?>
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
                        <a href="/courses" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                            Browse Courses
                        </a>
                        <a href="/register" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                            Start Learning
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            <?php echo isset($stats['students']) ? $stats['students'] : '250'; ?>+
                        </div>
                        <div class="text-gray-600">Students</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            <?php echo isset($stats['courses']) ? $stats['courses'] : '15'; ?>+
                        </div>
                        <div class="text-gray-600">Courses</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            <?php echo isset($stats['instructors']) ? $stats['instructors'] : '8'; ?>+
                        </div>
                        <div class="text-gray-600">Expert Instructors</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            <?php echo isset($stats['hours']) ? $stats['hours'] : '120'; ?>+
                        </div>
                        <div class="text-gray-600">Hours of Content</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Courses Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Featured Courses
                    </h2>
                    <p class="text-lg text-gray-600">
                        Discover our most popular radiology courses
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php if (!empty($featured_courses)): ?>
                        <?php foreach ($featured_courses as $course): ?>
                            <div class="bg-white rounded-lg card-shadow overflow-hidden hover:shadow-xl transition duration-300">
                                <img src="<?php echo $course['image'] ?? '/assets/images/course-placeholder.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($course['title']); ?>" 
                                     class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </h3>
                                    <p class="text-gray-600 mb-4">
                                        <?php echo htmlspecialchars(substr($course['description'], 0, 120)) . '...'; ?>
                                    </p>
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-blue-600 font-semibold">
                                            Dr. <?php echo htmlspecialchars($course['instructor']); ?>
                                        </span>
                                        <div class="flex items-center">
                                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                                            <span class="text-gray-600"><?php echo $course['rating']; ?></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold text-blue-600">
                                            $<?php echo $course['price']; ?>
                                        </span>
                                        <a href="/course/<?php echo $course['id']; ?>" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition duration-300">
                                            Learn More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Placeholder courses -->
                        <div class="bg-white rounded-lg card-shadow overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-48 flex items-center justify-center">
                                <i class="fas fa-x-ray text-white text-4xl"></i>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Basic Chest X-Ray Interpretation</h3>
                                <p class="text-gray-600 mb-4">Learn the fundamentals of chest X-ray interpretation with expert guidance...</p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-blue-600 font-semibold">Dr. Sarah Johnson</span>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="text-gray-600">4.8</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-blue-600">$99</span>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                                        Learn More
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg card-shadow overflow-hidden">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-48 flex items-center justify-center">
                                <i class="fas fa-brain text-white text-4xl"></i>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Neuroimaging Fundamentals</h3>
                                <p class="text-gray-600 mb-4">Master the basics of brain MRI and CT interpretation...</p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-blue-600 font-semibold">Dr. Michael Chen</span>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="text-gray-600">4.9</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-blue-600">$149</span>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                                        Learn More
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg card-shadow overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-48 flex items-center justify-center">
                                <i class="fas fa-bone text-white text-4xl"></i>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Musculoskeletal Radiology</h3>
                                <p class="text-gray-600 mb-4">Comprehensive approach to bone and joint imaging...</p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-blue-600 font-semibold">Dr. Emily Rodriguez</span>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="text-gray-600">4.7</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-blue-600">$129</span>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                                        Learn More
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-12">
                    <a href="/courses" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                        View All Courses
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-stethoscope text-blue-400 text-2xl mr-2"></i>
                        <span class="font-bold text-xl">Radiology Resident</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Advancing radiology education through expert-led courses and comprehensive learning materials.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/courses" class="text-gray-400 hover:text-white">Courses</a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white">Contact</a></li>
                        <li><a href="/privacy" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> info@radiologyresident.com</li>
                        <li><i class="fas fa-phone mr-2"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Medical Education Center</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; <?php echo date('Y'); ?> Radiology Resident. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
