<!-- Plans Management Content -->
<?php if (isset($table_missing) && $table_missing): ?>
    <!-- Database Setup Required -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center py-12">
                <i class="fas fa-database text-6xl text-yellow-500 mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Database Setup Required</h2>
                <p class="text-gray-600 mb-6">The plans table needs to be created before you can manage subscription plans.</p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-2xl mx-auto mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                        <div class="text-left">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">SQL Required:</h4>
                            <p class="text-sm text-yellow-700 mb-3">Please run this SQL command in your database:</p>
                            <div class="bg-gray-900 text-green-400 p-3 rounded text-xs font-mono overflow-x-auto">
CREATE TABLE plans (<br>
&nbsp;&nbsp;id INT AUTO_INCREMENT PRIMARY KEY,<br>
&nbsp;&nbsp;name VARCHAR(100) NOT NULL,<br>
&nbsp;&nbsp;description TEXT NOT NULL,<br>
&nbsp;&nbsp;price VARCHAR(20) NOT NULL,<br>
&nbsp;&nbsp;period ENUM('month', 'year', 'lifetime') NOT NULL,<br>
&nbsp;&nbsp;features JSON NOT NULL,<br>
&nbsp;&nbsp;icon VARCHAR(50) DEFAULT 'fas fa-star',<br>
&nbsp;&nbsp;is_popular BOOLEAN DEFAULT FALSE,<br>
&nbsp;&nbsp;is_active BOOLEAN DEFAULT TRUE,<br>
&nbsp;&nbsp;order_index INT DEFAULT 0,<br>
&nbsp;&nbsp;created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,<br>
&nbsp;&nbsp;updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP<br>
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
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Error Loading Plans</h2>
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
                <h1 class="text-2xl font-bold text-gray-900">Plans Management</h1>
                <p class="mt-1 text-sm text-gray-600">Create, modify, and manage subscription plans</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button id="addPlanBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Plan
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-layer-group text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Plans</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['total_plans']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Plans</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['active_plans']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-star text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Popular Plan</p>
                    <p class="text-lg font-bold text-gray-900"><?php echo $stats['popular_plan']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Price</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation">$<?php echo $stats['average_price']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Plans</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="<?php echo htmlspecialchars($search); ?>"
                       placeholder="Search by name or description..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <?php if (!empty($search)): ?>
                <a href="/admin/plans" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($plans)): ?>
            <?php foreach ($plans as $plan): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden plan-card" data-plan-id="<?php echo $plan['id']; ?>">
                    <div class="p-6">
                        <!-- Plan Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-blue-100">
                                    <i class="<?php echo htmlspecialchars($plan['icon']); ?> text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($plan['name']); ?></h3>
                                    <?php if ($plan['is_popular']): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1"></i>Popular
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo $plan['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <i class="fas fa-<?php echo $plan['is_active'] ? 'check' : 'times'; ?> mr-1"></i>
                                    <?php echo $plan['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="mb-4">
                            <div class="flex items-baseline">
                                <span class="text-3xl font-bold text-gray-900">
                                    <?php if ($plan['price'] === 'Free' || $plan['price'] === '0'): ?>
                                        Free
                                    <?php else: ?>
                                        $<?php echo htmlspecialchars($plan['price']); ?>
                                    <?php endif; ?>
                                </span>
                                <?php if ($plan['price'] !== 'Free' && $plan['price'] !== '0'): ?>
                                    <span class="text-sm text-gray-600 ml-1">/<?php echo htmlspecialchars($plan['period']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 mb-4"><?php echo htmlspecialchars($plan['description']); ?></p>

                        <!-- Features -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Features:</h4>
                            <ul class="space-y-1">
                                <?php 
                                $features = is_array($plan['features']) ? $plan['features'] : [];
                                $displayFeatures = array_slice($features, 0, 4);
                                foreach ($displayFeatures as $feature): 
                                ?>
                                    <li class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <?php echo htmlspecialchars($feature); ?>
                                    </li>
                                <?php endforeach; ?>
                                <?php if (count($features) > 4): ?>
                                    <li class="text-sm text-gray-500">
                                        <i class="fas fa-ellipsis-h mr-2"></i>
                                        +<?php echo count($features) - 4; ?> more features
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors edit-plan-btn" 
                                    data-plan-id="<?php echo $plan['id']; ?>">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors delete-plan-btn" 
                                    data-plan-id="<?php echo $plan['id']; ?>" 
                                    data-plan-name="<?php echo htmlspecialchars($plan['name']); ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <!-- Order Index Display -->
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <span class="text-xs text-gray-500">Order: <?php echo $plan['order_index']; ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Plans Found</h3>
                    <p class="text-gray-600 mb-6">
                        <?php if (!empty($search)): ?>
                            No plans match your search criteria. Try adjusting your search terms.
                        <?php else: ?>
                            Get started by creating your first subscription plan.
                        <?php endif; ?>
                    </p>
                    <?php if (empty($search)): ?>
                        <button id="addFirstPlanBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Create Your First Plan
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-700">
                    <span>
                        Showing <?php echo min(($current_page - 1) * 20 + 1, $total_plans); ?> to 
                        <?php echo min($current_page * 20, $total_plans); ?> of <?php echo $total_plans; ?> plans
                    </span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?php echo $current_page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                           class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php
                    $start = max(1, $current_page - 2);
                    $end = min($total_pages, $current_page + 2);
                    
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                           class="px-3 py-2 <?php echo $i === $current_page ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> rounded-md transition-colors">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?php echo $current_page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                           class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Plan Modal -->
<div id="planModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add New Plan</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="planForm" class="space-y-6">
            <input type="hidden" id="planId" name="plan_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plan Name -->
                <div>
                    <label for="planName" class="block text-sm font-medium text-gray-700 mb-1">Plan Name *</label>
                    <input type="text" 
                           id="planName" 
                           name="name" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="error-message text-red-600 text-sm mt-1 hidden" id="nameError"></div>
                </div>

                <!-- Plan Icon -->
                <div>
                    <label for="planIcon" class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                    <select id="planIcon" 
                            name="icon" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="fas fa-star">⭐ Star</option>
                        <option value="fas fa-crown">👑 Crown</option>
                        <option value="fas fa-gem">💎 Diamond</option>
                        <option value="fas fa-rocket">🚀 Rocket</option>
                        <option value="fas fa-graduation-cap">🎓 Graduation Cap</option>
                        <option value="fas fa-trophy">🏆 Trophy</option>
                        <option value="fas fa-medal">🏅 Medal</option>
                        <option value="fas fa-bookmark">📖 Bookmark</option>
                        <option value="fas fa-lightbulb">💡 Lightbulb</option>
                        <option value="fas fa-heart">❤️ Heart</option>
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label for="planPrice" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                    <input type="text" 
                           id="planPrice" 
                           name="price" 
                           required 
                           placeholder="e.g., 29 or Free"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="error-message text-red-600 text-sm mt-1 hidden" id="priceError"></div>
                </div>

                <!-- Period -->
                <div>
                    <label for="planPeriod" class="block text-sm font-medium text-gray-700 mb-1">Period *</label>
                    <select id="planPeriod" 
                            name="period" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Period</option>
                        <option value="month">Monthly</option>
                        <option value="year">Yearly</option>
                        <option value="lifetime">Lifetime</option>
                    </select>
                    <div class="error-message text-red-600 text-sm mt-1 hidden" id="periodError"></div>
                </div>

                <!-- Order Index -->
                <div>
                    <label for="planOrder" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                    <input type="number" 
                           id="planOrder" 
                           name="order_index" 
                           min="0" 
                           value="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Status Toggles -->
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="planActive" 
                               name="is_active" 
                               checked 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="planActive" class="ml-2 block text-sm text-gray-900">Active Plan</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="planPopular" 
                               name="is_popular" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="planPopular" class="ml-2 block text-sm text-gray-900">Mark as Popular</label>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="planDescription" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea id="planDescription" 
                          name="description" 
                          required 
                          rows="3" 
                          placeholder="Describe what this plan offers..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                <div class="error-message text-red-600 text-sm mt-1 hidden" id="descriptionError"></div>
            </div>

            <!-- Features -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Features *</label>
                <div id="featuresContainer" class="space-y-2">
                    <!-- Features will be added dynamically -->
                </div>
                <button type="button" 
                        id="addFeatureBtn" 
                        class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-plus mr-2"></i>Add Feature
                </button>
                <div class="error-message text-red-600 text-sm mt-1 hidden" id="featuresError"></div>
            </div>

            <!-- Modal Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" 
                        id="cancelBtn" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        id="submitBtn" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Plan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Plan Management JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const modal = document.getElementById('planModal');
    const modalTitle = document.getElementById('modalTitle');
    const planForm = document.getElementById('planForm');
    const featuresContainer = document.getElementById('featuresContainer');
    
    // Button elements
    const addPlanBtn = document.getElementById('addPlanBtn');
    const addFirstPlanBtn = document.getElementById('addFirstPlanBtn');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const addFeatureBtn = document.getElementById('addFeatureBtn');
    
    // Initialize features
    function initializeFeatures(features = []) {
        featuresContainer.innerHTML = '';
        
        if (features.length === 0) {
            addFeature('');
        } else {
            features.forEach(feature => addFeature(feature));
        }
    }
    
    // Add feature input
    function addFeature(value = '') {
        const featureDiv = document.createElement('div');
        featureDiv.className = 'flex items-center space-x-2';
        featureDiv.innerHTML = `
            <input type="text" 
                   name="features[]" 
                   value="${value}" 
                   placeholder="Enter a feature..." 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button type="button" 
                    class="remove-feature px-2 py-2 text-red-600 hover:text-red-800" 
                    onclick="removeFeature(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        featuresContainer.appendChild(featureDiv);
    }
    
    // Remove feature input
    window.removeFeature = function(button) {
        const featureDiv = button.parentElement;
        if (featuresContainer.children.length > 1) {
            featureDiv.remove();
        }
    };
    
    // Open modal for new plan
    function openNewPlanModal() {
        modalTitle.textContent = 'Add New Plan';
        planForm.reset();
        document.getElementById('planId').value = '';
        document.getElementById('planActive').checked = true;
        document.getElementById('planPopular').checked = false;
        initializeFeatures();
        clearErrors();
        modal.classList.remove('hidden');
    }
    
    // Open modal for editing plan
    function openEditPlanModal(planId) {
        modalTitle.textContent = 'Edit Plan';
        
        // Fetch plan details
        fetch(`/admin/plans/get-details?plan_id=${planId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const plan = data.plan;
                    
                    // Populate form
                    document.getElementById('planId').value = plan.id;
                    document.getElementById('planName').value = plan.name;
                    document.getElementById('planDescription').value = plan.description;
                    document.getElementById('planPrice').value = plan.price;
                    document.getElementById('planPeriod').value = plan.period;
                    document.getElementById('planIcon').value = plan.icon;
                    document.getElementById('planOrder').value = plan.order_index;
                    document.getElementById('planActive').checked = plan.is_active == 1;
                    document.getElementById('planPopular').checked = plan.is_popular == 1;
                    
                    // Initialize features
                    initializeFeatures(plan.features || []);
                    clearErrors();
                    modal.classList.remove('hidden');
                } else {
                    alert('Error loading plan details: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading plan details');
            });
    }
    
    // Close modal
    function closeModalFunction() {
        modal.classList.add('hidden');
        planForm.reset();
        clearErrors();
    }
    
    // Clear form errors
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }
    
    // Show form errors
    function showErrors(errors) {
        clearErrors();
        Object.keys(errors).forEach(field => {
            const errorEl = document.getElementById(field + 'Error');
            if (errorEl) {
                errorEl.textContent = errors[field];
                errorEl.classList.remove('hidden');
            }
        });
    }
    
    // Submit form
    function submitForm(e) {
        e.preventDefault();
        
        const formData = new FormData(planForm);
        const data = {};
        
        // Convert FormData to object
        for (let [key, value] of formData.entries()) {
            if (key === 'features[]') {
                if (!data.features) data.features = [];
                if (value.trim()) data.features.push(value.trim());
            } else {
                data[key] = value;
            }
        }
        
        // Convert checkboxes
        data.is_active = document.getElementById('planActive').checked ? 1 : 0;
        data.is_popular = document.getElementById('planPopular').checked ? 1 : 0;
        
        const isEdit = !!data.plan_id;
        const url = isEdit ? '/admin/plans/update' : '/admin/plans/create';
        
        fetch(url, {
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
                if (data.errors) {
                    showErrors(data.errors);
                } else {
                    alert('Error: ' + data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the plan');
        });
    }
    
    // Delete plan
    function deletePlan(planId, planName) {
        if (confirm(`Are you sure you want to delete the plan "${planName}"? This action cannot be undone.`)) {
            fetch('/admin/plans/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ plan_id: planId })
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
                alert('An error occurred while deleting the plan');
            });
        }
    }
    
    // Event listeners
    if (addPlanBtn) addPlanBtn.addEventListener('click', openNewPlanModal);
    if (addFirstPlanBtn) addFirstPlanBtn.addEventListener('click', openNewPlanModal);
    if (closeModal) closeModal.addEventListener('click', closeModalFunction);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModalFunction);
    if (addFeatureBtn) addFeatureBtn.addEventListener('click', () => addFeature());
    if (planForm) planForm.addEventListener('submit', submitForm);
    
    // Edit and delete buttons
    document.querySelectorAll('.edit-plan-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            openEditPlanModal(planId);
        });
    });
    
    document.querySelectorAll('.delete-plan-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            const planName = this.getAttribute('data-plan-name');
            deletePlan(planId, planName);
        });
    });
    
    // Initialize with one feature field
    initializeFeatures();
});
</script>
<?php endif; ?>
