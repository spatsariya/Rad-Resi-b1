<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Admin Dashboard - Radiology Resident'; ?></title>
	<meta name="description" content="<?php echo $description ?? 'Admin dashboard for managing courses, users, and content'; ?>">
	
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
		<div id="sidebar" class="sidebar-transition bg-gray-900 text-white w-64 min-h-screen p-4">
			<div class="flex items-center justify-between mb-8">
				<div class="flex items-center">
					<i class="fas fa-stethoscope text-blue-400 text-xl mr-2"></i>
					<h1 class="text-lg font-semibold">Radiology Resident</h1>
				</div>
				<button id="sidebarToggle" class="lg:hidden text-gray-400 hover:text-white">
					<i class="fas fa-times text-xl"></i>
				</button>
			</div>
			
			<!-- Navigation Menu -->
			<nav class="space-y-2">
				<!-- Dashboard -->
				<a href="/admin" class="flex items-center px-4 py-3 text-gray-100 bg-blue-600 rounded-lg">
					<i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
					<span>Dashboard</span>
				</a>
				
				<!-- Courses -->
				<a href="/admin/courses" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
					<i class="fas fa-graduation-cap w-5 h-5 mr-3"></i>
					<span>Courses</span>
				</a>
				
				<!-- Users -->
				<a href="/admin/users" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
					<i class="fas fa-users w-5 h-5 mr-3"></i>
					<span>Users</span>
				</a>
				
				<!-- Analytics -->
				<a href="/admin/analytics" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
					<i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
					<span>Analytics</span>
				</a>
				
				<!-- Settings -->
				<a href="/admin/settings" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
					<i class="fas fa-cog w-5 h-5 mr-3"></i>
					<span>Settings</span>
				</a>
				
				<div class="border-t border-gray-700 my-4"></div>
				
				<!-- Back to Site -->
				<a href="/" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
					<i class="fas fa-external-link-alt w-5 h-5 mr-3"></i>
					<span>Back to Site</span>
				</a>
				
				<!-- Logout -->
				<a href="/auth/logout" class="flex items-center px-4 py-3 text-gray-300 hover:bg-red-600 hover:text-white rounded-lg transition-colors">
					<i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
					<span>Logout</span>
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
		// Sidebar toggle functionality
		const menuToggle = document.getElementById('menuToggle');
		const sidebarToggle = document.getElementById('sidebarToggle');
		const sidebar = document.getElementById('sidebar');
		const sidebarOverlay = document.getElementById('sidebarOverlay');
		
		function toggleSidebar() {
			sidebar.classList.toggle('-translate-x-full');
			sidebarOverlay.classList.toggle('hidden');
		}
		
		menuToggle.addEventListener('click', toggleSidebar);
		sidebarToggle.addEventListener('click', toggleSidebar);
		sidebarOverlay.addEventListener('click', toggleSidebar);
		
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
