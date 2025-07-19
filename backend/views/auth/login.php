<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Login - Radiology Resident'; ?></title>
	<meta name="description" content="<?php echo $description ?? 'Login to your Radiology Resident account'; ?>">
	
	<!-- Favicon -->
	<link rel="icon" type="image/svg+xml" href="/backend/assets/svg/light-icon.svg">
	<link rel="alternate icon" href="/backend/assets/svg/light-icon.svg">
	
	<!-- Tailwind CSS -->
	<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
	<!-- Custom Logo Styles -->
	<link rel="stylesheet" href="/backend/assets/css/logo-styles.css">
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
	</style>
</head>
<body class="bg-cream min-h-screen flex items-center justify-center">
	<div class="max-w-md w-full space-y-8 p-8">
		<!-- Logo and Header -->
		<div class="text-center">
			<div class="relative inline-block mb-6">
				<a href="/" class="inline-block">
					<img src="/backend/assets/svg/logo.svg" alt="Radiology Resident" class="logo-login">
				</a>
			</div>
			<h2 class="text-3xl font-bold text-darken mb-2">Welcome Back</h2>
			<p class="text-gray-600">Sign in to your account to continue learning</p>
		</div>

		<!-- Login Form -->
		<div class="bg-white rounded-xl shadow-xl p-8">
			<!-- Alert Messages -->
			<div id="alert-container" class="mb-4 hidden">
				<div id="alert-message" class="p-4 rounded-lg text-sm"></div>
			</div>

			<form id="loginForm" class="space-y-6">
				<!-- CSRF Token -->
				<input type="hidden" name="csrf_token" value="<?php echo $this->generateCSRFToken(); ?>">
				
				<!-- Email Field -->
				<div>
					<label for="email" class="block text-sm font-medium text-gray-700 mb-1">
						Email Address
					</label>
					<div class="relative">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
							<i class="fas fa-envelope text-gray-400"></i>
						</div>
						<input 
							id="email" 
							name="email" 
							type="email" 
							autocomplete="email" 
							required 
							class="appearance-none rounded-lg relative block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:z-10 sm:text-sm"
							placeholder="Enter your email address"
						>
					</div>
				</div>

				<!-- Password Field -->
				<div>
					<label for="password" class="block text-sm font-medium text-gray-700 mb-1">
						Password
					</label>
					<div class="relative">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
							<i class="fas fa-lock text-gray-400"></i>
						</div>
						<input 
							id="password" 
							name="password" 
							type="password" 
							autocomplete="current-password" 
							required 
							class="appearance-none rounded-lg relative block w-full pl-10 pr-12 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:z-10 sm:text-sm"
							placeholder="Enter your password"
						>
						<button 
							type="button" 
							id="togglePassword"
							class="absolute inset-y-0 right-0 pr-3 flex items-center"
						>
							<i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
						</button>
					</div>
				</div>

				<!-- Remember Me & Forgot Password -->
				<div class="flex items-center justify-between">
					<div class="flex items-center">
						<input 
							id="remember-me" 
							name="remember-me" 
							type="checkbox" 
							class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
						>
						<label for="remember-me" class="ml-2 block text-sm text-gray-900">
							Remember me
						</label>
					</div>

					<div class="text-sm">
						<a href="/forgot-password" class="font-medium text-blue-600 hover:text-blue-500">
							Forgot your password?
						</a>
					</div>
				</div>

				<!-- Submit Button -->
				<div>
					<button 
						type="submit" 
						id="loginBtn"
						class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:scale-105"
					>
						<span class="absolute left-0 inset-y-0 flex items-center pl-3">
							<i class="fas fa-sign-in-alt text-blue-300 group-hover:text-blue-200"></i>
						</span>
						<span id="loginBtnText">Sign In</span>
						<i id="loginSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
					</button>
				</div>

				<!-- Register Link -->
				<div class="text-center">
					<p class="text-sm text-gray-600">
						Don't have an account? 
						<a href="/register" class="font-medium text-blue-600 hover:text-blue-500">
							Create one here
						</a>
					</p>
				</div>
			</form>
		</div>

		<!-- Features -->
		<div class="text-center">
			<p class="text-sm text-gray-500 mb-4">Join thousands of medical professionals advancing their radiology skills</p>
			<div class="flex justify-center space-x-6 text-xs text-gray-400">
				<div class="flex items-center">
					<i class="fas fa-shield-alt mr-1"></i>
					<span>Secure</span>
				</div>
				<div class="flex items-center">
					<i class="fas fa-graduation-cap mr-1"></i>
					<span>Expert-Led</span>
				</div>
				<div class="flex items-center">
					<i class="fas fa-certificate mr-1"></i>
					<span>CME Credits</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Floating Medical Icons (decorative) -->
	<div class="fixed top-20 left-10 floating opacity-20">
		<div class="bg-white bg-opacity-50 rounded-lg p-3">
			<i class="fas fa-x-ray text-blue-500 text-xl"></i>
		</div>
	</div>
	<div class="fixed bottom-20 right-10 floating opacity-20" style="animation-delay: 1s;">
		<div class="bg-white bg-opacity-50 rounded-lg p-3">
			<i class="fas fa-brain text-green-500 text-xl"></i>
		</div>
	</div>
	<div class="fixed top-1/2 right-20 floating opacity-20" style="animation-delay: 2s;">
		<div class="bg-white bg-opacity-50 rounded-lg p-3">
			<i class="fas fa-lungs text-red-500 text-xl"></i>
		</div>
	</div>

	<script>
		// Toggle password visibility
		document.getElementById('togglePassword').addEventListener('click', function() {
			const passwordInput = document.getElementById('password');
			const eyeIcon = this.querySelector('i');
			
			if (passwordInput.type === 'password') {
				passwordInput.type = 'text';
				eyeIcon.classList.remove('fa-eye');
				eyeIcon.classList.add('fa-eye-slash');
			} else {
				passwordInput.type = 'password';
				eyeIcon.classList.remove('fa-eye-slash');
				eyeIcon.classList.add('fa-eye');
			}
		});

		// Handle form submission
		document.getElementById('loginForm').addEventListener('submit', async function(e) {
			e.preventDefault();
			
			const loginBtn = document.getElementById('loginBtn');
			const loginBtnText = document.getElementById('loginBtnText');
			const loginSpinner = document.getElementById('loginSpinner');
			const alertContainer = document.getElementById('alert-container');
			const alertMessage = document.getElementById('alert-message');
			
			// Show loading state
			loginBtn.disabled = true;
			loginBtnText.textContent = 'Signing In...';
			loginSpinner.classList.remove('hidden');
			alertContainer.classList.add('hidden');
			
			try {
				const formData = new FormData(this);
				
				const response = await fetch('/auth/login', {
					method: 'POST',
					body: formData
				});
				
				const data = await response.json();
				
				if (data.success) {
					// Show success message
					alertMessage.textContent = data.message || 'Login successful! Redirecting...';
					alertMessage.className = 'p-4 rounded-lg text-sm bg-green-100 text-green-800 border border-green-200';
					alertContainer.classList.remove('hidden');
					
					// Redirect after short delay
					setTimeout(() => {
						window.location.href = data.redirect || '/admin';
					}, 1000);
				} else {
					throw new Error(data.error || 'Login failed');
				}
				
			} catch (error) {
				// Show error message
				alertMessage.textContent = error.message;
				alertMessage.className = 'p-4 rounded-lg text-sm bg-red-100 text-red-800 border border-red-200';
				alertContainer.classList.remove('hidden');
				
				// Reset button state
				loginBtn.disabled = false;
				loginBtnText.textContent = 'Sign In';
				loginSpinner.classList.add('hidden');
			}
		});

		// Auto-hide alerts after 5 seconds
		function hideAlert() {
			const alertContainer = document.getElementById('alert-container');
			if (!alertContainer.classList.contains('hidden')) {
				setTimeout(() => {
					alertContainer.classList.add('hidden');
				}, 5000);
			}
		}

		// Hide alert when form is focused again
		document.getElementById('email').addEventListener('focus', hideAlert);
		document.getElementById('password').addEventListener('focus', hideAlert);
	</script>
</body>
</html>
