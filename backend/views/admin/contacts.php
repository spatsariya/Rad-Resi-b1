<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contact List - Admin Panel</title>
	<meta name="description" content="Contact management and communication system">
	
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
			left: 100%;
			top: 50%;
			transform: translateY(-50%);
			margin-left: 0.5rem;
			padding: 0.375rem 0.75rem;
			background-color: #1f2937;
			color: #f9fafb;
			border-radius: 0.375rem;
			font-size: 0.75rem;
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
		
		.sidebar-expanded .tooltip {
			display: none;
		}
		
		/* Card hover effect */
		.card-hover {
			transition: all 0.2s ease-in-out;
		}
		
		.card-hover:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
		}
		
		/* Active submenu indicator */
		.submenu-toggle.active .fa-chevron-down {
			transform: rotate(180deg);
		}
		
		/* Animation for stats */
		.stat-animation {
			animation: countUp 0.8s ease-out;
		}
		
		@keyframes countUp {
			from {
				opacity: 0;
				transform: translateY(10px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}
	</style>
</head>
<body class="bg-gray-100">
	<div class="flex h-screen bg-gray-50">
		<!-- Sidebar -->
		<aside id="sidebar" class="sidebar-expanded sidebar-transition bg-gray-900 text-white flex-shrink-0 overflow-hidden">
			<div class="flex flex-col h-full">
				<!-- Sidebar Header -->
				<div class="flex items-center justify-between p-4 border-b border-gray-800">
					<div class="flex items-center space-x-2">
						<img src="/assets/svg/light-icon.svg" alt="Radiology Resident" class="logo-admin h-8">
						<span class="sidebar-text font-semibold text-lg">RadAdmin</span>
					</div>
					<button id="sidebarToggle" class="text-gray-400 hover:text-white focus:outline-none lg:hidden">
						<i class="fas fa-bars"></i>
					</button>
				</div>

				<!-- Navigation -->
				<nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
					<!-- Dashboard -->
					<a href="/admin" class="menu-item flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
						<i class="fas fa-tachometer-alt w-4 h-4 flex-shrink-0"></i>
						<span class="sidebar-text ml-2">Dashboard</span>
						<div class="tooltip">Dashboard</div>
					</a>
					
					<!-- Users Management -->
					<div class="mt-3">
						<button class="menu-item submenu-toggle flex items-center w-full px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm relative active" data-target="users-submenu">
							<i class="fas fa-users w-4 h-4 flex-shrink-0"></i>
							<span class="sidebar-text ml-2 flex-1 text-left">Users Management</span>
							<i class="sidebar-text fas fa-chevron-down ml-auto transform transition-transform duration-200"></i>
							<div class="tooltip">Users Management</div>
						</button>
						<div id="users-submenu" class="submenu ml-6 mt-1 space-y-1 active">
							<a href="/admin/users" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
								<i class="fas fa-user-friends w-3 h-3 flex-shrink-0"></i>
								<span class="sidebar-text ml-2">All Users</span>
								<div class="tooltip">All Users</div>
							</a>
							<a href="/admin/contacts" class="menu-item flex items-center px-3 py-2 bg-gray-800 text-white rounded-md text-sm relative">
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
								<span class="sidebar-text ml-2">Video Categories</span>
								<div class="tooltip">Video Categories</div>
							</a>
							<a href="/admin/videos" class="menu-item flex items-center px-3 py-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md text-sm relative">
								<i class="fas fa-play-circle w-3 h-3 flex-shrink-0"></i>
								<span class="sidebar-text ml-2">Videos</span>
								<div class="tooltip">Videos</div>
							</a>
						</div>
					</div>
				</nav>

				<!-- User Profile -->
				<div class="p-4 border-t border-gray-800">
					<div class="flex items-center space-x-3">
						<div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
							<span class="text-white text-sm font-medium">
								<?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?>
							</span>
						</div>
						<div class="sidebar-text">
							<p class="text-sm font-medium text-gray-200"><?php echo $_SESSION['user_name'] ?? 'Admin User'; ?></p>
							<p class="text-xs text-gray-400"><?php echo ucfirst($_SESSION['user_role'] ?? 'admin'); ?></p>
						</div>
					</div>
				</div>
			</div>
		</aside>

		<!-- Main Content -->
		<div class="flex-1 flex flex-col overflow-hidden">
			<!-- Header -->
			<header class="bg-white shadow-sm border-b border-gray-200">
				<div class="flex items-center justify-between px-6 py-4">
					<div class="flex items-center space-x-4">
						<button id="sidebarToggleDesktop" class="text-gray-500 hover:text-gray-700 focus:outline-none hidden lg:block">
							<i class="fas fa-bars"></i>
						</button>
						<div>
							<h1 class="text-2xl font-semibold text-gray-900">Contact List</h1>
							<p class="text-sm text-gray-600">Manage contacts and communications with all users</p>
						</div>
					</div>

					<div class="flex items-center space-x-4">
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
				<?php include __DIR__ . '/content/contacts-content.php'; ?>
			</main>
		</div>
	</div>

	<!-- JavaScript -->
	<script>
		// Sidebar functionality
		document.addEventListener('DOMContentLoaded', function() {
			const sidebar = document.getElementById('sidebar');
			const sidebarToggle = document.getElementById('sidebarToggle');
			const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
			const submenuToggles = document.querySelectorAll('.submenu-toggle');

			// Toggle sidebar on mobile
			if (sidebarToggle) {
				sidebarToggle.addEventListener('click', function() {
					sidebar.classList.toggle('hidden');
				});
			}

			// Toggle sidebar on desktop
			if (sidebarToggleDesktop) {
				sidebarToggleDesktop.addEventListener('click', function() {
					if (sidebar.classList.contains('sidebar-expanded')) {
						sidebar.classList.remove('sidebar-expanded');
						sidebar.classList.add('sidebar-collapsed');
					} else {
						sidebar.classList.remove('sidebar-collapsed');
						sidebar.classList.add('sidebar-expanded');
					}
				});
			}

			// Submenu toggles
			submenuToggles.forEach(toggle => {
				toggle.addEventListener('click', function() {
					const targetId = this.getAttribute('data-target');
					const submenu = document.getElementById(targetId);
					
					if (submenu) {
						submenu.classList.toggle('active');
						this.classList.toggle('active');
					}
				});
			});
		});
	</script>
</body>
</html>
