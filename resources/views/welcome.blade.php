<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi BPKAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/logonav.png') }}" alt="Logo" class="h-12">
                <h1 class="text-2xl font-bold text-blue-600">Absensi BPKAD</h1>
            </div>
            <div class="flex gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Logout</button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Login</a>
                    <a href="{{ url('/register') }}" class="px-4 py-2 border-2 border-blue-500 text-blue-500 rounded">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-bold text-gray-800 mb-4">Sistem Absensi Terpadu</h2>
            <p class="text-xl text-gray-600 mb-8">Kelola kehadiran karyawan dengan mudah dan efisien</p>
            @guest
                <div class="flex gap-4 justify-center">
                    <a href="{{ url('/login') }}" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg">Login</a>
                    <a href="{{ url('/register') }}" class="px-8 py-3 bg-white text-blue-600 border-2 border-blue-600 font-bold rounded-lg">Register</a>
                </div>
            @endguest
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold text-blue-600 mb-4"><i class="fas fa-check-circle"></i> Check-In/Out</h3>
                <p class="text-gray-700">Catat waktu masuk dan pulang dengan akurat. Sistem otomatis mendeteksi keterlambatan setelah jam 08:00 pagi.</p>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold text-green-600 mb-4"><i class="fas fa-chart-bar"></i> Dashboard Admin</h3>
                <p class="text-gray-700">Pantau kehadiran seluruh karyawan secara real-time dengan filter berdasarkan tanggal dan bulan.</p>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold text-purple-600 mb-4"><i class="fas fa-lock"></i> Keamanan</h3>
                <p class="text-gray-700">Sistem autentikasi yang aman dengan pemisahan role admin dan karyawan.</p>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white text-center py-6 mt-12">
        <p>&copy; 2025 Sistem Absensi BPKAD.</p>
    </footer>
</body>
</html>
