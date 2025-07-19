<?php
// Get the current path to highlight active menu item
$currentPath = $_SERVER['REQUEST_URI'];
?>

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
		<a href="/admin" class="menu-item flex items-center px-3 py-2 <?php echo ($currentPath === '/admin' || $currentPath === '/admin/') ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/users" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/users') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-user-friends w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">All Users</span>
					<div class="tooltip">All Users</div>
				</a>
				<a href="/admin/contacts" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/contacts') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/notes-chapters" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/notes-chapters') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-bookmark w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Notes Chapters</span>
					<div class="tooltip">Notes Chapters</div>
				</a>
				<a href="/admin/notes" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/notes') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-sticky-note w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Notes</span>
					<div class="tooltip">Notes</div>
				</a>
				<a href="/admin/prev-year-questions" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/prev-year-questions') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/video-categories" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/video-categories') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-folder w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Video Category</span>
					<div class="tooltip">Video Category</div>
				</a>
				<a href="/admin/videos" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/videos') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/spotter-categories" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/spotter-categories') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-tags w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Spotter Category</span>
					<div class="tooltip">Spotter Category</div>
				</a>
				<a href="/admin/spotters" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/spotters') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-crosshairs w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Spotters</span>
					<div class="tooltip">Spotters</div>
				</a>
				<a href="/admin/osce-categories" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/osce-categories') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-layer-group w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">OSCE Category</span>
					<div class="tooltip">OSCE Category</div>
				</a>
				<a href="/admin/osce" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/osce') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/rapid-frs-categories" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/rapid-frs-categories') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-list w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Rapid FRS Category</span>
					<div class="tooltip">Rapid FRS Category</div>
				</a>
				<a href="/admin/rapid-frs" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/rapid-frs') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/table-viva-categories" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/table-viva-categories') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-th-list w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Table Viva Category</span>
					<div class="tooltip">Table Viva Category</div>
				</a>
				<a href="/admin/table-viva" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/table-viva') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/longcases-categories" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/longcases-categories') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-folder-open w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Longcases Category</span>
					<div class="tooltip">Longcases Category</div>
				</a>
				<a href="/admin/long-cases" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/long-cases') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/shortcases-categories" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/shortcases-categories') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-folder w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Shortcases Category</span>
					<div class="tooltip">Shortcases Category</div>
				</a>
				<a href="/admin/short-cases" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/short-cases') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
				<a href="/admin/frcr-categories" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/frcr-categories') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-tags w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Category</span>
					<div class="tooltip">Category</div>
				</a>
				<a href="/admin/frcr-subjects" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/frcr-subjects') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-book w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Subject</span>
					<div class="tooltip">Subject</div>
				</a>
				<a href="/admin/frcr-quiz" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/frcr-quiz') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-question-circle w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Quiz</span>
					<div class="tooltip">Quiz</div>
				</a>
				<a href="/admin/frcr-questions" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/frcr-questions') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-question w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Question</span>
					<div class="tooltip">Question</div>
				</a>
			</div>
		</div>
		
		<!-- Content Management -->
		<div class="mt-4 border-t border-gray-700 pt-3">
			<!-- Pages -->
			<a href="/admin/pages" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/pages') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md transition-colors text-sm mb-1 relative">
				<i class="fas fa-file-alt w-4 h-4 flex-shrink-0"></i>
				<span class="sidebar-text ml-2">Pages</span>
				<div class="tooltip">Pages</div>
			</a>
			
			<!-- Banner -->
			<a href="/admin/banners" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/banners') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md transition-colors text-sm mb-1 relative">
				<i class="fas fa-image w-4 h-4 flex-shrink-0"></i>
				<span class="sidebar-text ml-2">Banner</span>
				<div class="tooltip">Banner</div>
			</a>
			
			<!-- Blogs -->
			<a href="/admin/blogs" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/blogs') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md transition-colors text-sm mb-1 relative">
				<i class="fas fa-blog w-4 h-4 flex-shrink-0"></i>
				<span class="sidebar-text ml-2">All Blogs</span>
				<div class="tooltip">All Blogs</div>
			</a>
			
			<!-- Testimonials -->
			<a href="/admin/testimonials" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/testimonials') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md transition-colors text-sm mb-1 relative">
				<i class="fas fa-quote-left w-4 h-4 flex-shrink-0"></i>
				<span class="sidebar-text ml-2">All Testimonial</span>
				<div class="tooltip">All Testimonial</div>
			</a>
			
			<!-- Plan -->
			<a href="/admin/plans" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/plans') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md transition-colors text-sm mb-1 relative">
				<i class="fas fa-credit-card w-4 h-4 flex-shrink-0"></i>
				<span class="sidebar-text ml-2">Plan</span>
				<div class="tooltip">Plan</div>
			</a>
			
			<!-- Subscription -->
			<a href="/admin/subscriptions" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/subscriptions') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md transition-colors text-sm mb-1 relative">
				<i class="fas fa-user-check w-4 h-4 flex-shrink-0"></i>
				<span class="sidebar-text ml-2">Subscription</span>
				<div class="tooltip">Subscription</div>
			</a>
			
			<!-- FAQ -->
			<a href="/admin/faq" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/faq') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md transition-colors text-sm mb-1 relative">
				<i class="fas fa-question-circle w-4 h-4 flex-shrink-0"></i>
				<span class="sidebar-text ml-2">FAQ</span>
				<div class="tooltip">FAQ</div>
			</a>
			
			<!-- Report -->
			<a href="/admin/reports" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/reports') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> rounded-md transition-colors text-sm mb-1 relative">
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
				<a href="/admin/global-settings" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/global-settings') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-globe w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Global Settings</span>
					<div class="tooltip">Global Settings</div>
				</a>
				<a href="/admin/logo-favicon" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/logo-favicon') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
					<i class="fas fa-icons w-3 h-3 flex-shrink-0"></i>
					<span class="sidebar-text ml-2">Logo & Favicon</span>
					<div class="tooltip">Logo & Favicon</div>
				</a>
				<a href="/admin/settings" class="menu-item flex items-center px-3 py-2 <?php echo (strpos($currentPath, '/admin/settings') === 0) ? 'text-gray-100 bg-blue-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'; ?> rounded-md text-sm relative">
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
