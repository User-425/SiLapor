<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLapor - Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animations */
        @keyframes logo-pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
            }
        }
        
        @keyframes icon-bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-3px);
            }
            60% {
                transform: translateY(-2px);
            }
        }
        
        .logo-pulse {
            animation: logo-pulse 2s infinite;
        }
        
        .icon-bounce {
            animation: icon-bounce 2s infinite;
        }
        
        /* Improved image display */
        @media (max-width: 767px) {
            .image-container {
                aspect-ratio: 16/9;
                max-height: 280px;
            }
        }
        
        @media (min-width: 768px) {
            .image-container {
                height: 100%;
            }
        }
        
        /* Subtle gradient background */
        .bg-gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Form input focus effects */
        .input-focus:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }
        
        /* Button hover effects */
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.25);
        }

        /* Error message styling */
        .error-message {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left side with image -->
        <div class="w-full md:w-1/2 bg-gradient-custom md:h-screen flex items-center justify-center overflow-hidden relative">
            <!-- Subtle decorative elements -->
            <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute bottom-20 right-16 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            
            <div class="image-container w-full relative">
                <img src="{{ asset('img/Frame 1.png') }}" alt="SiLapor Illustration"
                     class="w-full h-full object-contain sm:object-cover object-center drop-shadow-lg">
            </div>
        </div>

        <!-- Right side with forgot password form -->
        <div class="w-full md:w-1/2 flex items-center justify-center py-8 px-4 sm:p-6 md:p-8 lg:p-12 bg-white">
            <div class="w-full max-w-sm sm:max-w-md">
                <!-- Logo with new icon -->
                <div class="flex justify-center mb-8 sm:mb-10">
                    <div class="relative">
                        <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg logo-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 icon-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 h-4 w-4 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                    </div>
                </div>

                <!-- Forgot password text -->
                <div class="text-center mb-8 sm:mb-10">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-2 sm:mb-3">
                        Forgot Password
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed px-2 sm:px-0">
                        Enter your email address to receive a password reset link.
                    </p>
                </div>

                <!-- Forgot password form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6 sm:space-y-7">
                    @csrf
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input id="email" name="email" type="email" required
                            class="input-focus w-full px-4 py-3 sm:px-5 sm:py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                            placeholder="Enter your email">
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn-hover w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 sm:py-4 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm sm:text-base transition-all duration-200 shadow-lg">
                            Send Reset Link
                        </button>
                    </div>
                </form>

                <!-- Back to login link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-700 transition-colors duration-200">
                        Back to Login
                    </a>
                </div>

                <!-- Footer text -->
                <div class="mt-8 text-center">
                    <p class="text-xs sm:text-sm text-gray-500">
                        Secure password reset process
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>