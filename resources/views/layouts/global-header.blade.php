{{-- <section class="bg-gradient-to-r from-green-400 to-blue-500 py-4 shadow-md">
<section class="bg-gradient-to-r from-blue-400 to-cyan-500 py-4 shadow-md"> --}}

<!-- Header Section 1 -->
<header class="bg-white shadow-md py-3">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Left: Logo and Site Name -->
        <div class="flex items-center space-x-4">
            <img src="/images/logo.png" alt="logo" class="w-12 h-12">
            <h1 class="text-xl font-bold text-gray-800">Poefy</h1>
        </div>

        <!-- Right: Username and Logout -->
        <div class="flex items-center space-x-4">
            @auth
                <span class="text-gray-700 font-medium">Welcome, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a>
                <a href="{{ route('register') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Sign Up</a>
            @endauth
        </div>
    </div>
</header>

<!-- Header Section 2 -->
<nav class="bg-gray-100 shadow-inner py-2">
    <div class="container mx-auto flex justify-center space-x-8">
        <!-- Centered Navigation Links -->
        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
        <a href="{{ route('popular.exams') }}" class="text-gray-700 hover:text-blue-600">Popular Exams</a>
        <a href="{{ route('all.exams') }}" class="text-gray-700 hover:text-blue-600">All Exams</a>
        <a href="{{ route('exams.create') }}" class="text-gray-700 hover:text-blue-600">Create Exams</a>
        <a href="{{ route('profile.edit')}}" class="text-gray-700 hover:text-blue-600">My Page</a> <!-- Added My Page link -->

        <!-- Search Bar -->
        <form class="relative flex items-center">
            <input type="text" name="search" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search...">
            <button type="submit" class="absolute right-0 bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">Search</button>
        </form>
    </div>
</nav>
