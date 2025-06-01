<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLapor - Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left side with image -->
        <div class="w-full md:w-1/2 bg-indigo-600 relative overflow-hidden h-screen">
            <img src="{{ asset('img/Frame 1.png') }}" alt="SiLapor Illustration" 
                 class="absolute inset-0 w-full h-full object-cover object-center max-h-screen">
        </div>
        
        <!-- Right side with login form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-8">
                    <div class="bg-gray-200 rounded-full p-3">
                        <div class="bg-green-500 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <h2 class="text-4xl font-bold text-center mb-2">Welcome</h2>
                <p class="text-gray-600 text-center mb-8">Lorem ipsum dolor sit amet consectetur. Aenean consectetur leo dolor a netus eu.</p>
                
                <form method="POST" action="" class="space-y-6">
                    @csrf
                    <div>
                        <input id="nama_pengguna" name="nama_pengguna" type="text" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Username">
                    </div>
                    
                    <div>
                        <input id="kata_sandi" name="kata_sandi" type="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Password">
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="" class="text-indigo-600 hover:text-indigo-800">
                            Forgot your password?
                        </a>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Sign in
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <a href="" class="text-gray-600 hover:text-gray-800">
                        Create new account
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>