<!-- Users Management Content -->
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header Section -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">All Users</h1>
                <p class="mt-1 text-sm text-gray-600">Manage and view all registered users</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <span class="text-sm text-gray-500">Total: <?php echo $total_users; ?> users</span>
                <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New User
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="<?php echo htmlspecialchars($search); ?>"
                           placeholder="Search users by name, email, phone, specialization..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-1"></i> Search
                </button>
                <?php if (!empty($search)): ?>
                <a href="/admin/users" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-1"></i> Clear
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role & Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                                <p class="text-gray-500">
                                    <?php echo !empty($search) ? 'No users match your search criteria.' : 'No users have been registered yet.'; ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50">
                            <!-- User Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <?php if (!empty($user['profile_picture'])): ?>
                                            <img class="h-12 w-12 rounded-full object-cover" 
                                                 src="<?php echo htmlspecialchars($user['profile_picture']); ?>" 
                                                 alt="Profile">
                                        <?php else: ?>
                                            <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white font-medium text-lg">
                                                    <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </div>
                                        <?php if (!empty($user['specialization'])): ?>
                                            <div class="text-xs text-blue-600">
                                                <?php echo htmlspecialchars($user['specialization']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php if (!empty($user['phone'])): ?>
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                                            <?php echo htmlspecialchars($user['phone']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                        <span class="text-xs text-gray-500"><?php echo htmlspecialchars($user['email']); ?></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Account ID -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">
                                    #<?php echo str_pad($user['id'], 6, '0', STR_PAD_LEFT); ?>
                                </div>
                            </td>

                            <!-- Role & Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                                   ($user['role'] === 'instructor' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                                        <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                                    </span>
                                    <br>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo ucfirst(htmlspecialchars($user['status'])); ?>
                                    </span>
                                </div>
                            </td>

                            <!-- Joined Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">
                                        <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo date('g:i A', strtotime($user['created_at'])); ?>
                                    </div>
                                    <?php if (!empty($user['last_login'])): ?>
                                        <div class="text-xs text-blue-600 mt-1">
                                            Last: <?php echo date('M j', strtotime($user['last_login'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Edit Button -->
                                    <button onclick="editUser(<?php echo $user['id']; ?>)" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors" 
                                            title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Toggle Status Button -->
                                    <button onclick="toggleUserStatus(<?php echo $user['id']; ?>, '<?php echo $user['status'] === 'active' ? 'inactive' : 'active'; ?>')" 
                                            class="<?php echo $user['status'] === 'active' ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'; ?> transition-colors" 
                                            title="<?php echo $user['status'] === 'active' ? 'Disable User' : 'Enable User'; ?>">
                                        <i class="fas <?php echo $user['status'] === 'active' ? 'fa-user-slash' : 'fa-user-check'; ?>"></i>
                                    </button>

                                    <!-- Delete Button (only for non-admin users) -->
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <button onclick="deleteUser(<?php echo $user['id']; ?>)" 
                                                class="text-red-600 hover:text-red-900 transition-colors" 
                                                title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-400" title="Admin users cannot be deleted">
                                            <i class="fas fa-shield-alt"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
                    (<?php echo $total_users; ?> total users)
                </div>
                <div class="flex items-center space-x-2">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?php echo $current_page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                           class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                           class="px-3 py-1 <?php echo $i === $current_page ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'; ?> rounded-md transition-colors">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?php echo $current_page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                           class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeEditModal()"></div>
        
        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editUserForm" class="space-y-4">
                <input type="hidden" id="editUserId" name="user_id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="editFirstName" name="first_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="editLastName" name="last_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="editEmail" name="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" id="editPhone" name="phone"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                    <input type="text" id="editSpecialization" name="specialization"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="editRole" name="role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="student">Student</option>
                            <option value="instructor">Instructor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="editStatus" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for User Management -->
<script>
// Toggle user status
async function toggleUserStatus(userId, newStatus) {
    if (!confirm(`Are you sure you want to ${newStatus === 'active' ? 'enable' : 'disable'} this user?`)) {
        return;
    }
    
    try {
        const response = await fetch('/api/admin/users/toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}&status=${newStatus}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Network error occurred');
    }
}

// Delete user
async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch('/api/admin/users/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Network error occurred');
    }
}

// Edit user
async function editUser(userId) {
    try {
        const response = await fetch(`/api/admin/users/get?user_id=${userId}`);
        const result = await response.json();
        
        if (result.success) {
            const user = result.user;
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editFirstName').value = user.first_name || '';
            document.getElementById('editLastName').value = user.last_name || '';
            document.getElementById('editEmail').value = user.email || '';
            document.getElementById('editPhone').value = user.phone || '';
            document.getElementById('editSpecialization').value = user.specialization || '';
            document.getElementById('editRole').value = user.role || '';
            document.getElementById('editStatus').value = user.status || '';
            
            document.getElementById('editUserModal').classList.remove('hidden');
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Network error occurred');
    }
}

// Close edit modal
function closeEditModal() {
    document.getElementById('editUserModal').classList.add('hidden');
}

// Handle edit form submission
document.getElementById('editUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    
    try {
        const response = await fetch('/api/admin/users/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeEditModal();
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Network error occurred');
    }
});

// Close modal when clicking outside
document.getElementById('editUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
