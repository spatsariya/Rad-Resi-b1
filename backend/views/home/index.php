<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Radiology Resident - Master Radiology with Expert-Led Courses'; ?></title>
	<meta name="description" content="<?php echo $description ?? 'Advanced radiology education platform with comprehensive courses designed by leading radiologists'; ?>">
	
	<!-- Favicon -->
	<link rel="icon" type="image/svg+xml" href="/assets/svg/light-icon.svg">
	<link rel="alternate icon" href="/assets/svg/light-icon.svg">
	
	<!-- Tailwind CSS -->
	<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
	<!-- Custom Logo Styles -->
	<link rel="stylesheet" href="/assets/css/logo-styles.css">
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
		.floating { 
			animation-name: floating; 
			animation-duration: 3s; 
			animation-iteration-count: infinite; 
			animation-timing-function: ease-in-out;
		} 
		@keyframes floating { 
			0% { transform: translate(0, 0px); } 
			50% { transform: translate(0, 8px); } 
			100% { transform: translate(0, -0px); }  
		} 
		.floating-4 { 
			animation-name: floating; 
			animation-duration: 4s; 
			animation-iteration-count: infinite; 
			animation-timing-function: ease-in-out;
		} 
		@keyframes floating-4 { 
			0% { transform: translate(0, 0px); } 
			50% { transform: translate(0, 8px); } 
			100% { transform: translate(0, -0px); }  
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
						<img src="/assets/svg/logo.svg" alt="Radiology Resident" class="logo-main">
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
                <a class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline" href="/">Home</a>
                
                <!-- Theory Exams Dropdown -->
                <div class="relative" x-data="{ theoryOpen: false }">
                    <button @click="theoryOpen = !theoryOpen" class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline flex items-center">
                        Theory Exams
                        <i class="fas fa-chevron-down ml-1 text-xs" :class="{ 'rotate-180': theoryOpen }"></i>
                    </button>
                    <div x-show="theoryOpen" @click.away="theoryOpen = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 md:left-auto md:right-0">
                        <a href="/theory/notes" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Notes</a>
                        <a href="/theory/previous-year-questions" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Previous Year Questions</a>
                        <a href="/theory/video-tutorials" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Video Tutorial</a>
                    </div>
                </div>
                
                <!-- Practical Exams Dropdown -->
                <div class="relative" x-data="{ practicalOpen: false }">
                    <button @click="practicalOpen = !practicalOpen" class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline flex items-center">
                        Practical Exams
                        <i class="fas fa-chevron-down ml-1 text-xs" :class="{ 'rotate-180': practicalOpen }"></i>
                    </button>
                    <div x-show="practicalOpen" @click.away="practicalOpen = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 md:left-auto md:right-0">
                        <a href="/practical/spotters" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Spotters</a>
                        <a href="/practical/osce" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">OSCE</a>
                        <a href="/practical/exam-cases" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Exam Cases</a>
                        <a href="/practical/table-viva" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Table Viva</a>
                    </div>
                </div>
                
                <!-- Practice Cases & Quiz Dropdown -->
                <div class="relative" x-data="{ practiceOpen: false }">
                    <button @click="practiceOpen = !practiceOpen" class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline flex items-center">
                        Practice & Quiz
                        <i class="fas fa-chevron-down ml-1 text-xs" :class="{ 'rotate-180': practiceOpen }"></i>
                    </button>
                    <div x-show="practiceOpen" @click.away="practiceOpen = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 md:left-auto md:right-0">
                        <a href="/practice/spotters" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Spotters</a>
                        <a href="/practice/osce" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">OSCE</a>
                        <a href="/practice/exam-cases" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Exam Cases</a>
                        <a href="/practice/table-viva" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Table Viva</a>
                    </div>
                </div>
                
                <a class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline" href="/testimonials">Testimonials</a>
                <a class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline" href="/blog">Blog</a>
                <a class="px-4 py-2 mt-2 text-sm bg-transparent rounded-lg md:mt-8 md:ml-4 hover:text-gray-900 focus:outline-none focus:shadow-outline" href="/plans">Plans</a>
                <a class="px-10 py-3 mt-2 text-sm text-center bg-white text-gray-800 rounded-full md:mt-8 md:ml-4" href="/login">Login</a>
                <a class="px-10 py-3 mt-2 text-sm text-center bg-blue-500 text-white rounded-full md:mt-8 md:ml-4" href="/register">Sign Up</a>
            </nav>
        </div>
    </div>

	<!-- Hero Section -->
	<div class="bg-cream">
		<div class="max-w-screen-xl px-8 mx-auto flex flex-col lg:flex-row items-start">
			<!--Left Col-->
			<div class="flex flex-col w-full lg:w-6/12 justify-center lg:pt-24 items-start text-center lg:text-left mb-5 md:mb-0">
				<h1 data-aos="fade-right" data-aos-once="true" class="my-4 text-5xl font-bold leading-tight text-darken">
					<span class="text-blue-500">Master Radiology</span> with Expert-Led Courses
				</h1>
				<p data-aos="fade-down" data-aos-once="true" data-aos-delay="300" class="leading-normal text-2xl mb-8">Radiology Resident is a comprehensive platform that will teach you radiology in an interactive and evidence-based way</p>
				<div data-aos="fade-up" data-aos-once="true" data-aos-delay="700" class="w-full md:flex items-center justify-center lg:justify-start md:space-x-5">
					<button class="lg:mx-0 bg-blue-500 text-white text-xl font-bold rounded-full py-4 px-9 focus:outline-none transform transition hover:scale-110 duration-300 ease-in-out">
						Start Learning
					</button>
					<div class="flex items-center justify-center space-x-3 mt-5 md:mt-0 focus:outline-none transform transition hover:scale-110 duration-300 ease-in-out">
						<button class="bg-white w-14 h-14 rounded-full flex items-center justify-center">
							<svg class="w-5 h-5 ml-2" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M22.5751 12.8097C23.2212 13.1983 23.2212 14.135 22.5751 14.5236L1.51538 27.1891C0.848878 27.5899 5.91205e-07 27.1099 6.25202e-07 26.3321L1.73245e-06 1.00123C1.76645e-06 0.223477 0.848877 -0.256572 1.51538 0.14427L22.5751 12.8097Z" fill="#23BDEE"/>
							</svg>
						</button>
						<span class="cursor-pointer">Watch how it works</span>
					</div>
				</div>
			</div>
			<!--Right Col-->
			<div class="w-full lg:w-6/12 lg:-mt-10 relative" id="hero-image">
				<!-- Banner Image -->
				<div data-aos="fade-up" data-aos-once="true" class="w-10/12 mx-auto 2xl:-mb-20 relative">
					<img src="/assets/svg/Banner.svg" alt="Radiology Education Banner" class="w-full h-auto">
				</div>
				<!-- floating elements -->
				<div data-aos="fade-up" data-aos-delay="300" data-aos-once="true" class="absolute top-20 -left-6 sm:top-32 sm:left-10 md:top-40 md:left-16 lg:-left-0 lg:top-52 floating-4">
					<div class="bg-white bg-opacity-90 rounded-lg p-4 shadow-lg">
						<i class="fas fa-x-ray text-blue-500 text-2xl mb-2"></i>
						<div class="text-xs font-semibold">X-Ray Analysis</div>
					</div>
				</div>
				<!-- CT scan icon -->
				<div data-aos="fade-up" data-aos-delay="400" data-aos-once="true" class="absolute top-20 right-10 sm:right-24 sm:top-28 md:top-36 md:right-32 lg:top-32 lg:right-16 floating">
					<div class="bg-white bg-opacity-90 rounded-lg p-4 shadow-lg">
						<i class="fas fa-brain text-green-500 text-2xl mb-2"></i>
						<div class="text-xs font-semibold">Neuroimaging</div>
					</div>
				</div>
				<!-- MRI icon -->
				<div data-aos="fade-up" data-aos-delay="500" data-aos-once="true" class="absolute bottom-14 -left-4 sm:left-2 sm:bottom-20 lg:bottom-24 lg:-left-4 floating">
					<div class="bg-white bg-opacity-90 rounded-lg p-4 shadow-lg">
						<i class="fas fa-lungs text-red-500 text-2xl mb-2"></i>
						<div class="text-xs font-semibold">Chest Imaging</div>
					</div>
				</div>
				<!-- certificate -->
				<div data-aos="fade-up" data-aos-delay="600" data-aos-once="true" class="absolute bottom-20 md:bottom-48 lg:bottom-52 -right-6 lg:right-8 floating-4">
					<div class="bg-white bg-opacity-90 rounded-lg p-4 shadow-lg">
						<i class="fas fa-certificate text-yellow-500 text-2xl mb-2"></i>
						<div class="text-xs font-semibold">Certified</div>
					</div>
				</div>
			</div>
		</div>
		<div class="text-white -mt-14 sm:-mt-24 lg:-mt-36 z-40 relative">
			<svg class="xl:h-40 xl:w-full" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
				<path d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z" fill="currentColor"></path>
			</svg>
			<div class="bg-white w-full h-20 -mt-px"></div>
		</div>
	</div>

	<!-- container -->
	<div class="container px-4 lg:px-8 mx-auto max-w-screen-xl text-gray-700 overflow-x-hidden">

		<!-- trusted by -->
		<div class="max-w-4xl mx-auto">
			<h1 class="text-center mb-3 text-gray-400 font-medium">Trusted by Leading Medical Institutions Worldwide</h1>
			<div class="grid grid-cols-2 lg:grid-cols-4 gap-8 justify-items-center opacity-60">
				<div class="flex items-center justify-center h-16 bg-gray-100 rounded p-4">
					<span class="font-semibold text-gray-600">Johns Hopkins</span>
				</div>
				<div class="flex items-center justify-center h-16 bg-gray-100 rounded p-4">
					<span class="font-semibold text-gray-600">Mayo Clinic</span>
				</div>
				<div class="flex items-center justify-center h-16 bg-gray-100 rounded p-4">
					<span class="font-semibold text-gray-600">Harvard Medical</span>
				</div>
				<div class="flex items-center justify-center h-16 bg-gray-100 rounded p-4">
					<span class="font-semibold text-gray-600">Cleveland Clinic</span>
				</div>
			</div>
		</div>

		<!-- All-In-One Cloud Software. -->
		<div data-aos="flip-up" class="max-w-xl mx-auto text-center mt-24">
			<h1 class="font-bold text-darken my-3 text-2xl">All-In-One <span class="text-blue-500">Medical Education Platform.</span></h1>
			<p class="leading-relaxed text-gray-500">Radiology Resident combines all the tools needed to master radiology - from basic concepts to advanced imaging techniques.</p>
		</div>

		<!-- Features Cards -->
		<div class="grid md:grid-cols-3 gap-14 md:gap-5 mt-20">
			<div data-aos="fade-up" class="bg-white shadow-xl p-6 text-center rounded-xl">
				<div style="background: #5B72EE;" class="rounded-full w-16 h-16 flex items-center justify-center mx-auto shadow-lg transform -translate-y-12">
					<i class="fas fa-x-ray text-white text-2xl"></i>
				</div>
				<h1 class="font-medium text-xl mb-3 lg:px-14 text-darken">Interactive Case Studies</h1>
				<p class="px-4 text-gray-500">Learn through real patient cases with step-by-step image interpretation and expert commentary</p>
			</div>
			<div data-aos="fade-up" data-aos-delay="150" class="bg-white shadow-xl p-6 text-center rounded-xl">
				<div style="background: #F48C06;" class="rounded-full w-16 h-16 flex items-center justify-center mx-auto shadow-lg transform -translate-y-12">
					<i class="fas fa-graduation-cap text-white text-2xl"></i>
				</div>
				<h1 class="font-medium text-xl mb-3 lg:px-14 text-darken">Expert-Led Learning</h1>
				<p class="px-4 text-gray-500">Learn from board-certified radiologists with years of clinical and teaching experience</p>
			</div>
			<div data-aos="fade-up" data-aos-delay="300" class="bg-white shadow-xl p-6 text-center rounded-xl">
				<div style="background: #29B9E7;" class="rounded-full w-16 h-16 flex items-center justify-center mx-auto shadow-lg transform -translate-y-12">
					<i class="fas fa-certificate text-white text-2xl"></i>
				</div>
				<h1 class="font-medium text-xl mb-3 lg:px-14 text-darken lg:h-14 pt-3">CME Certification</h1>
				<p class="px-4 text-gray-500">Earn continuing medical education credits with accredited courses and assessments</p>
			</div>
		</div>

		<!-- What is Radiology Resident? -->
		<div class="mt-28">
			<div data-aos="flip-down" class="text-center max-w-screen-md mx-auto">
				<h1 class="text-3xl font-bold mb-4">What is <span class="text-blue-500">Radiology Resident?</span></h1>
				<p class="text-gray-500">Radiology Resident is a comprehensive platform that allows medical professionals to enhance their radiology skills through interactive courses, case studies, and expert-led instruction. Master imaging techniques, interpretation skills, and clinical decision-making all in one place.</p>
			</div>
			<div data-aos="fade-up" class="flex flex-col md:flex-row justify-center space-y-5 md:space-y-0 md:space-x-6 lg:space-x-10 mt-7">
				<div class="relative md:w-5/12">
					<div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl h-64 flex items-center justify-center">
						<div class="text-center text-white">
							<i class="fas fa-chalkboard-teacher text-4xl mb-4"></i>
							<h1 class="uppercase font-bold text-sm lg:text-xl mb-3">FOR RESIDENTS</h1>
							<button class="rounded-full text-white border text-xs lg:text-md px-6 py-3 w-full font-medium focus:outline-none transform transition hover:scale-110 duration-300 ease-in-out">Start Learning Today</button>
						</div>
					</div>
				</div>
				<div class="relative md:w-5/12">
					<div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl h-64 flex items-center justify-center">
						<div class="text-center text-white">
							<i class="fas fa-user-md text-4xl mb-4"></i>
							<h1 class="uppercase font-bold text-sm lg:text-xl mb-3">FOR ATTENDINGS</h1>
							<button class="rounded-full text-white bg-white bg-opacity-20 text-xs lg:text-md px-6 py-3 w-full font-medium focus:outline-none transform transition hover:scale-110 duration-300 ease-in-out">Enhance Your Skills</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Features Section -->
		<div class="sm:flex items-center sm:space-x-8 mt-36">
			<div data-aos="fade-right" class="sm:w-1/2 relative">
				<div class="bg-blue-500 rounded-full absolute w-12 h-12 z-0 -left-4 -top-3 animate-pulse"></div>
				<h1 class="font-semibold text-2xl relative z-50 text-darken lg:pr-10">Everything you need to master radiology, <span class="text-blue-500">all in one platform</span></h1>
				<p class="py-5 lg:pr-32">Radiology Resident's comprehensive learning management system helps medical professionals master imaging interpretation, differential diagnosis, and clinical correlation in one secure, evidence-based platform.</p>
				<a href="/courses" class="underline text-blue-500 hover:text-blue-700">Learn More</a>
			</div>
			<div data-aos="fade-left" class="sm:w-1/2 relative mt-10 sm:mt-0">
				<div style="background: #23BDEE;" class="floating w-24 h-24 absolute rounded-lg z-0 -top-3 -left-3"></div>
				<div class="rounded-xl z-40 relative bg-gradient-to-br from-gray-100 to-gray-200 p-8 flex items-center justify-center h-64">
					<i class="fas fa-laptop-medical text-6xl text-blue-500"></i>
				</div>
				<button class="bg-white w-14 h-14 rounded-full flex items-center justify-center absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 focus:outline-none transform transition hover:scale-110 duration-300 ease-in-out z-50">
					<svg class="w-5 h-5 ml-1" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M22.5751 12.8097C23.2212 13.1983 23.2212 14.135 22.5751 14.5236L1.51538 27.1891C0.848878 27.5899 5.91205e-07 27.1099 6.25202e-07 26.3321L1.73245e-06 1.00123C1.76645e-06 0.223477 0.848877 -0.256572 1.51538 0.14427L22.5751 12.8097Z" fill="#23BDEE"/>
					</svg>
				</button>
				<div class="bg-blue-500 w-40 h-40 floating absolute rounded-lg z-10 -bottom-3 -right-3"></div>
			</div>
		</div>

		<!-- Stats Section -->
		<div class="grid md:grid-cols-4 gap-14 md:gap-5 mt-20 text-center">
			<div data-aos="fade-up">
				<div class="text-4xl font-bold text-blue-500 mb-2">
					<?php echo isset($stats['students']) ? $stats['students'] : '2,500'; ?>+
				</div>
				<div class="text-gray-600">Medical Professionals</div>
			</div>
			<div data-aos="fade-up" data-aos-delay="100">
				<div class="text-4xl font-bold text-blue-500 mb-2">
					<?php echo isset($stats['courses']) ? $stats['courses'] : '50'; ?>+
				</div>
				<div class="text-gray-600">Expert Courses</div>
			</div>
			<div data-aos="fade-up" data-aos-delay="200">
				<div class="text-4xl font-bold text-blue-500 mb-2">
					<?php echo isset($stats['instructors']) ? $stats['instructors'] : '25'; ?>+
				</div>
				<div class="text-gray-600">Board-Certified Radiologists</div>
			</div>
			<div data-aos="fade-up" data-aos-delay="300">
				<div class="text-4xl font-bold text-blue-500 mb-2">
					<?php echo isset($stats['hours']) ? $stats['hours'] : '500'; ?>+
				</div>
				<div class="text-gray-600">Hours of Content</div>
			</div>
		</div>

		<!-- Featured Courses -->
		<div class="mt-32">
			<div data-aos="zoom-in" class="text-center">
				<h1 class="text-darken text-3xl font-semibold">Featured Courses</h1>
				<p class="text-gray-500 my-5">Discover our most popular radiology courses designed by expert radiologists</p>
			</div>
			<div data-aos="zoom-in-up" class="grid md:grid-cols-3 gap-8 mt-14">
				<!-- Course 1 -->
				<div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
					<div class="bg-gradient-to-r from-blue-400 to-blue-600 h-48 flex items-center justify-center">
						<i class="fas fa-x-ray text-white text-4xl"></i>
					</div>
					<div class="p-6">
						<span class="bg-blue-100 text-blue-800 font-semibold px-3 py-1 text-sm rounded-full">BASIC</span>
						<h3 class="text-xl font-semibold text-gray-900 mb-2 mt-3">Chest X-Ray Interpretation</h3>
						<p class="text-gray-600 mb-4">Master the fundamentals of chest radiography with systematic approach to interpretation</p>
						<div class="flex items-center justify-between mb-4">
							<span class="text-blue-600 font-semibold">Dr. Sarah Chen, MD</span>
							<div class="flex items-center">
								<i class="fas fa-star text-yellow-400 mr-1"></i>
								<span class="text-gray-600">4.9</span>
							</div>
						</div>
						<div class="flex items-center justify-between">
							<span class="text-2xl font-bold text-blue-600">$149</span>
							<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition duration-300">
								Enroll Now
							</button>
						</div>
					</div>
				</div>

				<!-- Course 2 -->
				<div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
					<div class="bg-gradient-to-r from-green-400 to-green-600 h-48 flex items-center justify-center">
						<i class="fas fa-brain text-white text-4xl"></i>
					</div>
					<div class="p-6">
						<span class="bg-green-100 text-green-800 font-semibold px-3 py-1 text-sm rounded-full">INTERMEDIATE</span>
						<h3 class="text-xl font-semibold text-gray-900 mb-2 mt-3">Neuroimaging Fundamentals</h3>
						<p class="text-gray-600 mb-4">Comprehensive approach to brain MRI and CT interpretation with clinical correlation</p>
						<div class="flex items-center justify-between mb-4">
							<span class="text-blue-600 font-semibold">Dr. Michael Rodriguez, MD</span>
							<div class="flex items-center">
								<i class="fas fa-star text-yellow-400 mr-1"></i>
								<span class="text-gray-600">4.8</span>
							</div>
						</div>
						<div class="flex items-center justify-between">
							<span class="text-2xl font-bold text-blue-600">$249</span>
							<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition duration-300">
								Enroll Now
							</button>
						</div>
					</div>
				</div>

				<!-- Course 3 -->
				<div class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
					<div class="bg-gradient-to-r from-purple-400 to-purple-600 h-48 flex items-center justify-center">
						<i class="fas fa-bone text-white text-4xl"></i>
					</div>
					<div class="p-6">
						<span class="bg-purple-100 text-purple-800 font-semibold px-3 py-1 text-sm rounded-full">ADVANCED</span>
						<h3 class="text-xl font-semibold text-gray-900 mb-2 mt-3">Musculoskeletal Imaging</h3>
						<p class="text-gray-600 mb-4">Advanced techniques in bone and joint imaging with sports medicine focus</p>
						<div class="flex items-center justify-between mb-4">
							<span class="text-blue-600 font-semibold">Dr. Emily Johnson, MD</span>
							<div class="flex items-center">
								<i class="fas fa-star text-yellow-400 mr-1"></i>
								<span class="text-gray-600">4.7</span>
							</div>
						</div>
						<div class="flex items-center justify-between">
							<span class="text-2xl font-bold text-blue-600">$349</span>
							<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition duration-300">
								Enroll Now
							</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="text-center mt-12">
				<button data-aos="flip-up" class="px-8 py-3 border border-blue-500 text-blue-500 font-medium focus:outline-none transform transition hover:scale-110 duration-300 ease-in-out rounded-full">
					View All Courses
				</button>
			</div>
		</div>
	</div>

	<!-- Footer -->
	<footer class="mt-32" style="background-color: rgba(37, 38, 65, 1);">
		<div class="max-w-lg mx-auto">
			<div class="flex py-12 justify-center text-white items-center px-20 sm:px-36">
				<div class="relative">
					<img src="/assets/svg/logo-dark-bg.svg" alt="Radiology Resident" class="logo-footer">
				</div>
				<span class="border-l border-gray-500 text-sm pl-5 py-2 font-semibold">Medical Education Platform</span>
			</div>
			<div class="text-center pb-16 pt-5">
				<label class="text-gray-300 font-semibold">Subscribe to get our Newsletter</label>
				<div class="px-5 sm:px-0 flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0 justify-center mt-3">
					<input type="email" placeholder="Your Email" class="rounded-full py-2 pl-5 bg-transparent border border-gray-400 text-white">
					<button type="submit" class="text-white w-40 sm:w-auto mx-auto sm:mx-0 font-semibold px-5 py-2 rounded-full" style="background: linear-gradient(105.5deg, #2563eb 19.57%, #1d4ed8 78.85%);">Subscribe</button>
				</div>
			</div>
			<div class="flex items-center text-gray-400 text-sm justify-center">
				<a href="/careers" class="pr-3">Careers</a>
				<a href="/privacy" class="border-l border-gray-400 px-3">Privacy</a>
				<a href="/terms" class="border-l border-gray-400 pl-3">Terms & Conditions</a>
			</div>
			<div class="text-center text-white">
				<p class="my-3 text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> Radiology Resident. All rights reserved.</p>
				<div class="py-3 tracking-wide">
					<p class="text-gray-500 text-sm">Advancing radiology education worldwide</p>
				</div>
			</div>
		</div>
	</footer>

	<!-- AOS init -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
    	AOS.init();
    </script>
</body>
</html>
