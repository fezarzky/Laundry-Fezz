<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes for Laundry Fezz Application
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', function () {
    return view('login'); // We'll create this view
});

Route::post('/login', function () {
    require app_path('../process/login_process.php');
});

Route::get('/register', function () {
    return view('register'); // We'll create this view
});

Route::post('/register', function () {
    require app_path('../process/register_process.php');
});

Route::get('/logout', function () {
    require app_path('../logout.php');
});

// Protected Routes (require authentication)
Route::middleware(['web'])->group(function () {
    
    // Dashboard
    Route::get('/', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return redirect('/login');
        }
        
        // Include the dashboard file
        ob_start();
        include app_path('../index.php');
        return ob_get_clean();
    });
    
    Route::get('/dashboard', function () {
        return redirect('/');
    });
    
    // Transaksi Routes
    Route::get('/transaksi', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return redirect('/login');
        }
        
        ob_start();
        include app_path('../pages/transaksi/index.php');
        return ob_get_clean();
    });
    
    // Paket Routes
    Route::get('/paket', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return redirect('/login');
        }
        
        ob_start();
        include app_path('../pages/paket/index.php');
        return ob_get_clean();
    });
    
    // Pelanggan Routes
    Route::get('/pelanggan', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return redirect('/login');
        }
        
        ob_start();
        include app_path('../pages/pelanggan/index.php');
        return ob_get_clean();
    });
    
    // User Routes (Admin only)
    Route::get('/user', function () {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['akses_id'] != 1) {
            return redirect('/login');
        }
        
        ob_start();
        include app_path('../pages/user/index.php');
        return ob_get_clean();
    });
    
    // Profile Routes
    Route::get('/profile', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return redirect('/login');
        }
        
        ob_start();
        include app_path('../pages/profile/index.php');
        return ob_get_clean();
    });
    
});

// Process Routes
Route::post('/process/login_process', function () {
    require app_path('../process/login_process.php');
});

Route::post('/process/register_process', function () {
    require app_path('../process/register_process.php');
});

// API Routes for AJAX requests
Route::prefix('api')->group(function () {
    
    Route::post('/transaksi/process', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        require app_path('../pages/transaksi/process.php');
    });
    
    Route::post('/paket/process', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        require app_path('../pages/paket/process.php');
    });
    
    Route::post('/pelanggan/process', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        require app_path('../pages/pelanggan/process.php');
    });
    
    Route::post('/user/process', function () {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['akses_id'] != 1) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        require app_path('../pages/user/process.php');
    });
    
    Route::get('/user/get_user_detail', function () {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['akses_id'] != 1) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        require app_path('../pages/user/get_user_detail.php');
    });
    
    Route::post('/profile/process', function () {
        session_start();
        if (!isset($_SESSION['username'])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        require app_path('../pages/profile/process.php');
    });
    
});

// Fallback route for Laravel's default welcome page
Route::get('/welcome', function () {
    return view('welcome');
});