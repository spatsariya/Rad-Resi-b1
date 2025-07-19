<?php if (isset($setup_results)): ?>
    <!-- Setup Results -->
    <div class="mb-6">
        <?php if ($setup_results['success']): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Setup Successful!</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p><?php echo htmlspecialchars($setup_results['message']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Detailed Results -->
            <div class="mt-4 bg-white shadow rounded-lg p-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Setup Details</h4>
                <div class="space-y-3">
                    <?php foreach ($setup_results['details'] as $key => $detail): ?>
                        <?php if ($key !== 'test_results'): ?>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 min-w-0 flex-1"><?php echo htmlspecialchars($detail); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                
                <?php if (isset($setup_results['details']['test_results'])): ?>
                    <div class="mt-6">
                        <h5 class="text-md font-medium text-gray-900 mb-3">Verification Tests</h5>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <?php foreach ($setup_results['details']['test_results'] as $test): ?>
                                <div class="text-sm font-mono"><?php echo htmlspecialchars($test); ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="mt-6 flex space-x-4">
                    <a href="/admin/notes" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Go to Notes Management
                    </a>
                    <a href="/admin" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7V4a1 1 0 011-1h8a1 1 0 011 1v3" />
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Setup Failed</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p><?php echo htmlspecialchars($setup_results['message']); ?></p>
                            <?php if (isset($setup_results['details'])): ?>
                                <div class="mt-2">
                                    <p><strong>Details:</strong> <?php echo htmlspecialchars($setup_results['details']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Setup Instructions -->
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Notes Database Setup</h3>
        <p class="mt-1 text-sm text-gray-500">
            Set up the notes table and populate it with sample radiology study notes.
        </p>
    </div>
    
    <div class="px-6 py-4">
        <div class="space-y-6">
            <!-- Prerequisites -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-3">📋 Prerequisites</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Database connection must be working</li>
                    <li>Notes Chapters table must exist (run Notes Chapters setup first if needed)</li>
                    <li>Admin privileges required for database operations</li>
                </ul>
            </div>
            
            <!-- What This Does -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-3">🔧 What This Setup Does</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Creates the <code class="bg-gray-100 px-1 rounded">notes</code> table with proper schema</li>
                    <li>Establishes foreign key relationships with notes_chapters</li>
                    <li>Inserts 10 comprehensive sample notes covering various radiology specialties</li>
                    <li>Creates database indexes for optimal performance</li>
                    <li>Sets up statistics view for reporting</li>
                    <li>Verifies everything works correctly</li>
                </ul>
            </div>
            
            <!-- Sample Data -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-3">📝 Sample Notes Include</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <h5 class="font-medium text-gray-800">Cardiothoracic</h5>
                        <ul class="list-disc list-inside ml-4">
                            <li>Chest X-ray Interpretation</li>
                            <li>CT Chest Protocol</li>
                            <li>Pulmonary Embolism Imaging</li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-800">Abdominal</h5>
                        <ul class="list-disc list-inside ml-4">
                            <li>Abdominal X-ray Review</li>
                            <li>CT Abdomen Protocol</li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-800">Neurological</h5>
                        <ul class="list-disc list-inside ml-4">
                            <li>Brain MRI Sequences</li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-800">Others</h5>
                        <ul class="list-disc list-inside ml-4">
                            <li>MSK, Pediatric, Breast</li>
                            <li>Radiation Safety</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Warning -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">⚠️ Important</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>If the notes table already exists, it will be dropped and recreated. Any existing notes data will be lost.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Run Setup Button -->
            <div class="flex justify-center">
                <form method="POST" onsubmit="return confirmSetup()">
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z" />
                        </svg>
                        🚀 Run Notes Database Setup
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmSetup() {
    return confirm('This will create/recreate the notes table and insert sample data. If the table already exists, all existing notes will be lost. Are you sure you want to continue?');
}
</script>
