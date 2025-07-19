<!-- Notes Chapters Management Content -->
<?php if (isset($table_missing) && $table_missing): ?>
    <!-- Database Setup Required -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center py-12">
                <i class="fas fa-database text-6xl text-yellow-500 mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Database Setup Required</h2>
                <p class="text-gray-600 mb-6">The notes_chapters table needs to be created before you can manage theory exam chapters.</p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-2xl mx-auto mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                        <div class="text-left">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">SQL Required:</h4>
                            <p class="text-sm text-yellow-700 mb-3">Please run the notes chapters schema SQL in your database.</p>
                            <div class="bg-gray-900 text-green-400 p-3 rounded text-xs font-mono overflow-x-auto">
CREATE TABLE notes_chapters (<br>
&nbsp;&nbsp;id INT AUTO_INCREMENT PRIMARY KEY,<br>
&nbsp;&nbsp;chapter_name VARCHAR(255) NOT NULL,<br>
&nbsp;&nbsp;sub_chapter_name VARCHAR(255) DEFAULT NULL,<br>
&nbsp;&nbsp;description TEXT,<br>
&nbsp;&nbsp;thumbnail_image VARCHAR(500),<br>
&nbsp;&nbsp;display_order INT DEFAULT 0,<br>
&nbsp;&nbsp;status ENUM('active', 'inactive') DEFAULT 'active',<br>
&nbsp;&nbsp;created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,<br>
&nbsp;&nbsp;updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,<br>
&nbsp;&nbsp;INDEX idx_chapter_name (chapter_name),<br>
&nbsp;&nbsp;INDEX idx_status (status),<br>
&nbsp;&nbsp;INDEX idx_display_order (display_order)<br>
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
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Error Loading Chapters</h2>
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
                <h1 class="text-2xl font-bold text-gray-900">Notes Chapters Management</h1>
                <p class="mt-1 text-sm text-gray-600">Create and manage theory exam notes chapters</p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                <button id="addChapterBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Chapter
                </button>
                <button id="addSubChapterBtn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Sub-Chapter
                </button>
                <button id="refreshBtn" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
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
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Chapters</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['total_chapters']; ?></p>
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
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['active_chapters']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['inactive_chapters']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-images text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">With Thumbnails</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['chapters_with_thumbnails']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-layer-group text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Unique Chapters</p>
                    <p class="text-2xl font-bold text-gray-900 stat-animation"><?php echo $stats['unique_chapters']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="<?php echo htmlspecialchars($search); ?>"
                       placeholder="Search by chapter or sub-chapter name..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status_filter" 
                        name="status_filter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <?php if (!empty($search) || !empty($status_filter)): ?>
                <a href="/admin/notes-chapters" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Chapters Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <?php if (!empty($chapters)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thumbnail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapter Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Display Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($chapters as $chapter): ?>
                            <tr class="hover:bg-gray-50">
                                <!-- Thumbnail -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
                                        <?php if (!empty($chapter['thumbnail_image']) && file_exists($chapter['thumbnail_image'])): ?>
                                            <img src="/<?php echo htmlspecialchars($chapter['thumbnail_image']); ?>" 
                                                 alt="Chapter thumbnail" 
                                                 class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i class="fas fa-book text-gray-400 text-2xl"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Chapter Details -->
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            <?php if (!empty($chapter['parent_id'])): ?>
                                                <i class="fas fa-level-up-alt text-gray-400 mr-2 transform rotate-90"></i>
                                                <span class="text-blue-600">Sub:</span>
                                            <?php else: ?>
                                                <i class="fas fa-folder text-blue-500 mr-2"></i>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($chapter['chapter_name']); ?>
                                        </div>
                                        <?php if (!empty($chapter['sub_chapter_name'])): ?>
                                            <div class="text-sm text-blue-600 mt-1 ml-6">
                                                <i class="fas fa-arrow-right mr-1"></i>
                                                <?php echo htmlspecialchars($chapter['sub_chapter_name']); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($chapter['description'])): ?>
                                            <div class="text-sm text-gray-500 mt-1 ml-6">
                                                <?php echo htmlspecialchars(substr($chapter['description'], 0, 100)) . (strlen($chapter['description']) > 100 ? '...' : ''); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Display Order -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            #<?php echo $chapter['display_order']; ?>
                                        </span>
                                        <div class="ml-2 flex flex-col space-y-1">
                                            <button class="move-up-btn text-gray-400 hover:text-blue-600" 
                                                    data-chapter-id="<?php echo $chapter['id']; ?>"
                                                    data-current-order="<?php echo $chapter['display_order']; ?>"
                                                    title="Move Up">
                                                <i class="fas fa-chevron-up text-xs"></i>
                                            </button>
                                            <button class="move-down-btn text-gray-400 hover:text-blue-600" 
                                                    data-chapter-id="<?php echo $chapter['id']; ?>"
                                                    data-current-order="<?php echo $chapter['display_order']; ?>"
                                                    title="Move Down">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="status-toggle-btn inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $chapter['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>"
                                            data-chapter-id="<?php echo $chapter['id']; ?>"
                                            data-current-status="<?php echo $chapter['status']; ?>">
                                        <i class="fas <?php echo $chapter['status'] === 'active' ? 'fa-check-circle' : 'fa-times-circle'; ?> mr-1"></i>
                                        <?php echo ucfirst($chapter['status']); ?>
                                    </button>
                                </td>

                                <!-- Created Date -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('M j, Y', strtotime($chapter['created_at'])); ?>
                                    <div class="text-xs text-gray-500">
                                        <?php echo date('g:i A', strtotime($chapter['created_at'])); ?>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button class="view-details-btn text-blue-600 hover:text-blue-900" 
                                            data-chapter-id="<?php echo $chapter['id']; ?>"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button class="edit-chapter-btn text-green-600 hover:text-green-900" 
                                            data-chapter-id="<?php echo $chapter['id']; ?>"
                                            title="Edit Chapter">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <button class="delete-chapter-btn text-red-600 hover:text-red-900" 
                                            data-chapter-id="<?php echo $chapter['id']; ?>"
                                            data-chapter-name="<?php echo htmlspecialchars($chapter['chapter_name']); ?>"
                                            title="Delete Chapter">
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
                <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Chapters Found</h3>
                <p class="text-gray-600 mb-6">
                    <?php if (!empty($search) || !empty($status_filter)): ?>
                        No chapters match your current filters. Try adjusting your search criteria.
                    <?php else: ?>
                        No notes chapters have been created yet. Create your first chapter to get started.
                    <?php endif; ?>
                </p>
                <button id="addFirstChapterBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Chapter
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-700">
                    <span>
                        Showing <?php echo min(($current_page - 1) * 20 + 1, $total_chapters); ?> to 
                        <?php echo min($current_page * 20, $total_chapters); ?> of <?php echo $total_chapters; ?> chapters
                    </span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?php echo $current_page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : ''; ?>" 
                           class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php
                    $start = max(1, $current_page - 2);
                    $end = min($total_pages, $current_page + 2);
                    
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : ''; ?>" 
                           class="px-3 py-2 <?php echo $i === $current_page ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> rounded-md transition-colors">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?php echo $current_page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : ''; ?>" 
                           class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Add/Edit Chapter Modal -->
<div id="chapterModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-5 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Chapter</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="chapterForm" enctype="multipart/form-data">
            <input type="hidden" id="chapterId" name="chapter_id">
            <input type="hidden" id="formType" name="form_type" value="chapter">
            
            <!-- Parent Chapter Selection (only for sub-chapters) -->
            <div id="parentChapterSection" class="mb-4 hidden">
                <label for="parentChapter" class="block text-sm font-medium text-gray-700 mb-2">Parent Chapter *</label>
                <select id="parentChapter" name="parent_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Parent Chapter</option>
                    <!-- Options will be loaded dynamically -->
                </select>
            </div>
            
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div>
                    <label for="chapterName" class="block text-sm font-medium text-gray-700 mb-2">
                        <span id="chapterNameLabel">Chapter Name</span> *
                    </label>
                    <input type="text" id="chapterName" name="chapter_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter chapter name">
                </div>
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter chapter description"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="displayOrder" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" id="displayOrder" name="display_order" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter display order">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Image</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <div id="thumbnailPreview" class="hidden mb-4">
                            <img id="previewImage" src="" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg">
                            <button type="button" id="removeThumbnail" class="mt-2 text-red-600 hover:text-red-800 text-sm">
                                <i class="fas fa-trash mr-1"></i>Remove
                            </button>
                        </div>
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Upload a file</span>
                                <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                    </div>
                </div>
                <input type="hidden" id="removeThumbnailFlag" name="remove_thumbnail" value="0">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelBtn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Save Chapter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Chapter Details</h3>
            <button id="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="chapterDetails">
            <!-- Details will be loaded here -->
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const chapterModal = document.getElementById('chapterModal');
    const detailsModal = document.getElementById('detailsModal');
    const chapterForm = document.getElementById('chapterForm');
    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const previewImage = document.getElementById('previewImage');
    
    let isEditMode = false;
    
    // Add Chapter and Sub-Chapter buttons
    document.getElementById('addChapterBtn').addEventListener('click', () => openAddModal('chapter'));
    document.getElementById('addSubChapterBtn').addEventListener('click', () => openAddModal('subchapter'));
    document.getElementById('addFirstChapterBtn')?.addEventListener('click', () => openAddModal('chapter'));
    
    // Edit Chapter buttons
    document.querySelectorAll('.edit-chapter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const chapterId = this.getAttribute('data-chapter-id');
            openEditModal(chapterId);
        });
    });
    
    // View Details buttons
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const chapterId = this.getAttribute('data-chapter-id');
            viewChapterDetails(chapterId);
        });
    });
    
    // Delete Chapter buttons
    document.querySelectorAll('.delete-chapter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const chapterId = this.getAttribute('data-chapter-id');
            const chapterName = this.getAttribute('data-chapter-name');
            deleteChapter(chapterId, chapterName);
        });
    });
    
    // Status Toggle buttons
    document.querySelectorAll('.status-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const chapterId = this.getAttribute('data-chapter-id');
            const currentStatus = this.getAttribute('data-current-status');
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            updateChapterStatus(chapterId, newStatus);
        });
    });
    
    // Close modals
    document.getElementById('closeModal').addEventListener('click', closeModal);
    document.getElementById('closeDetailsModal').addEventListener('click', () => detailsModal.classList.add('hidden'));
    document.getElementById('cancelBtn').addEventListener('click', closeModal);
    
    // Form submission
    chapterForm.addEventListener('submit', handleFormSubmit);
    
    // Thumbnail handling
    thumbnailInput.addEventListener('change', handleThumbnailChange);
    document.getElementById('removeThumbnail').addEventListener('click', removeThumbnail);
    
    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', () => location.reload());
    
    function openAddModal(type = 'chapter') {
        isEditMode = false;
        chapterForm.reset();
        document.getElementById('chapterId').value = '';
        document.getElementById('formType').value = type;
        thumbnailPreview.classList.add('hidden');
        document.getElementById('removeThumbnailFlag').value = '0';
        
        if (type === 'subchapter') {
            document.getElementById('modalTitle').textContent = 'Add Sub-Chapter';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save mr-2"></i>Save Sub-Chapter';
            document.getElementById('chapterNameLabel').textContent = 'Sub-Chapter Name';
            document.getElementById('chapterName').placeholder = 'Enter sub-chapter name';
            document.getElementById('parentChapterSection').classList.remove('hidden');
            document.getElementById('parentChapter').required = true;
            loadParentChapters();
        } else {
            document.getElementById('modalTitle').textContent = 'Add Chapter';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save mr-2"></i>Save Chapter';
            document.getElementById('chapterNameLabel').textContent = 'Chapter Name';
            document.getElementById('chapterName').placeholder = 'Enter chapter name';
            document.getElementById('parentChapterSection').classList.add('hidden');
            document.getElementById('parentChapter').required = false;
        }
        
        chapterModal.classList.remove('hidden');
    }
    
    function loadParentChapters() {
        console.log('Loading parent chapters...');
        // Load only main chapters (those without parent_id) for the dropdown
        fetch('/admin/notes-chapters/get-main-chapters', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                console.log('Response URL:', response.url);
                
                // Get the response text first to see what we're actually getting
                return response.text().then(text => {
                    console.log('Raw response text:', text);
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    console.log('Content-Type:', contentType);
                    
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Expected JSON but got ' + contentType + '. Response: ' + text.substring(0, 100));
                    }
                    
                    try {
                        return JSON.parse(text);
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        console.error('Response text that failed to parse:', text);
                        throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                    }
                });
            })
            .then(data => {
                console.log('API Response:', data);
                const parentSelect = document.getElementById('parentChapter');
                parentSelect.innerHTML = '<option value="">Select Parent Chapter</option>';
                
                if (data.success && data.chapters) {
                    console.log('Found', data.chapters.length, 'chapters');
                    if (data.chapters.length === 0) {
                        parentSelect.innerHTML += '<option value="" disabled>No chapters available</option>';
                    } else {
                        data.chapters.forEach(chapter => {
                            const option = document.createElement('option');
                            option.value = chapter.id;
                            option.textContent = chapter.chapter_name;
                            parentSelect.appendChild(option);
                        });
                    }
                } else {
                    console.error('API returned error:', data.message);
                    parentSelect.innerHTML += '<option value="" disabled>Error loading chapters</option>';
                    alert('Error loading parent chapters: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error loading parent chapters:', error);
                const parentSelect = document.getElementById('parentChapter');
                parentSelect.innerHTML = '<option value="">Select Parent Chapter</option><option value="" disabled>Error loading chapters</option>';
                alert('Error loading parent chapters: ' + error.message);
            });
    }
    
    function openEditModal(chapterId) {
        isEditMode = true;
        document.getElementById('modalTitle').textContent = 'Edit Chapter';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save mr-2"></i>Update Chapter';
        
        // Load chapter data
        fetch(`/admin/notes-chapters/get-details?chapter_id=${chapterId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateForm(data.chapter);
                    chapterModal.classList.remove('hidden');
                } else {
                    alert('Error loading chapter: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading chapter details');
            });
    }
    
    function populateForm(chapter) {
        document.getElementById('chapterId').value = chapter.id;
        document.getElementById('chapterName').value = chapter.chapter_name;
        document.getElementById('subChapterName').value = chapter.sub_chapter_name || '';
        document.getElementById('description').value = chapter.description || '';
        document.getElementById('displayOrder').value = chapter.display_order;
        document.getElementById('status').value = chapter.status;
        
        // Handle parent chapter selection
        if (chapter.parent_id) {
            // If this is a sub-chapter, show the parent selection section and set the value
            document.getElementById('parentSelection').classList.remove('hidden');
            document.getElementById('parentChapter').value = chapter.parent_id;
            document.getElementById('formType').value = 'subchapter';
        } else {
            // If this is a main chapter, hide the parent selection section
            document.getElementById('parentSelection').classList.add('hidden');
            document.getElementById('formType').value = 'chapter';
        }
        
        // Handle thumbnail preview
        if (chapter.thumbnail_image) {
            previewImage.src = '/' + chapter.thumbnail_image;
            thumbnailPreview.classList.remove('hidden');
        } else {
            thumbnailPreview.classList.add('hidden');
        }
        
        document.getElementById('removeThumbnailFlag').value = '0';
    }
    
    function closeModal() {
        chapterModal.classList.add('hidden');
        chapterForm.reset();
        thumbnailPreview.classList.add('hidden');
    }
    
    function handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(chapterForm);
        const url = isEditMode ? '/admin/notes-chapters/update' : '/admin/notes-chapters/create';
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the chapter');
        });
    }
    
    function handleThumbnailChange(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file');
                e.target.value = '';
                return;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                e.target.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                thumbnailPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
            
            document.getElementById('removeThumbnailFlag').value = '0';
        }
    }
    
    function removeThumbnail() {
        thumbnailInput.value = '';
        thumbnailPreview.classList.add('hidden');
        document.getElementById('removeThumbnailFlag').value = '1';
    }
    
    function viewChapterDetails(chapterId) {
        fetch(`/admin/notes-chapters/get-details?chapter_id=${chapterId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayChapterDetails(data.chapter);
                    detailsModal.classList.remove('hidden');
                } else {
                    alert('Error loading chapter details: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading chapter details');
            });
    }
    
    function displayChapterDetails(chapter) {
        const html = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Chapter Information</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Chapter Name:</strong> ${chapter.chapter_name}</p>
                        <p><strong>Sub Chapter:</strong> ${chapter.sub_chapter_name || 'N/A'}</p>
                        <p><strong>Display Order:</strong> #${chapter.display_order}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-sm font-medium ${chapter.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${chapter.status}</span></p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Thumbnail</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        ${chapter.thumbnail_image ? 
                            `<img src="/${chapter.thumbnail_image}" alt="Chapter thumbnail" class="w-full h-32 object-cover rounded-lg">` :
                            '<div class="w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center"><i class="fas fa-image text-gray-400 text-3xl"></i></div>'
                        }
                    </div>
                </div>
                
                <div class="md:col-span-2 space-y-4">
                    <h4 class="font-semibold text-gray-900">Description</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">${chapter.description || 'No description provided'}</p>
                    </div>
                </div>
                
                <div class="md:col-span-2 space-y-4">
                    <h4 class="font-semibold text-gray-900">Timeline</h4>
                    <div class="bg-gray-50 p-4 rounded-lg grid grid-cols-2 gap-4">
                        <div>
                            <p><strong>Created:</strong> ${new Date(chapter.created_at).toLocaleDateString()}</p>
                            <p class="text-sm text-gray-500">${new Date(chapter.created_at).toLocaleTimeString()}</p>
                        </div>
                        <div>
                            <p><strong>Last Updated:</strong> ${new Date(chapter.updated_at).toLocaleDateString()}</p>
                            <p class="text-sm text-gray-500">${new Date(chapter.updated_at).toLocaleTimeString()}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('chapterDetails').innerHTML = html;
    }
    
    function updateChapterStatus(chapterId, status) {
        fetch('/admin/notes-chapters/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                chapter_id: chapterId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
    
    function deleteChapter(chapterId, chapterName) {
        if (confirm(`Are you sure you want to delete the chapter "${chapterName}"? This action cannot be undone.`)) {
            fetch('/admin/notes-chapters/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ chapter_id: chapterId })
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
                alert('An error occurred while deleting the chapter');
            });
        }
    }
});
</script>
<?php endif; ?>
