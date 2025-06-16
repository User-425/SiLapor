<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLapor - Reset Password</title>
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

        /* Decorative elements */
        .decorative-element-1 {
            position: absolute;
            top: 10%;
            left: 8%;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            filter: blur(20px);
        }

        .decorative-element-2 {
            position: absolute;
            bottom: 15%;
            right: 10%;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            filter: blur(30px);
        }

        .decorative-element-3 {
            position: absolute;
            top: 50%;
            left: 15%;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            filter: blur(25px);
        }
    </style>
</head>
<body class="bg-gradient-custom min-h-screen">
    <!-- Decorative elements -->
    <div class="decorative-element-1"></div>
    <div class="decorative-element-2"></div>
    <div class="decorative-element-3"></div>

    <!-- Centered form container -->
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 relative z-10">
            <!-- Logo with lock icon -->
            <div class="flex justify-center mb-8">
                <div class="relative">
                    <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg logo-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 icon-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="absolute -top-1 -right-1 h-4 w-4 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                </div>
            </div>

            <!-- Reset password text -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-3">
                    Reset Password
                </h1>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Enter your new password to secure your account.
                </p>
            </div>

            <!-- Reset password form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <!-- Password field -->
                <div class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input id="password" name="password" type="password" required
                        class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-sm @error('password') border-red-500 @enderror"
                        placeholder="Enter new password" autocomplete="new-password">
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password confirmation field -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-sm"
                        placeholder="Confirm new password" autocomplete="new-password">
                </div>

                <!-- Submit button -->
                <div class="pt-4">
                    <button type="submit" class="btn-hover w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm transition-all duration-200 shadow-lg">
                        Reset Password
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
                <p class="text-xs text-gray-500">
                    Secure password reset process
                </p>
            </div>
        </div>
    </div>

    <!-- Optional: Show status messages -->
    @if (session('status'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 text-sm">
            {{ session('status') }}
        </div>
    @endif
</body>
</html>