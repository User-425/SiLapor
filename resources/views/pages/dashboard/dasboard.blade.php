<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>SiLapor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
      <!-- Sidebar -->
      <div class="w-64 bg-white shadow-lg flex flex-col">
        <div class="flex items-center p-4 border-b">
          <img class="w-10 h-10" src="img/dummy.png" alt="Logo" />
          <span class="ml-3 text-xl font-bold text-gray-800">SiLapor</span>
        </div>
        <div class="flex-1 p-4">
          <nav class="space-y-2">
            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
              <img class="w-6 h-6" src="img/graph-1.png" alt="Dashboard icon" />
              <span class="ml-3 text-gray-700">Dashboard</span>
            </a>
            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
              <img class="w-6 h-6" src="img/group.png" alt="Leaderboard icon" />
              <span class="ml-3 text-gray-700">Leaderboard</span>
            </a>
            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
              <img class="w-6 h-6" src="img/shopping-cart.svg" alt="Order icon" />
              <span class="ml-3 text-gray-700">Order</span>
            </a>
            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
              <img class="w-6 h-6" src="img/mdi-shopping-outline.svg" alt="Products icon" />
              <span class="ml-3 text-gray-700">Products</span>
            </a>
            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
              <img class="w-6 h-6" src="img/chart-line.svg" alt="Sales Report icon" />
              <span class="ml-3 text-gray-700">Sales Report</span>
            </a>
            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
              <img class="w-6 h-6" src="img/mdi-message-processing-outline.svg" alt="Messages icon" />
              <span class="ml-3 text-gray-700">Messages</span>
            </a>
            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
              <img class="w-6 h-6" src="img/mdi-cog-outline.svg" alt="Settings icon" />
              <span class="ml-3 text-gray-700">Settings</span>
            </a>
            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100 text-red-600">
              <img class="w-6 h-6" src="img/union.svg" alt="Sign Out icon" />
              <span class="ml-3">Sign Out</span>
            </a>
          </nav>
        </div>
      </div>
      <!-- Main Content -->
      <div class="flex-1 flex flex-col">
        <!-- Top Bar -->
        <div class="bg-white shadow p-4 flex items-center justify-between">
          <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
          <div class="flex items-center space-x-4">
            <div class="relative">
              <img class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2" src="img/magnifier.svg" alt="Search icon" />
              <input type="text" placeholder="Search here..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div class="flex items-center space-x-2">
              <div class="relative">
                <img class="w-6 h-6" src="img/clarity-notification-line.svg" alt="Notification icon" />
                <img class="w-3 h-3 absolute top-0 right-0" src="img/vector.svg" alt="Notification dot" />
              </div>
              <div class="flex items-center space-x-2">
                <img class="w-10 h-10 rounded-full" src="img/rectangle-1393.png" alt="Profile" />
                <div>
                  <p class="text-sm text-gray-600">Admin</p>
                  <p class="text-sm font-medium text-gray-800">Musfiq</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Content Area -->
        <div class="p-6">
          <!-- Add dashboard content here -->
        </div>
      </div>
    </div>
  </body>
</html>