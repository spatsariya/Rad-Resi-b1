<!-- Subscriptions Management Content -->
<?php if (isset($table_missing) && $table_missing): ?>
    <!-- Database Setup Required -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center py-12">
                <i class="fas fa-database text-6xl text-yellow-500 mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Database Setup Required</h2>
                <p class="text-gray-600 mb-6">The subscriptions table needs to be created before you can manage user subscriptions.</p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-2xl mx-auto mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                        <div class="text-left">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">SQL Required:</h4>
                            <p class="text-sm text-yellow-700 mb-3">Please run the subscription schema SQL in your database.</p>
                            <div class="bg-gray-900 text-green-400 p-3 rounded text-xs font-mono overflow-x-auto">
CREATE TABLE subscriptions (<br>
&nbsp;&nbsp;id INT AUTO_INCREMENT PRIMARY KEY,<br>
&nbsp;&nbsp;user_id INT NOT NULL,<br>
&nbsp;&nbsp;plan_id INT NOT NULL,<br>
&nbsp;&nbsp;amount VARCHAR(20) NOT NULL,<br>
&nbsp;&nbsp;currency VARCHAR(3) DEFAULT 'USD',<br>
&nbsp;&nbsp;payment_method VARCHAR(50),<br>
&nbsp;&nbsp;transaction_id VARCHAR(100),<br>
&nbsp;&nbsp;status ENUM('active', 'cancelled', 'expired', 'pending', 'failed') DEFAULT 'pending',<br>
&nbsp;&nbsp;starts_at TIMESTAMP NULL,<br>
&nbsp;&nbsp;expires_at TIMESTAMP NULL,<br>
&nbsp;&nbsp;created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,<br>
&nbsp;&nbsp;updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,<br>
&nbsp;&nbsp;FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,<br>
&nbsp;&nbsp;FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE<br>
);
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>
                        Refresh Page After Setup
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php elseif (isset($error_message)): ?>
    <!-- Error State -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center py-12">
                <i class="fas fa-exclamation-circle text-6xl text-red-500 mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Error Loading Subscriptions</h2>
                <p class="text-red-600 mb-6"><?php echo htmlspecialchars($error_message); ?></p>
                
                <div class="mt-6">
                    <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Subscriptions Management</h1>
                <p class="mt-1 text-sm text-gray-600">View and manage all user subscriptions</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button id="exportBtn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors mr-2">
                    <i class="fas fa-download mr-2"></i>
                    Export Data
                </button>
                <button id="refreshBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Subscriptions</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['total_subscriptions']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['active_subscriptions']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Expired</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['expired_subscriptions']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-xl font-bold text-gray-900 stat-animation">$<?php echo number_format($stats['total_revenue'], 2); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-calendar text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-xl font-bold text-gray-900 stat-animation">$<?php echo number_format($stats['monthly_revenue'], 2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="<?php echo htmlspecialchars($search); ?>"
                       placeholder="Search by user, email, transaction ID..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status_filter" 
                        name="status_filter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="expired" <?php echo $status_filter === 'expired' ? 'selected' : ''; ?>>Expired</option>
                    <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="failed" <?php echo $status_filter === 'failed' ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>
            
            <div>
                <label for="plan_filter" class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                <select id="plan_filter" 
                        name="plan_filter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Plans</option>
                    <?php foreach ($plans as $plan): ?>
                        <option value="<?php echo $plan['id']; ?>" <?php echo $plan_filter == $plan['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($plan['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <?php if (!empty($search) || !empty($status_filter) || !empty($plan_filter)): ?>
                <a href="/admin/subscriptions" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <?php if (!empty($subscriptions)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($subscriptions as $subscription): ?>
                            <tr class="hover:bg-gray-50">
                                <!-- User Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">
                                                <?php echo strtoupper(substr($subscription['first_name'] ?? 'U', 0, 1)); ?>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($subscription['first_name'] . ' ' . $subscription['last_name']); ?>
                                            </div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($subscription['email']); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Plan Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="<?php echo htmlspecialchars($subscription['plan_icon'] ?? 'fas fa-star'); ?> text-blue-600 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($subscription['plan_name'] ?? 'Unknown Plan'); ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        $<?php echo htmlspecialchars($subscription['amount']); ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo strtoupper($subscription['currency'] ?? 'USD'); ?>
                                    </div>
                                </td>

                                <!-- Transaction Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono">
                                        <?php echo htmlspecialchars($subscription['transaction_id'] ?? 'N/A'); ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($subscription['payment_method'] ?? 'N/A'); ?>
                                    </div>
                                </td>

                                <!-- Dates -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <strong>Start:</strong> <?php echo date('M j, Y', strtotime($subscription['starts_at'] ?? $subscription['created_at'])); ?>
                                    </div>
                                    <div>
                                        <strong>Expires:</strong> 
                                        <?php if ($subscription['expires_at']): ?>
                                            <?php echo date('M j, Y', strtotime($subscription['expires_at'])); ?>
                                        <?php else: ?>
                                            <span class="text-gray-500">N/A</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $status = $subscription['computed_status'] ?? $subscription['status'];
                                    $statusClasses = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'expired' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed' => 'bg-red-100 text-red-800'
                                    ];
                                    $statusIcons = [
                                        'active' => 'fas fa-check-circle',
                                        'expired' => 'fas fa-times-circle',
                                        'cancelled' => 'fas fa-ban',
                                        'pending' => 'fas fa-clock',
                                        'failed' => 'fas fa-exclamation-triangle'
                                    ];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusClasses[$status] ?? 'bg-gray-100 text-gray-800'; ?>">
                                        <i class="<?php echo $statusIcons[$status] ?? 'fas fa-question'; ?> mr-1"></i>
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button class="view-details-btn text-blue-600 hover:text-blue-900" 
                                            data-subscription-id="<?php echo $subscription['id']; ?>"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button class="generate-invoice-btn text-green-600 hover:text-green-900" 
                                            data-subscription-id="<?php echo $subscription['id']; ?>"
                                            title="Generate Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </button>
                                    
                                    <div class="relative inline-block">
                                        <button class="status-dropdown-btn text-purple-600 hover:text-purple-900" 
                                                data-subscription-id="<?php echo $subscription['id']; ?>"
                                                title="Change Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                    
                                    <button class="delete-subscription-btn text-red-600 hover:text-red-900" 
                                            data-subscription-id="<?php echo $subscription['id']; ?>"
                                            data-user-name="<?php echo htmlspecialchars($subscription['first_name'] . ' ' . $subscription['last_name']); ?>"
                                            title="Delete Subscription">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="p-12 text-center">
                <i class="fas fa-credit-card text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Subscriptions Found</h3>
                <p class="text-gray-600 mb-6">
                    <?php if (!empty($search) || !empty($status_filter) || !empty($plan_filter)): ?>
                        No subscriptions match your current filters. Try adjusting your search criteria.
                    <?php else: ?>
                        No subscriptions have been created yet.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-700">
                    <span>
                        Showing <?php echo min(($current_page - 1) * 20 + 1, $total_subscriptions); ?> to 
                        <?php echo min($current_page * 20, $total_subscriptions); ?> of <?php echo $total_subscriptions; ?> subscriptions
                    </span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?php echo $current_page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : ''; ?><?php echo !empty($plan_filter) ? '&plan_filter=' . urlencode($plan_filter) : ''; ?>" 
                           class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php
                    $start = max(1, $current_page - 2);
                    $end = min($total_pages, $current_page + 2);
                    
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : ''; ?><?php echo !empty($plan_filter) ? '&plan_filter=' . urlencode($plan_filter) : ''; ?>" 
                           class="px-3 py-2 <?php echo $i === $current_page ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> rounded-md transition-colors">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?php echo $current_page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : ''; ?><?php echo !empty($plan_filter) ? '&plan_filter=' . urlencode($plan_filter) : ''; ?>" 
                           class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Subscription Details Modal -->
<div id="subscriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Subscription Details</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="subscriptionDetails">
            <!-- Details will be loaded here -->
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Update Status</h3>
            <button id="closeStatusModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="statusForm">
            <input type="hidden" id="statusSubscriptionId" name="subscription_id">
            <div class="mb-4">
                <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                <select id="newStatus" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="active">Active</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="expired">Expired</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelStatusBtn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Subscription Management JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const subscriptionModal = document.getElementById('subscriptionModal');
    const statusModal = document.getElementById('statusModal');
    const closeModal = document.getElementById('closeModal');
    const closeStatusModal = document.getElementById('closeStatusModal');
    const statusForm = document.getElementById('statusForm');
    
    // View Details
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const subscriptionId = this.getAttribute('data-subscription-id');
            viewSubscriptionDetails(subscriptionId);
        });
    });
    
    // Generate Invoice
    document.querySelectorAll('.generate-invoice-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const subscriptionId = this.getAttribute('data-subscription-id');
            generateInvoice(subscriptionId);
        });
    });
    
    // Status Dropdown
    document.querySelectorAll('.status-dropdown-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const subscriptionId = this.getAttribute('data-subscription-id');
            openStatusModal(subscriptionId);
        });
    });
    
    // Delete Subscription
    document.querySelectorAll('.delete-subscription-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const subscriptionId = this.getAttribute('data-subscription-id');
            const userName = this.getAttribute('data-user-name');
            deleteSubscription(subscriptionId, userName);
        });
    });
    
    // Close modals
    if (closeModal) {
        closeModal.addEventListener('click', () => subscriptionModal.classList.add('hidden'));
    }
    if (closeStatusModal) {
        closeStatusModal.addEventListener('click', () => statusModal.classList.add('hidden'));
    }
    document.getElementById('cancelStatusBtn').addEventListener('click', () => statusModal.classList.add('hidden'));
    
    // Status form submission
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            updateSubscriptionStatus();
        });
    }
    
    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', () => location.reload());
    
    function viewSubscriptionDetails(subscriptionId) {
        fetch(`/admin/subscriptions/get-details?subscription_id=${subscriptionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displaySubscriptionDetails(data.subscription);
                    subscriptionModal.classList.remove('hidden');
                } else {
                    alert('Error loading subscription details: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading subscription details');
            });
    }
    
    function displaySubscriptionDetails(subscription) {
        const features = subscription.plan_features || [];
        const featuresHtml = features.map(feature => `<li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>${feature}</li>`).join('');
        
        const html = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">User Information</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Name:</strong> ${subscription.first_name} ${subscription.last_name}</p>
                        <p><strong>Email:</strong> ${subscription.email}</p>
                        <p><strong>Phone:</strong> ${subscription.phone || 'N/A'}</p>
                        <p><strong>Institution:</strong> ${subscription.institution || 'N/A'}</p>
                        <p><strong>Specialization:</strong> ${subscription.specialization || 'N/A'}</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Subscription Details</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Plan:</strong> ${subscription.plan_name}</p>
                        <p><strong>Amount:</strong> $${subscription.amount} ${subscription.currency}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-sm font-medium bg-blue-100 text-blue-800">${subscription.status}</span></p>
                        <p><strong>Payment Method:</strong> ${subscription.payment_method || 'N/A'}</p>
                        <p><strong>Transaction ID:</strong> <code class="bg-white px-2 py-1 rounded text-sm">${subscription.transaction_id || 'N/A'}</code></p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Timeline</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Created:</strong> ${new Date(subscription.created_at).toLocaleDateString()}</p>
                        <p><strong>Starts:</strong> ${subscription.starts_at ? new Date(subscription.starts_at).toLocaleDateString() : 'N/A'}</p>
                        <p><strong>Expires:</strong> ${subscription.expires_at ? new Date(subscription.expires_at).toLocaleDateString() : 'N/A'}</p>
                        <p><strong>Last Updated:</strong> ${new Date(subscription.updated_at).toLocaleDateString()}</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Plan Features</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        ${features.length > 0 ? `<ul class="space-y-1 text-sm">${featuresHtml}</ul>` : '<p class="text-gray-500">No features available</p>'}
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('subscriptionDetails').innerHTML = html;
    }
    
    function openStatusModal(subscriptionId) {
        document.getElementById('statusSubscriptionId').value = subscriptionId;
        statusModal.classList.remove('hidden');
    }
    
    function updateSubscriptionStatus() {
        const formData = new FormData(statusForm);
        const data = {
            subscription_id: formData.get('subscription_id'),
            status: formData.get('status')
        };
        
        fetch('/admin/subscriptions/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
        });
    }
    
    function generateInvoice(subscriptionId) {
        fetch(`/admin/subscriptions/generate-invoice?subscription_id=${subscriptionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error generating invoice');
            });
    }
    
    function deleteSubscription(subscriptionId, userName) {
        if (confirm(`Are you sure you want to delete the subscription for "${userName}"? This action cannot be undone.`)) {
            fetch('/admin/subscriptions/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ subscription_id: subscriptionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the subscription');
            });
        }
    }
});
</script>
<?php endif; ?>
