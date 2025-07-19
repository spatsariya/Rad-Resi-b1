<?php
// Get the current path to highlight active menu item
$currentPath = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Admin - Radiology Resident'; ?></title>
	<meta name="description" content="<?php echo $description ?? 'Admin panel for managing content'; ?>">
	
	<!-- Tailwind CSS -->
	<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
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
	</style>
</head>
<body class="bg-gray-100">
	<div class="flex h-screen bg-gray-100">
		<!-- Sidebar -->
		<div id="sidebar" class="sidebar-transition bg-gray-900 text-white w-72 min-h-screen p-4 overflow-y-auto">
			<div class="flex items-center justify-between mb-6">
				<div class="flex items-center">
					<i class="fas fa-stethoscope text-blue-400 text-xl mr-2"></i>
					<h1 class="text-lg font-semibold">Radiology Resident</h1>
				</div>
				<button id="sidebarToggle" class="lg:hidden text-gray-400 hover:text-white">
					<i class="fas fa-times text-xl"></i>
				</button>
			</div>
			
			<!-- Navigation Menu -->
			<nav class="space-y-1">
				<!-- Dashboard -->
				<a href="/admin" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md text-sm <?php echo $currentPath === '/admin' ? 'bg-blue-600 text-white' : ''; ?>">
					<i class="fas fa-tachometer-alt w-4 h-4 mr-2"></i>
					<span>Dashboard</span>
				</a>
				
				<!-- Users Management -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-users w-4 h-4 mr-2"></i>
						<span>Users Management</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/users" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/users' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-user-friends w-3 h-3 mr-2"></i>
							<span>All Users</span>
						</a>
						<a href="/admin/contacts" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/contacts' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-address-book w-3 h-3 mr-2"></i>
							<span>Contact List</span>
						</a>
					</div>
				</div>
				
				<!-- Theory Exams -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-book-open w-4 h-4 mr-2"></i>
						<span>Theory Exams</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/notes-chapters" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/notes-chapters' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-bookmark w-3 h-3 mr-2"></i>
							<span>Notes Chapters</span>
						</a>
						<a href="/admin/notes" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/notes' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-sticky-note w-3 h-3 mr-2"></i>
							<span>Notes</span>
						</a>
						<a href="/admin/prev-year-questions" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/prev-year-questions' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-question-circle w-3 h-3 mr-2"></i>
							<span>Prev Year Questions</span>
						</a>
					</div>
				</div>
				
				<!-- Video Tutorial -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-video w-4 h-4 mr-2"></i>
						<span>Video Tutorial</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/video-categories" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/video-categories' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-folder w-3 h-3 mr-2"></i>
							<span>Video Category</span>
						</a>
						<a href="/admin/videos" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/videos' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-play-circle w-3 h-3 mr-2"></i>
							<span>Videos</span>
						</a>
					</div>
				</div>
				
				<!-- Spotters -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-search w-4 h-4 mr-2"></i>
						<span>Spotters</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/spotter-categories" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/spotter-categories' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-tags w-3 h-3 mr-2"></i>
							<span>Spotter Category</span>
						</a>
						<a href="/admin/spotters" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/spotters' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-crosshairs w-3 h-3 mr-2"></i>
							<span>Spotters</span>
						</a>
						<a href="/admin/osce-categories" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/osce-categories' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-layer-group w-3 h-3 mr-2"></i>
							<span>OSCE Category</span>
						</a>
						<a href="/admin/osce" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/osce' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-stethoscope w-3 h-3 mr-2"></i>
							<span>OSCE</span>
						</a>
					</div>
				</div>
				
				<!-- Rapid FRS -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-bolt w-4 h-4 mr-2"></i>
						<span>Rapid FRS</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/rapid-frs-categories" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/rapid-frs-categories' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-list w-3 h-3 mr-2"></i>
							<span>Rapid FRS Category</span>
						</a>
						<a href="/admin/rapid-frs" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/rapid-frs' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-fast-forward w-3 h-3 mr-2"></i>
							<span>Rapid FRS</span>
						</a>
					</div>
				</div>
				
				<!-- Table Viva -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-table w-4 h-4 mr-2"></i>
						<span>Table Viva</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/table-viva-categories" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/table-viva-categories' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-th-list w-3 h-3 mr-2"></i>
							<span>Table Viva Category</span>
						</a>
						<a href="/admin/table-viva" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/table-viva' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-comments w-3 h-3 mr-2"></i>
							<span>Table Viva</span>
						</a>
					</div>
				</div>
				
				<!-- Long Cases -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-file-medical w-4 h-4 mr-2"></i>
						<span>Long Cases</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/longcases-categories" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/longcases-categories' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-folder-open w-3 h-3 mr-2"></i>
							<span>Longcases Category</span>
						</a>
						<a href="/admin/long-cases" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/long-cases' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-file-alt w-3 h-3 mr-2"></i>
							<span>Long Cases</span>
						</a>
					</div>
				</div>
				
				<!-- Short Cases -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-file-medical-alt w-4 h-4 mr-2"></i>
						<span>Short Cases</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/shortcases-categories" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/shortcases-categories' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-folder w-3 h-3 mr-2"></i>
							<span>Shortcases Category</span>
						</a>
						<a href="/admin/short-cases" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/short-cases' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-file w-3 h-3 mr-2"></i>
							<span>Short Cases</span>
						</a>
					</div>
				</div>
				
				<!-- FRCR -->
				<div class="mt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-award w-4 h-4 mr-2"></i>
						<span>FRCR</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/frcr-categories" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/frcr-categories' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-th w-3 h-3 mr-2"></i>
							<span>Category</span>
						</a>
						<a href="/admin/frcr-subjects" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/frcr-subjects' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-book w-3 h-3 mr-2"></i>
							<span>Subject</span>
						</a>
						<a href="/admin/frcr-quiz" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/frcr-quiz' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-question-circle w-3 h-3 mr-2"></i>
							<span>Quiz</span>
						</a>
						<a href="/admin/frcr-questions" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/frcr-questions' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-question w-3 h-3 mr-2"></i>
							<span>Question</span>
						</a>
					</div>
				</div>
				
				<!-- Content Management -->
				<div class="mt-4 border-t border-gray-700 pt-3">
					<!-- Pages -->
					<a href="/admin/pages" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 <?php echo $currentPath === '/admin/pages' ? 'bg-blue-600 text-white' : ''; ?>">
						<i class="fas fa-file-alt w-4 h-4 mr-2"></i>
						<span>Pages</span>
					</a>
					
					<!-- Banner -->
					<a href="/admin/banners" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 <?php echo $currentPath === '/admin/banners' ? 'bg-blue-600 text-white' : ''; ?>">
						<i class="fas fa-image w-4 h-4 mr-2"></i>
						<span>Banner</span>
					</a>
					
					<!-- Blogs -->
					<a href="/admin/blogs" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 <?php echo $currentPath === '/admin/blogs' ? 'bg-blue-600 text-white' : ''; ?>">
						<i class="fas fa-blog w-4 h-4 mr-2"></i>
						<span>All Blogs</span>
					</a>
					
					<!-- Testimonials -->
					<a href="/admin/testimonials" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 <?php echo $currentPath === '/admin/testimonials' ? 'bg-blue-600 text-white' : ''; ?>">
						<i class="fas fa-quote-left w-4 h-4 mr-2"></i>
						<span>All Testimonial</span>
					</a>
					
					<!-- Plan -->
					<a href="/admin/plans" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 <?php echo $currentPath === '/admin/plans' ? 'bg-blue-600 text-white' : ''; ?>">
						<i class="fas fa-credit-card w-4 h-4 mr-2"></i>
						<span>Plan</span>
					</a>
					
					<!-- Subscription -->
					<a href="/admin/subscriptions" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 <?php echo $currentPath === '/admin/subscriptions' ? 'bg-blue-600 text-white' : ''; ?>">
						<i class="fas fa-user-check w-4 h-4 mr-2"></i>
						<span>Subscription</span>
					</a>
					
					<!-- FAQ -->
					<a href="/admin/faq" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 <?php echo $currentPath === '/admin/faq' ? 'bg-blue-600 text-white' : ''; ?>">
						<i class="fas fa-question-circle w-4 h-4 mr-2"></i>
						<span>FAQ</span>
					</a>
					
					<!-- Report -->
					<a href="/admin/reports" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm mb-1 <?php echo $currentPath === '/admin/reports' ? 'bg-blue-600 text-white' : ''; ?>">
						<i class="fas fa-chart-line w-4 h-4 mr-2"></i>
						<span>Notifications</span>
					</a>
				</div>
				
				<!-- General Settings -->
				<div class="mt-3 border-t border-gray-700 pt-3">
					<div class="flex items-center px-3 py-2 text-gray-200 text-xs font-semibold uppercase tracking-wider">
						<i class="fas fa-cogs w-4 h-4 mr-2"></i>
						<span>General Settings</span>
					</div>
					<div class="ml-4 space-y-1">
						<a href="/admin/global-settings" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/global-settings' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-globe w-3 h-3 mr-2"></i>
							<span>Global Settings</span>
						</a>
						<a href="/admin/logo-favicon" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/logo-favicon' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-icons w-3 h-3 mr-2"></i>
							<span>Logo & Favicon</span>
						</a>
						<a href="/admin/settings" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm <?php echo $currentPath === '/admin/settings' ? 'bg-blue-600 text-white' : ''; ?>">
							<i class="fas fa-cog w-3 h-3 mr-2"></i>
							<span>Settings</span>
						</a>
					</div>
				</div>
				
				<div class="border-t border-gray-700 my-4"></div>
				
				<!-- Back to Site -->
				<a href="/" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition-colors text-sm">
					<i class="fas fa-external-link-alt w-4 h-4 mr-2"></i>
					<span>Back to Site</span>
				</a>
				
				<!-- Logout -->
				<form method="POST" action="/auth/logout" class="mt-2">
					<button type="submit" class="w-full flex items-center px-3 py-2 text-gray-300 hover:bg-red-600 hover:text-white rounded-md transition-colors text-sm">
						<i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>
						<span>Logout</span>
					</button>
				</form>
			</nav>
		</div>
		
		<!-- Main Content -->
		<div class="flex-1 flex flex-col overflow-hidden">
			<!-- Header -->
			<header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
				<div class="flex items-center justify-between">
					<div class="flex items-center">
						<button id="mobileMenuToggle" class="lg:hidden mr-3 text-gray-600 hover:text-gray-900">
							<i class="fas fa-bars text-xl"></i>
						</button>
						<div>
							<h1 class="text-2xl font-semibold text-gray-900"><?php echo $page_title ?? 'Admin Dashboard'; ?></h1>
							<p class="text-sm text-gray-600"><?php echo $page_description ?? 'Manage your platform'; ?></p>
						</div>
					</div>
					
					<div class="flex items-center space-x-4">
						<div class="relative">
							<button class="flex items-center text-sm text-gray-700 hover:text-gray-900">
								<img class="w-8 h-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name'] ?? 'Admin'); ?>&background=3b82f6&color=fff" alt="Avatar">
								<span class="hidden md:block"><?php echo $_SESSION['user_name'] ?? 'Admin User'; ?></span>
								<i class="fas fa-chevron-down ml-1"></i>
							</button>
						</div>
					</div>
				</div>
			</header>
			
			<!-- Main Content Area -->
			<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
				<div class="max-w-7xl mx-auto">
					<!-- Content Card -->
					<div class="bg-white rounded-lg shadow-sm p-6">
						<div class="text-center py-12">
							<i class="fas fa-tools text-6xl text-gray-300 mb-4"></i>
							<h2 class="text-2xl font-semibold text-gray-900 mb-2"><?php echo $page_title ?? 'Page Under Development'; ?></h2>
							<p class="text-gray-600 mb-6"><?php echo $page_description ?? 'This page is currently being developed and will be available soon.'; ?></p>
							
							<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-md mx-auto">
								<div class="flex items-center">
									<i class="fas fa-info-circle text-blue-500 mr-2"></i>
									<p class="text-blue-700 text-sm">This section will include comprehensive management tools for this feature.</p>
								</div>
							</div>
							
							<div class="mt-6">
								<a href="/admin" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
									<i class="fas fa-arrow-left mr-2"></i>
									Back to Dashboard
								</a>
							</div>
						</div>
					</div>
				</div>
			</main>
		</div>
	</div>
	
	<!-- Mobile sidebar overlay -->
	<div id="sidebarOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-40 lg:hidden hidden"></div>
	
	<script>
		// Mobile menu toggle
		const mobileMenuToggle = document.getElementById('mobileMenuToggle');
		const sidebar = document.getElementById('sidebar');
		const sidebarOverlay = document.getElementById('sidebarOverlay');
		const sidebarToggle = document.getElementById('sidebarToggle');
		
		mobileMenuToggle.addEventListener('click', function() {
			sidebar.classList.toggle('-translate-x-full');
			sidebarOverlay.classList.toggle('hidden');
		});
		
		sidebarToggle.addEventListener('click', function() {
			sidebar.classList.add('-translate-x-full');
			sidebarOverlay.classList.add('hidden');
		});
		
		sidebarOverlay.addEventListener('click', function() {
			sidebar.classList.add('-translate-x-full');
			sidebarOverlay.classList.add('hidden');
		});
		
		// Close sidebar on window resize
		window.addEventListener('resize', function() {
			if (window.innerWidth >= 1024) {
				sidebar.classList.remove('-translate-x-full');
				sidebarOverlay.classList.add('hidden');
			}
		});
		
		// Initialize sidebar position on mobile
		if (window.innerWidth < 1024) {
			sidebar.classList.add('-translate-x-full');
		}
	</script>
</body>
</html>
