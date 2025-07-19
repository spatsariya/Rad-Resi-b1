<?php
// Get the current path to highlight active menu item
$currentPath = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Radiology Resident'; ?></title>
	<meta name="description" content="<?php echo $description ?? 'Advanced radiology education platform'; ?>">
	
	<!-- Favicon -->
	<link rel="icon" type="image/svg+xml" href="/backend/assets/svg/light-icon.svg">
	<link rel="alternate icon" href="/backend/assets/svg/light-icon.svg">
	
	<!-- Tailwind CSS -->
	<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
	<!-- Custom Logo Styles -->
	<link rel="stylesheet" href="/backend/assets/css/logo-styles.css">
	<!-- Alpine JS -->
	<script type="module" src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
	<script nomodule src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine-ie11.min.js" defer></script>
	<!-- AOS Animation -->
	<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
	<!-- Poppins font -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	
	<style>
		/*primary color*/
		.bg-cream {
			background-color: #FFF2E1;
		}
		
		/*font*/
		body {
			font-family: 'Poppins', sans-serif;
		}
		
		.bg-blue-500 {
			background-color: #2563eb;
		}
		.text-blue-500 {
			color: #2563eb;
		}
		.bg-medical-blue {
			background-color: #1e40af;
		}
		.text-medical-blue {
			color: #1e40af;
		}
		.text-darken {
			color: #2F327D;
		}
		
		/* Dropdown animations */
		.rotate-180 {
			transform: rotate(180deg);
		}
		
		/* Custom dropdown transition */
		[x-cloak] { display: none !important; }
		
		/* Ensure dropdowns appear above other content */
		.relative .absolute {
			z-index: 9999;
		}
	</style>
</head>
<body class="antialiased">
	<!-- navbar -->
	<div x-data="{ open: false }" class="w-full text-gray-700 bg-cream">
        <div class="flex flex-col max-w-screen-xl px-8 mx-auto md:items-center md:justify-between md:flex-row">
            <div class="flex flex-row items-center justify-between py-6">
                <div class="relative md:mt-8">
                    <a href="/" class="relative z-50 rounded-lg focus:outline-none focus:shadow-outline">
						<img src="/backend/assets/svg/logo.svg" alt="Radiology Resident" class="logo-main">
					</a>
                </div>
                <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                        <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        <path x-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <nav :class="{ 'transform md:transform-none': !open, 'h-full': open }" class="h-0 md:h-auto flex flex-col flex-grow md:items-center pb-4 md:pb-0 md:flex md:justify-end md:flex-row origin-top duration-300 scale-y-0">
                <a class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline <?php echo $currentPath === '/' ? 'text-gray-900 font-semibold' : ''; ?>" href="/">Home</a>
                
                <!-- Theory Exams Dropdown -->
                <div class="relative" x-data="{ theoryOpen: false }">
                    <button @click="theoryOpen = !theoryOpen" class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline flex items-center <?php echo strpos($currentPath, '/theory/') === 0 ? 'text-gray-900 font-semibold' : ''; ?>">
                        Theory Exams
                        <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200" :class="{ 'rotate-180': theoryOpen }"></i>
                    </button>
                    <div x-show="theoryOpen" @click.away="theoryOpen = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 md:left-auto md:right-0">
                        <a href="/theory/notes" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/theory/notes' ? 'bg-blue-50 text-blue-600' : ''; ?>">Notes</a>
                        <a href="/theory/previous-year-questions" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/theory/previous-year-questions' ? 'bg-blue-50 text-blue-600' : ''; ?>">Previous Year Questions</a>
                        <a href="/theory/video-tutorials" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/theory/video-tutorials' ? 'bg-blue-50 text-blue-600' : ''; ?>">Video Tutorial</a>
                    </div>
                </div>
                
                <!-- Practical Exams Dropdown -->
                <div class="relative" x-data="{ practicalOpen: false }">
                    <button @click="practicalOpen = !practicalOpen" class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline flex items-center <?php echo strpos($currentPath, '/practical/') === 0 ? 'text-gray-900 font-semibold' : ''; ?>">
                        Practical Exams
                        <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200" :class="{ 'rotate-180': practicalOpen }"></i>
                    </button>
                    <div x-show="practicalOpen" @click.away="practicalOpen = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 md:left-auto md:right-0">
                        <a href="/practical/spotters" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/practical/spotters' ? 'bg-blue-50 text-blue-600' : ''; ?>">Spotters</a>
                        <a href="/practical/osce" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/practical/osce' ? 'bg-blue-50 text-blue-600' : ''; ?>">OSCE</a>
                        <a href="/practical/exam-cases" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/practical/exam-cases' ? 'bg-blue-50 text-blue-600' : ''; ?>">Exam Cases</a>
                        <a href="/practical/table-viva" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/practical/table-viva' ? 'bg-blue-50 text-blue-600' : ''; ?>">Table Viva</a>
                    </div>
                </div>
                
                <!-- Practice Cases & Quiz Dropdown -->
                <div class="relative" x-data="{ practiceOpen: false }">
                    <button @click="practiceOpen = !practiceOpen" class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline flex items-center <?php echo strpos($currentPath, '/practice/') === 0 ? 'text-gray-900 font-semibold' : ''; ?>">
                        Practice & Quiz
                        <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200" :class="{ 'rotate-180': practiceOpen }"></i>
                    </button>
                    <div x-show="practiceOpen" @click.away="practiceOpen = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 md:left-auto md:right-0">
                        <a href="/practice/spotters" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/practice/spotters' ? 'bg-blue-50 text-blue-600' : ''; ?>">Spotters</a>
                        <a href="/practice/osce" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/practice/osce' ? 'bg-blue-50 text-blue-600' : ''; ?>">OSCE</a>
                        <a href="/practice/exam-cases" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/practice/exam-cases' ? 'bg-blue-50 text-blue-600' : ''; ?>">Exam Cases</a>
                        <a href="/practice/table-viva" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo $currentPath === '/practice/table-viva' ? 'bg-blue-50 text-blue-600' : ''; ?>">Table Viva</a>
                    </div>
                </div>
                
                <a class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline <?php echo $currentPath === '/testimonials' ? 'text-gray-900 font-semibold' : ''; ?>" href="/testimonials">Testimonials</a>
                <a class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline <?php echo $currentPath === '/blog' ? 'text-gray-900 font-semibold' : ''; ?>" href="/blog">Blog</a>
                <a class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline <?php echo $currentPath === '/plans' ? 'text-gray-900 font-semibold' : ''; ?>" href="/plans">Plans</a>
                <a class="px-10 py-3 mt-2 text-sm text-center bg-white text-gray-800 rounded-full md:mt-8 md:ml-4" href="/login">Login</a>
                <a class="px-10 py-3 mt-2 text-sm text-center bg-blue-500 text-white rounded-full md:mt-8 md:ml-4" href="/register">Sign Up</a>
            </nav>
        </div>
    </div>

	<!-- Main Content -->
	<main class="min-h-screen bg-gray-50">
		<?php if(!isset($content)): ?>
		<!-- Page Header -->
		<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center">
					<h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">
						<?php echo $page_title ?? 'Page Title'; ?>
					</h1>
					<p class="text-xl text-blue-100 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
						<?php echo $page_description ?? 'Page description'; ?>
					</p>
				</div>
			</div>
		</section>

		<!-- Content Section -->
		<section class="py-16">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="bg-white rounded-lg shadow-sm p-8">
					<div class="text-center py-12">
						<div class="mb-8">
							<i class="fas fa-construction text-6xl text-gray-300 mb-4"></i>
						</div>
						<h2 class="text-3xl font-bold text-gray-900 mb-4">Coming Soon</h2>
						<p class="text-gray-600 mb-8 max-w-2xl mx-auto">
							This section is currently under development. We're working hard to bring you the best learning experience for <?php echo strtolower($page_title ?? 'this section'); ?>.
						</p>
						
						<div class="bg-blue-50 border border-blue-200 rounded-lg p-6 max-w-md mx-auto mb-8">
							<div class="flex items-center">
								<i class="fas fa-info-circle text-blue-500 mr-3"></i>
								<div class="text-left">
									<h3 class="text-blue-800 font-semibold">What's Coming</h3>
									<p class="text-blue-700 text-sm">Interactive content, quizzes, and comprehensive study materials.</p>
								</div>
							</div>
						</div>
						
						<div class="space-x-4">
							<a href="/" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
								<i class="fas fa-home mr-2"></i>
								Back to Home
							</a>
							<a href="/plans" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
								<i class="fas fa-credit-card mr-2"></i>
								View Plans
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php else: ?>
		<!-- Custom Content -->
		<?php echo $content; ?>
		<?php endif; ?>
	</main>

	<!-- Footer -->
	<footer class="bg-gray-900 text-white py-12">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid grid-cols-1 md:grid-cols-4 gap-8">
				<div class="col-span-1 md:col-span-2">
					<div class="flex items-center mb-4">
						<img src="/backend/assets/svg/logo-dark-bg.svg" alt="Radiology Resident" class="logo-footer">
					</div>
					<p class="text-gray-400 max-w-md">
						Advanced radiology education platform designed for medical students and residents.
					</p>
				</div>
				<div>
					<h3 class="text-lg font-semibold mb-4">Quick Links</h3>
					<ul class="space-y-2">
						<li><a href="/" class="text-gray-400 hover:text-white">Home</a></li>
						<li><a href="/plans" class="text-gray-400 hover:text-white">Plans</a></li>
						<li><a href="/blog" class="text-gray-400 hover:text-white">Blog</a></li>
						<li><a href="/testimonials" class="text-gray-400 hover:text-white">Testimonials</a></li>
					</ul>
				</div>
				<div>
					<h3 class="text-lg font-semibold mb-4">Support</h3>
					<ul class="space-y-2">
						<li><a href="/contact" class="text-gray-400 hover:text-white">Contact Us</a></li>
						<li><a href="#" class="text-gray-400 hover:text-white">Help Center</a></li>
						<li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
						<li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
					</ul>
				</div>
			</div>
			<div class="border-t border-gray-800 mt-8 pt-8 text-center">
				<p class="text-gray-400">&copy; 2025 Radiology Resident. All rights reserved.</p>
			</div>
		</div>
	</footer>

	<!-- AOS Animation Script -->
	<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
	<script>
		AOS.init({
			duration: 1000,
			once: true
		});
	</script>
</body>
</html>
