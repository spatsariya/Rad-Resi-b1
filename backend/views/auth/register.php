<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Register - Radiology Resident'; ?></title>
	<meta name="description" content="<?php echo $description ?? 'Create your Radiology Resident account and start your medical education journey'; ?>">
	
	<!-- Favicon -->
	<link rel="icon" type="image/svg+xml" href="/assets/svg/light-icon.svg">
	<link rel="alternate icon" href="/assets/svg/light-icon.svg">
	
	<!-- Tailwind CSS -->
	<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
	<!-- Custom Logo Styles -->
	<link rel="stylesheet" href="/assets/css/logo-styles.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<!-- Poppins font -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	
	<style>
		/*primary color*/
		.bg-cream {
			background-color: #FFF2E1;
		}
		
		/*font*/
		body {
			font-family: 'Poppins', sans-serif;
		}
		
		.text-darken {
			color: #2F327D;
		}
		
		.bg-medical-blue {
			background-color: #1e40af;
		}
		
		.floating { 
			animation-name: floating; 
			animation-duration: 3s; 
			animation-iteration-count: infinite; 
			animation-timing-function: ease-in-out;
		} 
		@keyframes floating { 
			0% { transform: translate(0, 0px); } 
			50% { transform: translate(0, 8px); } 
			100% { transform: translate(0, -0px); } 
		}
		
		.form-floating {
			position: relative;
		}
		
		.form-floating .form-control {
			padding: 1rem 0.75rem 0.625rem;
		}
		
		.form-floating label {
			position: absolute;
			top: 0;
			left: 0;
			height: 100%;
			padding: 1rem 0.75rem;
			pointer-events: none;
			border: 1px solid transparent;
			transform-origin: 0 0;
			transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
		}
		
		.form-floating .form-control:focus ~ label,
		.form-floating .form-control:not(:placeholder-shown) ~ label {
			opacity: 0.65;
			transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
		}
		
		.password-strength {
			height: 4px;
			border-radius: 2px;
			transition: all 0.3s ease;
		}
		
		.strength-weak { background: #ef4444; width: 25%; }
		.strength-fair { background: #f59e0b; width: 50%; }
		.strength-good { background: #10b981; width: 75%; }
		.strength-strong { background: #059669; width: 100%; }
		
		.step {
			transition: all 0.3s ease;
		}
		
		.step.active {
			transform: translateX(0);
			opacity: 1;
		}
		
		.step.inactive {
			transform: translateX(100%);
			opacity: 0;
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
		}
		
		.step.prev {
			transform: translateX(-100%);
		}
	</style>
</head>
<body class="bg-cream min-h-screen">
	<!-- Navigation -->
	<nav class="bg-white shadow-sm py-4">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex justify-between items-center">
				<div class="flex items-center">
					<a href="/" class="logo-container">
						<img src="/assets/svg/logo.svg" alt="Radiology Resident" class="logo-light">
					</a>
				</div>
				<div class="flex items-center space-x-4">
					<span class="text-gray-600 text-sm">Already have an account?</span>
					<a href="/login" class="text-blue-600 hover:text-blue-700 font-medium">Sign In</a>
				</div>
			</div>
		</div>
	</nav>

	<!-- Main Content -->
	<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
		<div class="max-w-md w-full space-y-8">
			<!-- Header -->
			<div class="text-center">
				<div class="mx-auto h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center floating">
					<i class="fas fa-user-md text-blue-600 text-xl"></i>
				</div>
				<h2 class="mt-6 text-3xl font-extrabold text-darken">
					Join Radiology Resident
				</h2>
				<p class="mt-2 text-sm text-gray-600">
					Start your medical education journey today
				</p>
			</div>

			<!-- Registration Form -->
			<form id="registerForm" class="mt-8 space-y-6 relative overflow-hidden">
				<!-- CSRF Token -->
				<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
				
				<!-- Step 1: Personal Information -->
				<div id="step1" class="step active space-y-4">
					<div class="text-center mb-6">
						<h3 class="text-lg font-semibold text-darken">Personal Information</h3>
						<p class="text-sm text-gray-600">Tell us about yourself</p>
					</div>
					
					<!-- Name Fields -->
					<div class="grid grid-cols-2 gap-4">
						<div class="form-floating">
							<input type="text" 
								   id="first_name" 
								   name="first_name" 
								   class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
								   placeholder="First Name"
								   required>
							<label for="first_name" class="text-gray-500">First Name</label>
						</div>
						<div class="form-floating">
							<input type="text" 
								   id="last_name" 
								   name="last_name" 
								   class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
								   placeholder="Last Name"
								   required>
							<label for="last_name" class="text-gray-500">Last Name</label>
						</div>
					</div>

					<!-- Email -->
					<div class="form-floating">
						<input type="email" 
							   id="email" 
							   name="email" 
							   class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
							   placeholder="Email Address"
							   required>
						<label for="email" class="text-gray-500">Email Address</label>
					</div>

					<!-- Phone -->
					<div class="form-floating">
						<input type="tel" 
							   id="phone" 
							   name="phone" 
							   class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
							   placeholder="Phone Number">
						<label for="phone" class="text-gray-500">Phone Number (Optional)</label>
					</div>

					<button type="button" 
							onclick="nextStep()" 
							class="w-full flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
						Continue
						<i class="fas fa-arrow-right ml-2"></i>
					</button>
				</div>

				<!-- Step 2: Professional Information -->
				<div id="step2" class="step inactive space-y-4">
					<div class="text-center mb-6">
						<h3 class="text-lg font-semibold text-darken">Professional Details</h3>
						<p class="text-sm text-gray-600">Your medical background</p>
					</div>

					<!-- Specialization -->
					<div class="form-floating">
						<select id="specialization" 
								name="specialization" 
								class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
							<option value="">Select Specialization</option>
							<option value="Radiology">Radiology</option>
							<option value="Internal Medicine">Internal Medicine</option>
							<option value="Surgery">Surgery</option>
							<option value="Pediatrics">Pediatrics</option>
							<option value="Cardiology">Cardiology</option>
							<option value="Neurology">Neurology</option>
							<option value="Orthopedics">Orthopedics</option>
							<option value="Anesthesiology">Anesthesiology</option>
							<option value="Emergency Medicine">Emergency Medicine</option>
							<option value="Pathology">Pathology</option>
							<option value="Dermatology">Dermatology</option>
							<option value="Psychiatry">Psychiatry</option>
							<option value="Ophthalmology">Ophthalmology</option>
							<option value="ENT">ENT</option>
							<option value="Urology">Urology</option>
							<option value="Gynecology">Gynecology</option>
							<option value="Oncology">Oncology</option>
							<option value="Other">Other</option>
						</select>
						<label for="specialization" class="text-gray-500">Specialization</label>
					</div>

					<!-- Experience Level -->
					<div class="form-floating">
						<select id="experience_years" 
								name="experience_years" 
								class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
							<option value="">Select Experience Level</option>
							<option value="0">Medical Student</option>
							<option value="1">Intern (1st Year)</option>
							<option value="2">Resident (2nd Year)</option>
							<option value="3">Resident (3rd Year)</option>
							<option value="4">Resident (4th Year)</option>
							<option value="5">Fellow (5+ Years)</option>
							<option value="6">Attending Physician</option>
						</select>
						<label for="experience_years" class="text-gray-500">Experience Level</label>
					</div>

					<!-- Current Institution -->
					<div class="form-floating">
						<input type="text" 
							   id="institution" 
							   name="institution" 
							   class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
							   placeholder="Medical School/Hospital">
						<label for="institution" class="text-gray-500">Current Institution (Optional)</label>
					</div>

					<div class="flex space-x-4">
						<button type="button" 
								onclick="prevStep()" 
								class="flex-1 flex justify-center py-4 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
							<i class="fas fa-arrow-left mr-2"></i>
							Back
						</button>
						<button type="button" 
								onclick="nextStep()" 
								class="flex-1 flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
							Continue
							<i class="fas fa-arrow-right ml-2"></i>
						</button>
					</div>
				</div>

				<!-- Step 3: Security -->
				<div id="step3" class="step inactive space-y-4">
					<div class="text-center mb-6">
						<h3 class="text-lg font-semibold text-darken">Account Security</h3>
						<p class="text-sm text-gray-600">Create a secure password</p>
					</div>

					<!-- Password -->
					<div class="form-floating">
						<input type="password" 
							   id="password" 
							   name="password" 
							   class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
							   placeholder="Password"
							   required>
						<label for="password" class="text-gray-500">Password</label>
					</div>

					<!-- Password Strength Indicator -->
					<div class="space-y-2">
						<div class="flex justify-between text-xs text-gray-600">
							<span>Password Strength</span>
							<span id="strengthText">Enter password</span>
						</div>
						<div class="w-full bg-gray-200 rounded-full h-1">
							<div id="strengthBar" class="password-strength bg-gray-300 rounded-full"></div>
						</div>
					</div>

					<!-- Confirm Password -->
					<div class="form-floating">
						<input type="password" 
							   id="confirm_password" 
							   name="confirm_password" 
							   class="form-control w-full px-3 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
							   placeholder="Confirm Password"
							   required>
						<label for="confirm_password" class="text-gray-500">Confirm Password</label>
					</div>

					<!-- Terms and Conditions -->
					<div class="flex items-start">
						<div class="flex items-center h-5">
							<input id="terms" 
								   name="terms" 
								   type="checkbox" 
								   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
								   required>
						</div>
						<div class="ml-3 text-sm">
							<label for="terms" class="text-gray-600">
								I agree to the 
								<a href="#" class="text-blue-600 hover:text-blue-700">Terms and Conditions</a> 
								and 
								<a href="#" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
							</label>
						</div>
					</div>

					<!-- Newsletter Subscription -->
					<div class="flex items-start">
						<div class="flex items-center h-5">
							<input id="newsletter" 
								   name="newsletter" 
								   type="checkbox" 
								   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
								   checked>
						</div>
						<div class="ml-3 text-sm">
							<label for="newsletter" class="text-gray-600">
								Subscribe to our newsletter for medical updates and course announcements
							</label>
						</div>
					</div>

					<div class="flex space-x-4">
						<button type="button" 
								onclick="prevStep()" 
								class="flex-1 flex justify-center py-4 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
							<i class="fas fa-arrow-left mr-2"></i>
							Back
						</button>
						<button type="submit" 
								id="submitBtn"
								class="flex-1 flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
							<span id="submitText">Create Account</span>
							<i id="submitIcon" class="fas fa-user-plus ml-2"></i>
							<i id="loadingIcon" class="fas fa-spinner fa-spin ml-2 hidden"></i>
						</button>
					</div>
				</div>

				<!-- Progress Indicator -->
				<div class="flex justify-center mt-8">
					<div class="flex space-x-2">
						<div id="progress1" class="w-3 h-3 rounded-full bg-blue-600"></div>
						<div id="progress2" class="w-3 h-3 rounded-full bg-gray-300"></div>
						<div id="progress3" class="w-3 h-3 rounded-full bg-gray-300"></div>
					</div>
				</div>
			</form>

			<!-- Login Link -->
			<div class="text-center">
				<p class="text-sm text-gray-600">
					Already have an account? 
					<a href="/login" class="font-medium text-blue-600 hover:text-blue-500">
						Sign in here
					</a>
				</p>
			</div>

			<!-- Social Proof -->
			<div class="mt-8 text-center">
				<p class="text-xs text-gray-500 mb-4">Trusted by medical professionals worldwide</p>
				<div class="flex justify-center space-x-6 text-gray-400">
					<i class="fas fa-hospital text-lg"></i>
					<i class="fas fa-user-md text-lg"></i>
					<i class="fas fa-stethoscope text-lg"></i>
					<i class="fas fa-heartbeat text-lg"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Success/Error Messages -->
	<div id="messageContainer" class="fixed top-4 right-4 z-50 hidden">
		<div id="messageBox" class="bg-white border-l-4 p-4 rounded-lg shadow-lg max-w-sm">
			<div class="flex items-center">
				<i id="messageIcon" class="text-lg mr-3"></i>
				<div>
					<p id="messageText" class="text-sm font-medium"></p>
				</div>
				<button onclick="hideMessage()" class="ml-4 text-gray-400 hover:text-gray-600">
					<i class="fas fa-times"></i>
				</button>
			</div>
		</div>
	</div>

	<script>
		let currentStep = 1;
		const totalSteps = 3;

		// Step Navigation
		function nextStep() {
			if (currentStep < totalSteps && validateCurrentStep()) {
				document.getElementById(`step${currentStep}`).classList.remove('active');
				document.getElementById(`step${currentStep}`).classList.add('prev');
				document.getElementById(`progress${currentStep}`).classList.add('bg-blue-600');
				document.getElementById(`progress${currentStep}`).classList.remove('bg-gray-300');
				
				currentStep++;
				
				document.getElementById(`step${currentStep}`).classList.remove('inactive');
				document.getElementById(`step${currentStep}`).classList.add('active');
				document.getElementById(`progress${currentStep}`).classList.add('bg-blue-600');
				document.getElementById(`progress${currentStep}`).classList.remove('bg-gray-300');
			}
		}

		function prevStep() {
			if (currentStep > 1) {
				document.getElementById(`step${currentStep}`).classList.remove('active');
				document.getElementById(`step${currentStep}`).classList.add('inactive');
				document.getElementById(`progress${currentStep}`).classList.remove('bg-blue-600');
				document.getElementById(`progress${currentStep}`).classList.add('bg-gray-300');
				
				currentStep--;
				
				document.getElementById(`step${currentStep}`).classList.remove('prev');
				document.getElementById(`step${currentStep}`).classList.add('active');
			}
		}

		// Validation
		function validateCurrentStep() {
			if (currentStep === 1) {
				const firstName = document.getElementById('first_name').value.trim();
				const lastName = document.getElementById('last_name').value.trim();
				const email = document.getElementById('email').value.trim();
				
				if (!firstName || !lastName || !email) {
					showMessage('Please fill in all required fields', 'error');
					return false;
				}
				
				if (!isValidEmail(email)) {
					showMessage('Please enter a valid email address', 'error');
					return false;
				}
			}
			
			if (currentStep === 2) {
				// Professional info is optional, so always valid
				return true;
			}
			
			return true;
		}

		function isValidEmail(email) {
			return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
		}

		// Password Strength
		document.getElementById('password').addEventListener('input', function() {
			const password = this.value;
			const strength = getPasswordStrength(password);
			updatePasswordStrength(strength);
		});

		function getPasswordStrength(password) {
			let score = 0;
			if (password.length >= 8) score++;
			if (/[a-z]/.test(password)) score++;
			if (/[A-Z]/.test(password)) score++;
			if (/[0-9]/.test(password)) score++;
			if (/[^A-Za-z0-9]/.test(password)) score++;
			return score;
		}

		function updatePasswordStrength(strength) {
			const strengthBar = document.getElementById('strengthBar');
			const strengthText = document.getElementById('strengthText');
			
			strengthBar.className = 'password-strength rounded-full';
			
			switch (strength) {
				case 0:
				case 1:
					strengthBar.classList.add('strength-weak');
					strengthText.textContent = 'Weak';
					break;
				case 2:
					strengthBar.classList.add('strength-fair');
					strengthText.textContent = 'Fair';
					break;
				case 3:
					strengthBar.classList.add('strength-good');
					strengthText.textContent = 'Good';
					break;
				case 4:
				case 5:
					strengthBar.classList.add('strength-strong');
					strengthText.textContent = 'Strong';
					break;
			}
		}

		// Form Submission
		document.getElementById('registerForm').addEventListener('submit', async function(e) {
			e.preventDefault();
			
			const password = document.getElementById('password').value;
			const confirmPassword = document.getElementById('confirm_password').value;
			
			if (password !== confirmPassword) {
				showMessage('Passwords do not match', 'error');
				return;
			}
			
			if (password.length < 8) {
				showMessage('Password must be at least 8 characters long', 'error');
				return;
			}
			
			if (!document.getElementById('terms').checked) {
				showMessage('Please agree to the Terms and Conditions', 'error');
				return;
			}
			
			const submitBtn = document.getElementById('submitBtn');
			const submitText = document.getElementById('submitText');
			const submitIcon = document.getElementById('submitIcon');
			const loadingIcon = document.getElementById('loadingIcon');
			
			// Show loading state
			submitBtn.disabled = true;
			submitText.textContent = 'Creating Account...';
			submitIcon.classList.add('hidden');
			loadingIcon.classList.remove('hidden');
			
			try {
				const formData = new FormData(this);
				const response = await fetch('/auth/register', {
					method: 'POST',
					body: formData
				});
				
				const result = await response.json();
				
				if (result.success) {
					showMessage(result.message, 'success');
					setTimeout(() => {
						window.location.href = result.redirect || '/login';
					}, 2000);
				} else {
					showMessage(result.error || 'Registration failed', 'error');
				}
			} catch (error) {
				showMessage('Network error. Please try again.', 'error');
			} finally {
				// Reset button state
				submitBtn.disabled = false;
				submitText.textContent = 'Create Account';
				submitIcon.classList.remove('hidden');
				loadingIcon.classList.add('hidden');
			}
		});

		// Message System
		function showMessage(message, type) {
			const container = document.getElementById('messageContainer');
			const box = document.getElementById('messageBox');
			const icon = document.getElementById('messageIcon');
			const text = document.getElementById('messageText');
			
			container.classList.remove('hidden');
			text.textContent = message;
			
			if (type === 'success') {
				box.className = 'bg-white border-l-4 border-green-500 p-4 rounded-lg shadow-lg max-w-sm';
				icon.className = 'fas fa-check-circle text-green-500 text-lg mr-3';
			} else {
				box.className = 'bg-white border-l-4 border-red-500 p-4 rounded-lg shadow-lg max-w-sm';
				icon.className = 'fas fa-exclamation-circle text-red-500 text-lg mr-3';
			}
		}

		function hideMessage() {
			document.getElementById('messageContainer').classList.add('hidden');
		}

		// Auto-hide messages after 5 seconds
		setInterval(() => {
			const container = document.getElementById('messageContainer');
			if (!container.classList.contains('hidden')) {
				setTimeout(hideMessage, 5000);
			}
		}, 1000);
	</script>
</body>
</html>
