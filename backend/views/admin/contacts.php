<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/backend/views/admin/assets/admin-sidebar.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include __DIR__ . '/components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <button id="sidebarToggle" class="lg:hidden text-gray-600 hover:text-gray-900 mr-4">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <h1 class="text-2xl font-semibold text-gray-900">Contact List</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?></span>
                            <a href="/auth/logout" class="text-sm text-red-600 hover:text-red-800">Logout</a>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php include __DIR__ . '/content/contacts-content.php'; ?>
            </main>
        </div>
    </div>
    
    <!-- Include Sidebar JavaScript -->
    <script src="/backend/views/admin/assets/admin-sidebar.js"></script>
</body>
</html>
