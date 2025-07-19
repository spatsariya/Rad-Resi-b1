<!-- Contact List Management Content -->
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header Section -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Contact List</h1>
                <p class="mt-1 text-sm text-gray-600">Manage contacts and communications with all users</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <span class="text-sm text-gray-500">Total: <?php echo $total_contacts; ?> contacts</span>
                <button onclick="openBulkMessageModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Send Bulk Message
                </button>
                <button onclick="openContactGroupModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-users mr-2"></i>
                    Manage Groups
                </button>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" class="space-y-4">
            <!-- Search Bar -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="<?php echo htmlspecialchars($search); ?>"
                               placeholder="Search contacts by name, email, phone, institution, specialization..." 
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
                    <?php if (!empty($search) || !empty($role_filter) || !empty($group_filter)): ?>
                    <a href="/admin/contacts" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times mr-1"></i> Clear
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                    <select name="role_filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Roles</option>
                        <option value="user" <?php echo $role_filter === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="student" <?php echo $role_filter === 'student' ? 'selected' : ''; ?>>Student</option>
                        <option value="instructor" <?php echo $role_filter === 'instructor' ? 'selected' : ''; ?>>Instructor</option>
                        <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status_filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contact Group</label>
                    <select name="group_filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Groups</option>
                        <?php foreach ($contact_groups as $group): ?>
                        <option value="<?php echo $group['id']; ?>" <?php echo $group_filter == $group['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($group['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Newsletter</label>
                    <select name="newsletter_filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="1" <?php echo $newsletter_filter === '1' ? 'selected' : ''; ?>>Subscribed</option>
                        <option value="0" <?php echo $newsletter_filter === '0' ? 'selected' : ''; ?>>Not Subscribed</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Contact Statistics -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600"><?php echo $stats['total_users']; ?></div>
                <div class="text-xs text-gray-500">Total Contacts</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600"><?php echo $stats['active_users']; ?></div>
                <div class="text-xs text-gray-500">Active Users</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600"><?php echo $stats['newsletter_subscribers']; ?></div>
                <div class="text-xs text-gray-500">Newsletter</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600"><?php echo $stats['messages_sent_today']; ?></div>
                <div class="text-xs text-gray-500">Messages Today</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600"><?php echo $stats['total_interactions']; ?></div>
                <div class="text-xs text-gray-500">Total Interactions</div>
            </div>
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-3 text-left">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professional Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Groups & Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Communications</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-address-book text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No contacts found</h3>
                                <p class="text-gray-500">
                                    <?php echo !empty($search) ? 'No contacts match your search criteria.' : 'No contacts available.'; ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contacts as $contact): ?>
                        <tr class="hover:bg-gray-50" data-user-id="<?php echo $contact['id']; ?>">
                            <!-- Checkbox -->
                            <td class="px-3 py-4 whitespace-nowrap">
                                <input type="checkbox" class="contact-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                       value="<?php echo $contact['id']; ?>">
                            </td>
                            
                            <!-- Contact Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <?php if (!empty($contact['profile_picture'])): ?>
                                            <img class="h-12 w-12 rounded-full object-cover" 
                                                 src="<?php echo htmlspecialchars($contact['profile_picture']); ?>" 
                                                 alt="Profile">
                                        <?php else: ?>
                                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-500">
                                                    <?php echo strtoupper(substr($contact['first_name'], 0, 1) . substr($contact['last_name'], 0, 1)); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-envelope mr-1"></i>
                                            <?php echo htmlspecialchars($contact['email']); ?>
                                        </div>
                                        <?php if (!empty($contact['phone'])): ?>
                                            <div class="text-sm text-gray-500">
                                                <i class="fas fa-phone mr-1"></i>
                                                <?php echo htmlspecialchars($contact['phone']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Professional Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php if (!empty($contact['specialization'])): ?>
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-user-md text-blue-500 mr-2"></i>
                                            <span class="font-medium"><?php echo htmlspecialchars($contact['specialization']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($contact['institution'])): ?>
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-university text-green-500 mr-2"></i>
                                            <span class="text-xs"><?php echo htmlspecialchars($contact['institution']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($contact['experience_years'])): ?>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-orange-500 mr-2"></i>
                                            <span class="text-xs"><?php echo $contact['experience_years']; ?> years exp.</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Groups & Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $contact['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                                   ($contact['role'] === 'instructor' ? 'bg-blue-100 text-blue-800' : 
                                                   ($contact['role'] === 'student' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')); ?>">
                                        <?php echo ucfirst(htmlspecialchars($contact['role'])); ?>
                                    </span>
                                    <br>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $contact['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo ucfirst(htmlspecialchars($contact['status'])); ?>
                                    </span>
                                    <?php if ($contact['newsletter']): ?>
                                        <br>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Newsletter
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Last Activity -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <?php if (!empty($contact['last_login'])): ?>
                                        <div class="text-xs text-gray-500 mb-1">Last login:</div>
                                        <div><?php echo date('M j, Y', strtotime($contact['last_login'])); ?></div>
                                        <div class="text-xs text-gray-400"><?php echo date('g:i A', strtotime($contact['last_login'])); ?></div>
                                    <?php else: ?>
                                        <div class="text-xs text-gray-500">Never logged in</div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Communications -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="text-center">
                                        <div class="text-sm font-medium text-blue-600"><?php echo $contact['messages_received'] ?? 0; ?></div>
                                        <div class="text-xs text-gray-500">Messages</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-sm font-medium text-green-600"><?php echo $contact['interactions_count'] ?? 0; ?></div>
                                        <div class="text-xs text-gray-500">Interactions</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button onclick="openMessageModal(<?php echo $contact['id']; ?>)" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors" title="Send Message">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                    <button onclick="sendEmail(<?php echo $contact['id']; ?>)" 
                                            class="text-green-600 hover:text-green-900 transition-colors" title="Send Email">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    <button onclick="viewHistory(<?php echo $contact['id']; ?>)" 
                                            class="text-purple-600 hover:text-purple-900 transition-colors" title="View History">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    <button onclick="editContact(<?php echo $contact['id']; ?>)" 
                                            class="text-orange-600 hover:text-orange-900 transition-colors" title="Edit Contact">
                                        <i class="fas fa-edit"></i>
                                    </button>
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
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <?php echo (($current_page - 1) * 20) + 1; ?> to <?php echo min($current_page * 20, $total_contacts); ?> of <?php echo $total_contacts; ?> contacts
            </div>
            <div class="flex space-x-1">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="px-3 py-2 text-sm <?php echo $i === $current_page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?> border border-gray-300 rounded-md">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Send Message Modal -->
<div id="messageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeMessageModal()"></div>
        
        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Send Message</h3>
                <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="messageForm" class="space-y-4">
                <input type="hidden" id="messageUserId" name="user_id">
                
                <div id="recipientInfo" class="p-3 bg-gray-50 rounded-md"></div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message Type</label>
                    <select id="messageType" name="message_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="internal">Internal Message</option>
                        <option value="email">Email</option>
                        <option value="sms">SMS (if phone available)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" id="messageSubject" name="subject" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea id="messageContent" name="message" rows="6" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeMessageModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Message Modal -->
<div id="bulkMessageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeBulkMessageModal()"></div>
        
        <div class="inline-block w-full max-w-3xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Send Bulk Message</h3>
                <button onclick="closeBulkMessageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="bulkMessageForm" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recipients</label>
                        <select id="bulkRecipients" name="recipients" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="selected">Selected Contacts (<span id="selectedCount">0</span>)</option>
                            <option value="all">All Contacts</option>
                            <option value="role_user">All Users</option>
                            <option value="role_student">All Students</option>
                            <option value="role_instructor">All Instructors</option>
                            <option value="newsletter">Newsletter Subscribers</option>
                            <option value="active">Active Users Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message Type</label>
                        <select id="bulkMessageType" name="message_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="internal">Internal Message</option>
                            <option value="email">Email</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" id="bulkSubject" name="subject" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea id="bulkMessage" name="message" rows="6" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeBulkMessageModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send to <span id="recipientCount">0</span> Recipients
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Communication History Modal -->
<div id="historyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeHistoryModal()"></div>
        
        <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Communication History</h3>
                <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="historyContent">
                <!-- History content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Contact List JavaScript Functions
let selectedContacts = [];

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedContacts();
});

// Individual checkbox functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('contact-checkbox')) {
        updateSelectedContacts();
    }
});

function updateSelectedContacts() {
    const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
    selectedContacts = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('selectedCount').textContent = selectedContacts.length;
    document.getElementById('recipientCount').textContent = selectedContacts.length;
}

// Send individual message
function openMessageModal(userId) {
    document.getElementById('messageUserId').value = userId;
    
    // Get user info for display
    fetch(`/api/admin/contacts/user-info?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                document.getElementById('recipientInfo').innerHTML = `
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-blue-800">${user.first_name[0]}${user.last_name[0]}</span>
                        </div>
                        <div>
                            <div class="font-medium">${user.first_name} ${user.last_name}</div>
                            <div class="text-sm text-gray-500">${user.email}</div>
                        </div>
                    </div>
                `;
            }
        });
    
    document.getElementById('messageModal').classList.remove('hidden');
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
    document.getElementById('messageForm').reset();
}

// Send message form submission
document.getElementById('messageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/api/admin/contacts/send-message', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Message sent successfully!');
            closeMessageModal();
            // Optionally refresh the page or update UI
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Network error occurred');
    }
});

// Bulk message functionality
function openBulkMessageModal() {
    updateSelectedContacts();
    document.getElementById('bulkMessageModal').classList.remove('hidden');
}

function closeBulkMessageModal() {
    document.getElementById('bulkMessageModal').classList.add('hidden');
    document.getElementById('bulkMessageForm').reset();
}

// Send email functionality
function sendEmail(userId) {
    // This will open the user's default email client
    fetch(`/api/admin/contacts/user-info?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                window.location.href = `mailto:${user.email}?subject=Message from Radiology Resident Platform`;
            }
        });
}

// View communication history
function viewHistory(userId) {
    fetch(`/api/admin/contacts/history?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const history = data.history;
                let historyHtml = '';
                
                if (history.length === 0) {
                    historyHtml = '<p class="text-gray-500 text-center py-8">No communication history found.</p>';
                } else {
                    historyHtml = '<div class="space-y-4">';
                    history.forEach(item => {
                        historyHtml += `
                            <div class="border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-${item.type === 'email' ? 'envelope' : item.type === 'sms' ? 'sms' : 'comment'} text-blue-500 mr-2"></i>
                                        <span class="font-medium">${item.subject}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">${item.created_at}</span>
                                </div>
                                <p class="text-gray-700">${item.message}</p>
                                <div class="mt-2 text-xs text-gray-500">
                                    Status: <span class="capitalize">${item.status}</span>
                                </div>
                            </div>
                        `;
                    });
                    historyHtml += '</div>';
                }
                
                document.getElementById('historyContent').innerHTML = historyHtml;
                document.getElementById('historyModal').classList.remove('hidden');
            }
        });
}

function closeHistoryModal() {
    document.getElementById('historyModal').classList.add('hidden');
}

// Edit contact functionality
function editContact(userId) {
    // This will redirect to the user edit page or open an edit modal
    window.location.href = `/admin/users/edit/${userId}`;
}

// Contact group management
function openContactGroupModal() {
    // Implementation for contact group management
    alert('Contact group management feature coming soon!');
}
</script>
