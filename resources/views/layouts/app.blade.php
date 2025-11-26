<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Absensi BPKAD Garut')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #f59e0b;
            --info: #0891b2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 24px;
            border-bottom: none;
        }

        .card-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .card-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .card-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 15px;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .form-label .icon {
            font-size: 18px;
        }

        .required {
            color: var(--danger);
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: #f8fafc;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-hint {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .alert-danger {
            background-color: #fee2e2;
            border-left: 4px solid var(--danger);
            color: #991b1b;
        }

        .alert-success {
            background-color: #dcfce7;
            border-left: 4px solid var(--success);
            color: #15803d;
        }

        .alert-info {
            background-color: #cffafe;
            border-left: 4px solid var(--info);
            color: #164e63;
        }

        .alert ul {
            margin-top: 8px;
            padding-left: 20px;
        }

        .alert li {
            margin-bottom: 4px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .btn-secondary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(107, 114, 128, 0.3);
        }

        .btn-block {
            width: 100%;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .preview-container {
            margin-top: 12px;
            display: none;
            border: 2px dashed var(--primary);
            border-radius: 8px;
            padding: 12px;
            background-color: #f0f9ff;
        }

        .preview-container.active {
            display: block;
        }

        .preview-container img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 4px;
        }

        .location-info {
            display: none;
            margin-top: 12px;
            padding: 14px;
            background-color: #f0fdf4;
            border: 2px solid var(--success);
            border-radius: 8px;
        }

        .location-info.active {
            display: block;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            margin: 20px 0;
        }

        .loading.active {
            display: block;
        }

        .spinner {
            border: 4px solid #e5e7eb;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 12px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 16px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 32px;
        }
        
        .navbar.admin-hidden {
            display: none;
        }
        
        body.admin-page {
            background: #f9fafb;
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .container-main {
            max-width: 680px;
            margin: 0 auto;
            padding: 0 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar (Hidden on admin pages) -->
    <nav class="navbar @if(auth()->check() && auth()->user()->role === 'admin') admin-hidden @endif">
        <div class="navbar-content">
            <div class="navbar-brand">
                <img src="{{ asset('image/logonav.png') }}" alt="Logo">
                <span>Absensi BPKAD Garut</span>
            </div>
            <div>
                @auth
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <span style="font-size: 14px;"> {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary" style="padding: 8px 16px; font-size: 12px;">
                                 Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-main">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
