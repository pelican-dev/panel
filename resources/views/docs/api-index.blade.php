<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg py-8 px-8 max-w-md w-full text-center">
        <h2 class="text-xl font-bold text-gray-800 mb-4">API Documentation</h2>

        <div class="mb-2">
            <a href="/docs/api/application" class="inline-flex items-center">
                <span class="mr-2">📘</span> <span style="color: #2563EB;">Application API</span>
            </a>
        </div>

        <div class="mb-4">
            <a href="/docs/api/client" class="inline-flex items-center">
                <span class="mr-2">📗</span> <span style="color: #10B981;">Client API</span>
            </a>
        </div>

        <div class="text-sm mt-4 flex items-center justify-center">
            <span class="text-yellow-500 mr-2">⚠️</span>
            <span style="color: #EF4444;">Note: You need to be logged in to view the API docs!</span>
        </div>
    </div>
</body>
</html>
