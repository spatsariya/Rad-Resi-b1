<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Admin Dashboard - Radiology Resident'; ?></title>
	<meta name="description" content="<?php echo $description ?? 'Admin dashboard for managing courses, users, and content'; ?>">
	
	<!-- Favicon -->
	<link rel="icon" type="image/svg+xml" href="/assets/svg/dark-icon.svg">
	<link rel="alternate icon" href="/assets/svg/dark-icon.svg">
	
	<!-- Tailwind CSS -->
	<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
	<!-- Custom Logo Styles -->
	<link rel="stylesheet" href="/assets/css/logo-styles.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<!-- Inter font (TailAdmin style) -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	
	<style>
		body {
			font-family: 'Inter', sans-serif;
		}
		
		/* Custom scrollbar */
		::-webkit-scrollbar {
			width: 6px;
		}
		::-webkit-scrollbar-track {
			background: #f1f5f9;
		}
		::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 3px;
		}
		::-webkit-scrollbar-thumb:hover {
			background: #94a3b8;
		}
		
		/* Sidebar animation */
		.sidebar-transition {
			transition: all 0.3s ease-in-out;
		}
		
		/* Collapsed sidebar styles */
		.sidebar-collapsed {
			width: 4rem; /* 64px */
		}
		
		.sidebar-expanded {
			width: 18rem; /* 288px */
		}
		
		/* Hide text when collapsed */
		.sidebar-collapsed .sidebar-text {
			opacity: 0;
			transform: translateX(-10px);
			transition: all 0.2s ease-in-out;
		}
		
		.sidebar-expanded .sidebar-text {
			opacity: 1;
			transform: translateX(0);
			transition: all 0.3s ease-in-out 0.1s;
		}
		
		/* Submenu styles */
		.submenu {
			max-height: 0;
			overflow: hidden;
			transition: max-height 0.3s ease-in-out;
		}
		
		.submenu.active {
			max-height: 500px;
		}
		
		/* Logo scaling */
		.sidebar-collapsed .logo-admin {
			height: 2rem;
		}
		
		.sidebar-expanded .logo-admin {
			height: 2rem;
		}
		
		/* Icon centering when collapsed */
		.sidebar-collapsed .menu-item {
			justify-content: center;
		}
		
		.sidebar-expanded .menu-item {
			justify-content: flex-start;
		}
		
		/* Tooltip for collapsed state */
		.tooltip {
			position: absolute;
			left: 4.5rem;
			background: #1f2937;
			color: white;
			padding: 0.5rem 0.75rem;
			border-radius: 0.375rem;
			font-size: 0.875rem;
			white-space: nowrap;
			opacity: 0;
			visibility: hidden;
			transition: all 0.2s ease-in-out;
			z-index: 1000;
		}
		
		.sidebar-collapsed .menu-item:hover .tooltip {
			opacity: 1;
			visibility: visible;
		}
		
		/* Card hover effect */
		.card-hover {
			transition: all 0.3s ease;
		}
		.card-hover:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
		}
	</style>
</head>
<body class="bg-gray-100">
	<div class="flex h-screen bg-gray-100">
		<!-- Sidebar -->
		<div id="sidebar" class="sidebar-transition sidebar-expanded bg-gray-900 text-white min-h-screen p-4 overflow-y-auto">
			<div class="flex items-center justify-between mb-6">
				<div class="flex items-center overflow-hidden">
					<img src="/assets/svg/logo-dark-bg.svg" alt="Radiology Resident" class="logo-admin sidebar-transition">
				</div>
				<!-- Collapse Toggle Button -->
				<button id="collapseToggle" class="text-gray-400 hover:text-white focus:outline-none sidebar-transition">
					<i id="collapseIcon" class="fas fa-chevron-left text-lg"></i>
				</button>
			</div>
			
			<!-- Navigation Menu -->
			<nav class="space-y-1">
				<!-- Dashboard -->
				<a href="/admin" class="menu-item flex items-center px-3 py-2 text-gray-100 bg-blue-600 rounded-md text-sm relative">
					<i class="fas fa-tachometer-alt w-4 h-4 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Dashboard</span>
					<div class="tooltip">Dashboard</div>
				</a>
				
				<!-- Users Management -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="users-submenu">
						<i class="fas fa-users w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">Users Management</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">Users Management</div>
					</button>
					<div id="users-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/users" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-user-friends w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">All Users</span>
							<div class="tooltip">All Users</div>
						</a>
						<a href="/admin/contacts" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-address-book w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Contact List</span>
							<div class="tooltip">Contact List</div>
						</a>
					</div>
				</div>
				
				<!-- Theory Exams -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="theory-submenu">
						<i class="fas fa-book-open w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">Theory Exams</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">Theory Exams</div>
					</button>
					<div id="theory-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/notes-chapters" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-bookmark w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Notes Chapters</span>
							<div class="tooltip">Notes Chapters</div>
						</a>
						<a href="/admin/notes" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-sticky-note w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Notes</span>
							<div class="tooltip">Notes</div>
						</a>
						<a href="/admin/prev-year-questions" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-question-circle w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Prev Year Questions</span>
							<div class="tooltip">Prev Year Questions</div>
						</a>
					</div>
				</div>
				
				<!-- Video Tutorial -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="video-submenu">
						<i class="fas fa-video w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">Video Tutorial</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">Video Tutorial</div>
					</button>
					<div id="video-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/video-categories" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-folder w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Video Category</span>
							<div class="tooltip">Video Category</div>
						</a>
						<a href="/admin/videos" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-play-circle w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Videos</span>
							<div class="tooltip">Videos</div>
						</a>
					</div>
				</div>
				
				<!-- Spotters -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="spotters-submenu">
						<i class="fas fa-search w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">Spotters</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">Spotters</div>
					</button>
					<div id="spotters-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/spotter-categories" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-tags w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Spotter Category</span>
							<div class="tooltip">Spotter Category</div>
						</a>
						<a href="/admin/spotters" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-crosshairs w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Spotters</span>
							<div class="tooltip">Spotters</div>
						</a>
						<a href="/admin/osce-categories" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-layer-group w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">OSCE Category</span>
							<div class="tooltip">OSCE Category</div>
						</a>
						<a href="/admin/osce" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-stethoscope w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">OSCE</span>
							<div class="tooltip">OSCE</div>
						</a>
					</div>
				</div>
				
				<!-- Rapid FRS -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="rapidfrs-submenu">
						<i class="fas fa-bolt w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">Rapid FRS</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">Rapid FRS</div>
					</button>
					<div id="rapidfrs-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/rapid-frs-categories" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-list w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Rapid FRS Category</span>
							<div class="tooltip">Rapid FRS Category</div>
						</a>
						<a href="/admin/rapid-frs" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-fast-forward w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Rapid FRS</span>
							<div class="tooltip">Rapid FRS</div>
						</a>
					</div>
				</div>
				
				<!-- Table Viva -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="tableviva-submenu">
						<i class="fas fa-table w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">Table Viva</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">Table Viva</div>
					</button>
					<div id="tableviva-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/table-viva-categories" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-th-list w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Table Viva Category</span>
							<div class="tooltip">Table Viva Category</div>
						</a>
						<a href="/admin/table-viva" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-comments w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Table Viva</span>
							<div class="tooltip">Table Viva</div>
						</a>
					</div>
				</div>
				
				<!-- Long Cases -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="longcases-submenu">
						<i class="fas fa-file-medical w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">Long Cases</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">Long Cases</div>
					</button>
					<div id="longcases-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/longcases-categories" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-folder-open w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Longcases Category</span>
							<div class="tooltip">Longcases Category</div>
						</a>
						<a href="/admin/long-cases" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-file-alt w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Long Cases</span>
							<div class="tooltip">Long Cases</div>
						</a>
					</div>
				</div>
				
				<!-- Short Cases -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="shortcases-submenu">
						<i class="fas fa-file-medical-alt w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">Short Cases</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">Short Cases</div>
					</button>
					<div id="shortcases-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/shortcases-categories" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-folder w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Shortcases Category</span>
							<div class="tooltip">Shortcases Category</div>
						</a>
						<a href="/admin/short-cases" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-file w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Short Cases</span>
							<div class="tooltip">Short Cases</div>
						</a>
					</div>
				</div>
				
				<!-- FRCR -->
				<div class="mt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="frcr-submenu">
						<i class="fas fa-award w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">FRCR</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">FRCR</div>
					</button>
					<div id="frcr-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/frcr-categories" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-tags w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Category</span>
							<div class="tooltip">Category</div>
						</a>
						<a href="/admin/frcr-subjects" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-book w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Subject</span>
							<div class="tooltip">Subject</div>
						</a>
						<a href="/admin/frcr-quiz" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-question-circle w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Quiz</span>
							<div class="tooltip">Quiz</div>
						</a>
						<a href="/admin/frcr-questions" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-question w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Question</span>
							<div class="tooltip">Question</div>
						</a>
					</div>
				</div>
				
				<!-- Content Management -->
				<div class="mt-4 border-t border-gray-700 pt-3">
					<!-- Pages -->
					<a href="/admin/pages" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 relative">
						<i class="fas fa-file-alt w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">Pages</span>
						<div class="tooltip">Pages</div>
					</a>
					
					<!-- Banner -->
					<a href="/admin/banners" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 relative">
						<i class="fas fa-image w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">Banner</span>
						<div class="tooltip">Banner</div>
					</a>
					
					<!-- Blogs -->
					<a href="/admin/blogs" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 relative">
						<i class="fas fa-blog w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">All Blogs</span>
						<div class="tooltip">All Blogs</div>
					</a>
					
					<!-- Testimonials -->
					<a href="/admin/testimonials" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 relative">
						<i class="fas fa-quote-left w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">All Testimonial</span>
						<div class="tooltip">All Testimonial</div>
					</a>
					
					<!-- Plan -->
					<a href="/admin/plans" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 relative">
						<i class="fas fa-credit-card w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">Plan</span>
						<div class="tooltip">Plan</div>
					</a>
					
					<!-- Subscription -->
					<a href="/admin/subscriptions" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 relative">
						<i class="fas fa-user-check w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">Subscription</span>
						<div class="tooltip">Subscription</div>
					</a>
					
					<!-- FAQ -->
					<a href="/admin/faq" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 relative">
						<i class="fas fa-question-circle w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">FAQ</span>
						<div class="tooltip">FAQ</div>
					</a>
					
					<!-- Report -->
					<a href="/admin/reports" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 relative">
						<i class="fas fa-chart-line w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">Notifications</span>
						<div class="tooltip">Notifications</div>
					</a>
				</div>
				
				<!-- General Settings -->
				<div class="mt-3 border-t border-gray-700 pt-3">
					<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative" data-target="settings-submenu">
						<i class="fas fa-cogs w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2 flex-1 text-left">General Settings</span>
						<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
						<div class="tooltip">General Settings</div>
					</button>
					<div id="settings-submenu" class="submenu ml-6 mt-1 space-y-1">
						<a href="/admin/global-settings" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-globe w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Global Settings</span>
							<div class="tooltip">Global Settings</div>
						</a>
						<a href="/admin/logo-favicon" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-icons w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Logo & Favicon</span>
							<div class="tooltip">Logo & Favicon</div>
						</a>
						<a href="/admin/settings" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
							<i class="fas fa-cog w-3 h-3 flex-shrink-0"></i>
							<span class="sidebar-text ml-2">Settings</span>
							<div class="tooltip">Settings</div>
						</a>
					</div>
				</div>
				
				<div class="border-t border-gray-700 my-4"></div>
				
				<!-- Back to Site -->
				<a href="/" class="menu-item flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors relative">
					<i class="fas fa-external-link-alt w-5 h-5 flex-shrink-0"></i>
					<span class="sidebar-text ml-3">Back to Site</span>
					<div class="tooltip">Back to Site</div>
				</a>
				
				<!-- Logout -->
				<a href="/auth/logout" class="menu-item flex items-center px-4 py-3 text-gray-300 hover:bg-red-600 hover:text-white rounded-lg transition-colors relative">
					<i class="fas fa-sign-out-alt w-5 h-5 flex-shrink-0"></i>
					<span class="sidebar-text ml-3">Logout</span>
					<div class="tooltip">Logout</div>
				</a>
			</nav>
		</div>

		<!-- Main Content -->
		<div class="flex-1 flex flex-col overflow-hidden">
			<!-- Header -->
			<header class="bg-white shadow-sm border-b border-gray-200">
				<div class="flex items-center justify-between px-6 py-4">
					<div class="flex items-center">
						<button id="menuToggle" class="lg:hidden mr-3 text-gray-500 hover:text-gray-700">
							<i class="fas fa-bars text-xl"></i>
						</button>
						<h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
					</div>
					
					<div class="flex items-center space-x-4">
						<!-- Notifications -->
						<button class="relative text-gray-500 hover:text-gray-700">
							<i class="fas fa-bell text-xl"></i>
							<span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
						</button>
						
						<!-- User Profile -->
						<div class="flex items-center space-x-3">
							<div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
								<span class="text-white text-sm font-medium">
									<?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?>
								</span>
							</div>
							<div class="hidden md:block">
								<p class="text-sm font-medium text-gray-700"><?php echo $_SESSION['user_name'] ?? 'Admin User'; ?></p>
								<p class="text-xs text-gray-500"><?php echo ucfirst($_SESSION['user_role'] ?? 'admin'); ?></p>
							</div>
						</div>
					</div>
				</div>
			</header>

			<!-- Main Content Area -->
			<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
				<!-- Stats Cards -->
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
					<!-- Total Students -->
					<div class="bg-white rounded-lg shadow-sm p-6 card-hover">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
									<i class="fas fa-user-graduate text-blue-600 text-xl"></i>
								</div>
							</div>
							<div class="ml-4">
								<p class="text-sm font-medium text-gray-500">Total Students</p>
								<p class="text-2xl font-semibold text-gray-900"><?php echo number_format($stats['students'] ?? 0); ?></p>
							</div>
						</div>
					</div>

					<!-- Total Courses -->
					<div class="bg-white rounded-lg shadow-sm p-6 card-hover">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
									<i class="fas fa-book text-green-600 text-xl"></i>
								</div>
							</div>
							<div class="ml-4">
								<p class="text-sm font-medium text-gray-500">Total Courses</p>
								<p class="text-2xl font-semibold text-gray-900"><?php echo number_format($stats['courses'] ?? 0); ?></p>
							</div>
						</div>
					</div>

					<!-- Instructors -->
					<div class="bg-white rounded-lg shadow-sm p-6 card-hover">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
									<i class="fas fa-chalkboard-teacher text-purple-600 text-xl"></i>
								</div>
							</div>
							<div class="ml-4">
								<p class="text-sm font-medium text-gray-500">Instructors</p>
								<p class="text-2xl font-semibold text-gray-900"><?php echo number_format($stats['instructors'] ?? 0); ?></p>
							</div>
						</div>
					</div>

					<!-- Monthly Enrollments -->
					<div class="bg-white rounded-lg shadow-sm p-6 card-hover">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
									<i class="fas fa-chart-line text-yellow-600 text-xl"></i>
								</div>
							</div>
							<div class="ml-4">
								<p class="text-sm font-medium text-gray-500">This Month</p>
								<p class="text-2xl font-semibold text-gray-900"><?php echo number_format($stats['monthly_enrollments'] ?? 0); ?></p>
							</div>
						</div>
					</div>
				</div>

				<!-- Charts and Tables Row -->
				<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
					<!-- Recent Enrollments -->
					<div class="bg-white rounded-lg shadow-sm">
						<div class="px-6 py-4 border-b border-gray-200">
							<h3 class="text-lg font-medium text-gray-900">Recent Enrollments</h3>
						</div>
						<div class="p-6">
							<?php if (!empty($recent_enrollments)): ?>
								<div class="space-y-4">
									<?php foreach ($recent_enrollments as $enrollment): ?>
										<div class="flex items-center space-x-4">
											<div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
												<span class="text-blue-600 text-sm font-medium">
													<?php echo strtoupper(substr($enrollment['first_name'], 0, 1)); ?>
												</span>
											</div>
											<div class="flex-1 min-w-0">
												<p class="text-sm font-medium text-gray-900 truncate">
													<?php echo htmlspecialchars($enrollment['first_name'] . ' ' . $enrollment['last_name']); ?>
												</p>
												<p class="text-sm text-gray-500 truncate">
													<?php echo htmlspecialchars($enrollment['course_title']); ?>
												</p>
											</div>
											<div class="text-sm text-gray-500">
												<?php echo date('M j', strtotime($enrollment['enrolled_at'])); ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php else: ?>
								<div class="text-center py-8 text-gray-500">
									<i class="fas fa-users text-3xl mb-3"></i>
									<p>No recent enrollments</p>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Popular Courses -->
					<div class="bg-white rounded-lg shadow-sm">
						<div class="px-6 py-4 border-b border-gray-200">
							<h3 class="text-lg font-medium text-gray-900">Popular Courses</h3>
						</div>
						<div class="p-6">
							<?php if (!empty($popular_courses)): ?>
								<div class="space-y-4">
									<?php foreach ($popular_courses as $course): ?>
										<div class="flex items-center justify-between">
											<div class="flex-1 min-w-0">
												<p class="text-sm font-medium text-gray-900 truncate">
													<?php echo htmlspecialchars($course['title']); ?>
												</p>
												<p class="text-sm text-gray-500">
													Dr. <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
												</p>
											</div>
											<div class="text-right">
												<p class="text-sm font-medium text-gray-900">
													<?php echo number_format($course['total_enrollments']); ?> students
												</p>
												<div class="flex items-center">
													<i class="fas fa-star text-yellow-400 text-xs mr-1"></i>
													<span class="text-sm text-gray-500"><?php echo $course['rating']; ?></span>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php else: ?>
								<div class="text-center py-8 text-gray-500">
									<i class="fas fa-book text-3xl mb-3"></i>
									<p>No courses available</p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Quick Actions -->
				<div class="bg-white rounded-lg shadow-sm">
					<div class="px-6 py-4 border-b border-gray-200">
						<h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
					</div>
					<div class="p-6">
						<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
							<a href="/admin/courses/create" class="flex items-center justify-center px-4 py-6 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
								<div class="text-center">
									<i class="fas fa-plus-circle text-blue-600 text-2xl mb-2"></i>
									<p class="text-sm font-medium text-blue-600">Create Course</p>
								</div>
							</a>
							
							<a href="/admin/users" class="flex items-center justify-center px-4 py-6 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
								<div class="text-center">
									<i class="fas fa-user-plus text-green-600 text-2xl mb-2"></i>
									<p class="text-sm font-medium text-green-600">Manage Users</p>
								</div>
							</a>
							
							<a href="/admin/analytics" class="flex items-center justify-center px-4 py-6 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
								<div class="text-center">
									<i class="fas fa-chart-bar text-purple-600 text-2xl mb-2"></i>
									<p class="text-sm font-medium text-purple-600">View Analytics</p>
								</div>
							</a>
						</div>
					</div>
				</div>
			</main>
		</div>
	</div>

	<!-- Mobile sidebar overlay -->
	<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

	<script>
		// Sidebar collapse functionality
		const collapseToggle = document.getElementById('collapseToggle');
		const sidebar = document.getElementById('sidebar');
		const collapseIcon = document.getElementById('collapseIcon');
		
		// Initialize sidebar state from localStorage or default to expanded
		let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
		
		function updateSidebarState() {
			if (isCollapsed) {
				sidebar.classList.remove('sidebar-expanded');
				sidebar.classList.add('sidebar-collapsed');
				collapseIcon.classList.remove('fa-chevron-left');
				collapseIcon.classList.add('fa-chevron-right');
			} else {
				sidebar.classList.remove('sidebar-collapsed');
				sidebar.classList.add('sidebar-expanded');
				collapseIcon.classList.remove('fa-chevron-right');
				collapseIcon.classList.add('fa-chevron-left');
			}
			localStorage.setItem('sidebarCollapsed', isCollapsed);
		}
		
		// Toggle sidebar collapse
		collapseToggle.addEventListener('click', function() {
			isCollapsed = !isCollapsed;
			updateSidebarState();
		});
		
		// Initialize sidebar state on page load
		updateSidebarState();
		
		// Submenu toggle functionality
		const submenuToggles = document.querySelectorAll('.submenu-toggle');
		submenuToggles.forEach(toggle => {
			toggle.addEventListener('click', function(e) {
				e.preventDefault();
				const targetId = this.getAttribute('data-target');
				const submenu = document.getElementById(targetId);
				const chevron = this.querySelector('.fa-chevron-down');
				
				if (submenu) {
					// Toggle submenu visibility
					if (submenu.style.maxHeight && submenu.style.maxHeight !== '0px') {
						submenu.style.maxHeight = '0px';
						chevron.style.transform = 'rotate(0deg)';
					} else {
						submenu.style.maxHeight = submenu.scrollHeight + 'px';
						chevron.style.transform = 'rotate(180deg)';
					}
				}
			});
		});
		
		// Mobile sidebar functionality
		const menuToggle = document.getElementById('menuToggle');
		const sidebarOverlay = document.getElementById('sidebarOverlay');
		
		function toggleMobileSidebar() {
			sidebar.classList.toggle('-translate-x-full');
			sidebarOverlay.classList.toggle('hidden');
		}
		
		if (menuToggle) {
			menuToggle.addEventListener('click', toggleMobileSidebar);
		}
		
		if (sidebarOverlay) {
			sidebarOverlay.addEventListener('click', toggleMobileSidebar);
		}
		
		// Close mobile sidebar on window resize
		window.addEventListener('resize', function() {
			if (window.innerWidth >= 1024) {
				sidebar.classList.remove('-translate-x-full');
				if (sidebarOverlay) {
					sidebarOverlay.classList.add('hidden');
				}
			}
		});
		
		// Initialize mobile sidebar position
		if (window.innerWidth < 1024) {
			sidebar.classList.add('-translate-x-full');
		}
		
		// Add smooth scrolling to navigation
		const navLinks = document.querySelectorAll('nav a');
		navLinks.forEach(link => {
			link.addEventListener('click', function(e) {
				// Add a small loading state
				this.style.opacity = '0.7';
				setTimeout(() => {
					this.style.opacity = '1';
				}, 200);
			});
		});
		
		// Highlight current page in navigation
		const currentPath = window.location.pathname;
		navLinks.forEach(link => {
			if (link.getAttribute('href') === currentPath) {
				link.classList.remove('text-gray-300', 'hover:bg-gray-800');
				link.classList.add('bg-blue-600', 'text-white');
			}
		});
	</script>
</body>
</html>
