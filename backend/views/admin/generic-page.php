<?php
// Get the current path to highlight active menu item
$currentPath = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Admin Panel - Radiology Resident'; ?></title>
	<meta name="description" content="<?php echo $description ?? 'Admin panel for managing platform content and settings'; ?>">
	
	<!-- Favicon -->
	<link rel="icon" type="image/svg+xml" href="/assets/svg/dark-icon.svg">
	<link rel="alternate icon" href="/assets/svg/dark-icon.svg">
	
	<!-- Tailwind CSS -->
	<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
	<!-- Custom Logo Styles -->
	<link rel="stylesheet" href="/assets/css/logo-styles.css">
	<!-- Admin Sidebar Styles -->
	<link rel="stylesheet" href="/assets/css/admin-sidebar.css">
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
	</style>
</head>
<body class="bg-gray-100">
	<div class="flex h-screen bg-gray-100">
		<?php include __DIR__ . '/partials/sidebar.php'; ?>
		
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
					<?php
					// Get current path to determine which content to show
					$currentPath = $_SERVER['REQUEST_URI'];
					$pathParts = explode('?', $currentPath);
					$basePath = $pathParts[0];
					
					if ($basePath === '/admin/users') {
						include __DIR__ . '/content/users-content.php';
					} elseif ($basePath === '/admin/contacts') {
						include __DIR__ . '/content/contacts-content.php';
					} elseif ($basePath === '/admin/plans') {
						include __DIR__ . '/content/plans-content.php';
					} elseif ($basePath === '/admin/subscriptions') {
						include __DIR__ . '/content/subscriptions-content.php';
					} elseif ($basePath === '/admin/notes-chapters') {
						include __DIR__ . '/content/notes-chapters-content.php';
					} elseif ($basePath === '/admin/notes') {
						include __DIR__ . '/content/notes-content.php';
					} else {
						// Default content for other pages
						?>
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
						<?php
					}
					?>
				</div>
			</main>
		</div>
	</div>
	
	<!-- Mobile sidebar overlay -->
	<div id="sidebarOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-40 lg:hidden hidden"></div>
	
	<script src="/assets/js/admin-sidebar.js"></script>
</body>
</html>
