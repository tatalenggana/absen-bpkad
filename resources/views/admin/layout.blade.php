<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Absensi BPKAD</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0066cc;
            --primary-dark: #0052a3;
            --primary-light: #4d94ff;
            --success: #00cc66;
            --warning: #ff9900;
            --danger: #ff3333;
            --text-primary: #0d1b2a;
            --text-secondary: #536878;
            --sidebar-bg: #003d7a;
            --sidebar-hover: #004d99;
            --bg-light: #f0f4f8;
            --bg-white: #ffffff;
            --border: #d0d7e0;
            --accent-blue: #3399ff;
            --accent-cyan: #00d4ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .sidebar-header p {
            font-size: 13px;
            opacity: 0.8;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-nav a:hover {
            background-color: var(--sidebar-hover);
            color: var(--accent-blue);
            transform: translateX(4px);
        }

        .sidebar-nav a.active {
            background-color: var(--sidebar-hover);
            color: var(--accent-cyan);
            border-left: 4px solid var(--accent-cyan);
            padding-left: calc(20px - 4px);
            font-weight: 600;
        }

        .sidebar-nav i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout {
            width: 100%;
            background-color: var(--danger);
            border: none;
            color: white;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #dc2626;
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background-color: var(--bg-light);
        }

        .top-header {
            padding: 30px 25px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent-blue) 100%);
            border-bottom: 3px solid var(--accent-cyan);
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 102, 204, 0.15);
        }

        .top-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 6px;
            color: white;
        }

        .top-header p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
            padding: 0 25px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent-blue) 100%);
            border-radius: 12px;
            padding: 25px;
            color: white;
            box-shadow: 0 6px 20px rgba(0, 102, 204, 0.25);
            display: flex;
            flex-direction: column;
            gap: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 102, 204, 0.35);
        }

        .stat-card:hover::before {
            top: -30%;
            right: -30%;
        }

        .stat-card.success {
            background: linear-gradient(135deg, var(--success) 0%, #00ff99 100%);
            box-shadow: 0 6px 20px rgba(0, 204, 102, 0.25);
        }

        .stat-card.success:hover {
            box-shadow: 0 12px 30px rgba(0, 204, 102, 0.35);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, var(--warning) 0%, #ffaa00 100%);
            box-shadow: 0 6px 20px rgba(255, 153, 0, 0.25);
        }

        .stat-card.warning:hover {
            box-shadow: 0 12px 30px rgba(255, 153, 0, 0.35);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, var(--danger) 0%, #ff6666 100%);
            box-shadow: 0 6px 20px rgba(255, 51, 51, 0.25);
        }

        .stat-card.danger:hover {
            box-shadow: 0 12px 30px rgba(255, 51, 51, 0.35);
        }

        .stat-card i {
            font-size: 32px;
            opacity: 0.8;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Filter Section */
        .filter-section {
            background-color: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px 25px;
            margin-bottom: 25px;
            margin-left: 25px;
            margin-right: 25px;
        }

        .filter-section form {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-control {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            color: var(--text-primary);
            font-family: inherit;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Buttons */
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: 2px solid transparent;
            position: relative;
        }

        .btn-primary:hover {
            background-color: var(--accent-blue);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--accent-cyan);
            color: #0d1b2a;
            border: 2px solid var(--accent-cyan);
            font-weight: 600;
        }

        .btn-secondary:hover {
            background-color: #00b8cc;
            border-color: #00b8cc;
            color: #0d1b2a;
            box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #ff6666;
            box-shadow: 0 4px 15px rgba(255, 51, 51, 0.3);
            transform: translateY(-2px);
        }

        /* Table */
        .table-container {
            background-color: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            margin: 0 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background-color: #f1f5f9;
            border-bottom: 2px solid var(--border);
        }

        .table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }

        .table-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Empty State */
        .empty-state {
            padding: 60px 25px;
            text-align: center;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.4;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        /* Pagination */
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination .active span {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                padding: 0 15px;
            }

            .filter-section {
                margin-left: 15px;
                margin-right: 15px;
            }

            .table-container {
                margin: 0 15px;
            }

            .top-header {
                padding: 20px 15px;
            }

            .table {
                font-size: 13px;
            }

            .table th, .table td {
                padding: 10px 8px !important;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                position: fixed;
                left: -200px;
                width: 200px;
                transition: left 0.3s ease;
                z-index: 2000;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .top-header {
                padding: 15px 12px;
                margin-bottom: 15px;
            }

            .top-header h1 {
                font-size: 22px;
                margin-bottom: 4px;
            }

            .top-header p {
                font-size: 12px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 0 12px;
                margin-bottom: 15px;
            }

            .stat-card {
                padding: 15px;
                gap: 10px;
            }

            .stat-card i {
                font-size: 24px;
            }

            .stat-number {
                font-size: 28px;
            }

            .stat-label {
                font-size: 12px;
            }

            .filter-section {
                margin: 0 12px 15px 12px;
            }

            .filter-section form {
                flex-direction: column;
                gap: 8px;
            }

            .form-control, .btn {
                width: 100%;
                font-size: 14px;
                padding: 8px 12px;
            }

            .table-container {
                margin: 0 12px;
                overflow-x: auto;
            }

            .table {
                font-size: 12px;
                min-width: 600px;
            }

            .table th, .table td {
                padding: 8px 6px !important;
                white-space: nowrap;
            }

            .btn-primary, .btn-secondary {
                padding: 6px 10px;
                font-size: 11px;
            }

            .table-badge {
                padding: 3px 6px;
                font-size: 11px;
            }

            /* Modal responsive */
            #detailModal {
                padding: 12px !important;
            }

            #detailModal > div {
                width: 95% !important;
                max-width: 100% !important;
                max-height: 95vh !important;
                padding: 16px !important;
            }

            #detailModal > div h2 {
                font-size: 18px !important;
            }

            #detailModal > div .card-header {
                padding: 12px !important;
            }

            #detailPhoto {
                max-height: 300px !important;
            }

            /* Toggle sidebar button */
            .sidebar-toggle {
                display: block;
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: var(--primary);
                color: white;
                border: none;
                font-size: 20px;
                cursor: pointer;
                z-index: 1500;
                box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
            }

            .sidebar-toggle:active {
                transform: scale(0.95);
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 180px;
            }

            .top-header {
                padding: 12px 10px;
            }

            .top-header h1 {
                font-size: 20px;
            }

            .stats-grid {
                gap: 12px;
            }

            .stat-card {
                padding: 12px;
            }

            .stat-number {
                font-size: 24px;
            }

            .table {
                font-size: 11px;
            }

            .table th, .table td {
                padding: 6px 4px !important;
            }

            .btn {
                padding: 6px 8px;
                font-size: 10px;
            }

            .filter-section form {
                gap: 6px;
            }

            .form-control {
                font-size: 13px;
                padding: 6px 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin</h2>
            <p>Absensi BPKAD</p>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="@if(Route::current()->getName() == 'admin.dashboard') active @endif">
                <i class="fas fa-chart-line"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.attendance-report') }}" class="@if(Route::current()->getName() == 'admin.attendance-report') active @endif">
                <i class="fas fa-file-alt"></i>
                Laporan Absensi
            </a>
            <a href="{{ route('admin.settings') }}" class="@if(Route::current()->getName() == 'admin.settings') active @endif">
                <i class="fas fa-cog"></i>
                Pengaturan
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Mobile Menu Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking main content on mobile
        mainContent.addEventListener('click', function() {
            if (window.innerWidth <= 600) {
                sidebar.classList.remove('active');
            }
        });

        // Close sidebar when clicking sidebar links
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 600) {
                    sidebar.classList.remove('active');
                }
            });
        });

        // Close sidebar when window is resized above mobile breakpoint
        window.addEventListener('resize', function() {
            if (window.innerWidth > 600) {
                sidebar.classList.remove('active');
                sidebarToggle.style.display = 'none';
            } else {
                sidebarToggle.style.display = 'block';
            }
        });

        // Initial check on page load
        window.addEventListener('load', function() {
            if (window.innerWidth > 600) {
                sidebarToggle.style.display = 'none';
            }
        });
    </script>
</body>
</html>
