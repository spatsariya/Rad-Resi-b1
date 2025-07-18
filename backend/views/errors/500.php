<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - Radiology Resident</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-6xl text-red-600 mb-4">
                <i class="fas fa-server"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">500</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Server Error</h2>
            <p class="text-gray-600 mb-8">
                Something went wrong on our end. Please try again later.
            </p>
            <div class="space-y-4">
                <button onclick="window.location.reload()" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    <i class="fas fa-refresh mr-2"></i>
                    Try Again
                </button>
                <a href="/" class="block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition duration-300">
                    <i class="fas fa-home mr-2"></i>
                    Go Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
