<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">

            <h1 class="text-8xl font-bold text-primary-600">
                404
            </h1>

            <h2 class="mt-4 text-2xl font-bold text-gray-800">
                Page Not Found
            </h2>

            <p class="mt-2 text-gray-500">
                Sorry, the page you are looking for doesn't exist.
            </p>

            <a href="{{ url('/') }}"
               class="inline-block mt-6 px-5 py-3 bg-primary-600 font-bold text-primary-600 rounded-lg">
                Go to Home
            </a>

        </div>
    </div>
</body>
</html>