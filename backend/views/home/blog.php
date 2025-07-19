<?php
$title = "Blog - Radiology Resident";
$description = "Latest insights and updates in radiology education";
$page_title = "Radiology Education Blog";
$page_description = "Stay updated with the latest insights, tips, and developments in radiology education";

ob_start();
?>

<!-- Blog Content -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="space-y-8">
                    <?php foreach($blog_posts as $post): ?>
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow" data-aos="fade-up">
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="<?php echo $post['image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-8">
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <time datetime="<?php echo $post['date']; ?>"><?php echo date('F j, Y', strtotime($post['date'])); ?></time>
                                <span class="mx-2">•</span>
                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded"><?php echo htmlspecialchars($post['category']); ?></span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                                <a href="/blog/<?php echo $post['slug']; ?>" class="hover:text-blue-600 transition-colors">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h2>
                            <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                        <?php echo strtoupper(substr($post['author'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($post['author']); ?></div>
                                        <div class="text-gray-500 text-sm"><?php echo htmlspecialchars($post['author_title']); ?></div>
                                    </div>
                                </div>
                                <a href="/blog/<?php echo $post['slug']; ?>" class="text-blue-600 hover:text-blue-700 font-semibold">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <nav class="flex space-x-2">
                        <a href="#" class="px-4 py-2 text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</a>
                        <a href="#" class="px-4 py-2 text-white bg-blue-600 border border-blue-600 rounded-md">1</a>
                        <a href="#" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">2</a>
                        <a href="#" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">3</a>
                        <a href="#" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</a>
                    </nav>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="space-y-8">
                    <!-- Categories -->
                    <div class="bg-white rounded-lg shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Categories</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 flex justify-between">Study Tips <span class="text-gray-400">12</span></a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 flex justify-between">Radiology Basics <span class="text-gray-400">8</span></a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 flex justify-between">Exam Preparation <span class="text-gray-400">15</span></a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 flex justify-between">Technology Updates <span class="text-gray-400">6</span></a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 flex justify-between">Career Guidance <span class="text-gray-400">9</span></a></li>
                        </ul>
                    </div>

                    <!-- Recent Posts -->
                    <div class="bg-white rounded-lg shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Posts</h3>
                        <ul class="space-y-4">
                            <li>
                                <a href="#" class="block group">
                                    <h4 class="text-gray-900 group-hover:text-blue-600 font-medium mb-1">Top 10 Study Tips for Radiology Students</h4>
                                    <p class="text-gray-500 text-sm">March 15, 2024</p>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block group">
                                    <h4 class="text-gray-900 group-hover:text-blue-600 font-medium mb-1">Understanding CT Scan Basics</h4>
                                    <p class="text-gray-500 text-sm">March 12, 2024</p>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block group">
                                    <h4 class="text-gray-900 group-hover:text-blue-600 font-medium mb-1">FRCR Exam Preparation Guide</h4>
                                    <p class="text-gray-500 text-sm">March 10, 2024</p>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div class="bg-blue-600 rounded-lg p-6 text-white" data-aos="fade-up" data-aos-delay="300">
                        <h3 class="text-xl font-bold mb-4">Stay Updated</h3>
                        <p class="text-blue-100 mb-4">Get the latest radiology education tips delivered to your inbox.</p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Your email address" class="w-full px-4 py-2 rounded-md text-gray-900">
                            <button type="submit" class="w-full bg-white text-blue-600 py-2 rounded-md font-semibold hover:bg-gray-100 transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/../frontend/layout.php';
?>
