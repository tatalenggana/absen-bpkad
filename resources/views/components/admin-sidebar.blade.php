<!-- Admin Sidebar Navigation -->
<style>
    .admin-sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        height: 100vh;
        background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
        padding-top: 60px;
        z-index: 40;
        overflow-y: auto;
    }
    
    .admin-sidebar nav {
        padding: 24px 0;
    }
    
    .admin-sidebar a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 24px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .admin-sidebar a:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .admin-sidebar a.active {
        background: rgba(255, 255, 255, 0.2);
        border-left: 3px solid white;
    }
    
    .admin-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.2);
        margin: 12px 0;
    }
    
    .admin-info {
        padding: 16px 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>

<div class="admin-sidebar">
    <!-- Navigation Links -->
    <nav>
        <!-- Dashboard Link -->
        <a href="{{ route('admin.dashboard') }}" @class(['active' => Route::currentRouteName() == 'admin.dashboard'])>
            <i class="fas fa-chart-bar"></i>
            <span>Dashboard</span>
        </a>

        <!-- Attendance Report Link -->
        <a href="{{ route('admin.attendance-report') }}" @class(['active' => Route::currentRouteName() == 'admin.attendance-report'])>
            <i class="fas fa-file-alt"></i>
            <span>Laporan Absensi</span>
        </a>

        <!-- Settings Link -->
        <a href="{{ route('admin.settings') }}" @class(['active' => Route::currentRouteName() == 'admin.settings'])>
            <i class="fas fa-cog"></i>
            <span>Pengaturan</span>
        </a>
    </nav>

    <!-- Divider -->
    <div class="admin-divider"></div>

    <!-- Admin Info -->
    <div class="admin-info">
        <p style="font-size: 11px; color: rgba(255, 255, 255, 0.7); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px 0;">Login Sebagai</p>
        <p style="font-size: 13px; color: white; font-weight: 600; margin: 0; margin-bottom: 16px;">{{ Auth::user()->name }}</p>
        
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
            @csr
            <button type="submit" style="width: 100%; background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: white; padding: 10px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>
