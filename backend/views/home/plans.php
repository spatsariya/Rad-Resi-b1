<?php
$title = "Subscription Plans - Radiology Resident";
$description = "Choose the perfect plan for your radiology education journey";
$page_title = "Subscription Plans";
$page_description = "Choose the perfect plan to accelerate your radiology learning journey";

ob_start();
?>

<!-- Plans Content -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Pricing Toggle -->
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="inline-flex items-center bg-gray-100 rounded-full p-1">
                <button class="px-6 py-2 rounded-full text-sm font-semibold bg-white text-gray-900 shadow-sm">Monthly</button>
                <button class="px-6 py-2 rounded-full text-sm font-semibold text-gray-500">Yearly (Save 20%)</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <?php foreach($plans as $index => $plan): ?>
            <div class="<?php echo $plan['featured'] ? 'bg-blue-600 text-white transform scale-105' : 'bg-white'; ?> rounded-2xl shadow-xl p-8 <?php echo $plan['featured'] ? '' : 'border border-gray-200'; ?>" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <?php if($plan['featured']): ?>
                <div class="text-center mb-4">
                    <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                </div>
                <?php endif; ?>
                
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($plan['name']); ?></h3>
                    <div class="text-4xl font-bold mb-2">
                        $<?php echo $plan['price']; ?>
                        <span class="text-lg font-normal <?php echo $plan['featured'] ? 'text-blue-100' : 'text-gray-500'; ?>">/month</span>
                    </div>
                    <p class="<?php echo $plan['featured'] ? 'text-blue-100' : 'text-gray-600'; ?>"><?php echo htmlspecialchars($plan['description']); ?></p>
                </div>

                <ul class="space-y-4 mb-8">
                    <?php foreach($plan['features'] as $feature): ?>
                    <li class="flex items-center">
                        <i class="fas fa-check <?php echo $plan['featured'] ? 'text-blue-200' : 'text-green-500'; ?> mr-3"></i>
                        <span class="<?php echo $plan['featured'] ? 'text-blue-100' : 'text-gray-700'; ?>"><?php echo htmlspecialchars($feature); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <button class="w-full py-3 px-6 rounded-lg font-semibold transition-colors <?php echo $plan['featured'] ? 'bg-white text-blue-600 hover:bg-gray-100' : 'bg-blue-600 text-white hover:bg-blue-700'; ?>">
                    <?php echo $plan['button_text']; ?>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- FAQ Section -->
        <div class="mt-20" data-aos="fade-up">
            <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">Frequently Asked Questions</h3>
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Can I change my plan anytime?</h4>
                    <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial available?</h4>
                    <p class="text-gray-600">We offer a 7-day free trial for all premium plans. No credit card required to start your trial.</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">What payment methods do you accept?</h4>
                    <p class="text-gray-600">We accept all major credit cards (Visa, MasterCard, American Express) and PayPal payments.</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Can I cancel my subscription?</h4>
                    <p class="text-gray-600">Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your current billing period.</p>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="bg-gray-50 rounded-2xl p-8 mt-16 text-center" data-aos="fade-up">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Need Help Choosing?</h3>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                Our team is here to help you select the perfect plan for your learning goals. Get personalized recommendations based on your needs.
            </p>
            <div class="space-x-4">
                <a href="/contact" class="inline-flex items-center px-8 py-3 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-comments mr-2"></i>
                    Contact Sales
                </a>
                <a href="#" class="inline-flex items-center px-8 py-3 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-phone mr-2"></i>
                    Schedule a Call
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/../frontend/layout.php';
?>
