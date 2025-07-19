<?php
$title = "Testimonials - Radiology Resident";
$description = "Success stories from medical students and residents";
$page_title = "Student Testimonials";
$page_description = "Hear from successful students who have used our platform to excel in their radiology studies";

ob_start();
?>

<!-- Testimonials Content -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4" data-aos="fade-up">What Our Students Say</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Join thousands of medical students and residents who have transformed their radiology learning experience with our platform.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($testimonials as $testimonial): ?>
            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="<?php echo $testimonial['delay']; ?>">
                <div class="flex items-center mb-4">
                    <?php for($i = 0; $i < 5; $i++): ?>
                        <i class="fas fa-star text-yellow-400"></i>
                    <?php endfor; ?>
                </div>
                <blockquote class="text-gray-600 mb-6 italic">
                    "<?php echo htmlspecialchars($testimonial['quote']); ?>"
                </blockquote>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-4">
                        <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($testimonial['name']); ?></div>
                        <div class="text-gray-500 text-sm"><?php echo htmlspecialchars($testimonial['title']); ?></div>
                        <div class="text-blue-600 text-sm"><?php echo htmlspecialchars($testimonial['university']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- CTA Section -->
        <div class="bg-blue-600 rounded-2xl p-8 mt-16 text-center text-white" data-aos="fade-up">
            <h3 class="text-2xl font-bold mb-4">Ready to Join Our Success Stories?</h3>
            <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                Start your journey with our comprehensive radiology learning platform and become our next success story.
            </p>
            <div class="space-x-4">
                <a href="/register" class="inline-flex items-center px-8 py-3 bg-white text-blue-600 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Get Started
                </a>
                <a href="/plans" class="inline-flex items-center px-8 py-3 bg-blue-700 text-white rounded-full font-semibold hover:bg-blue-800 transition-colors">
                    <i class="fas fa-credit-card mr-2"></i>
                    View Plans
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/../frontend/layout.php';
?>
