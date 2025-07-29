<?php
/**
 * Custom PHP Router for Laundry Fezz
 * Simple PHP routing without framework
 */

// Get current request URI
$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

// For different environments, adjust base path
if (strpos($_SERVER['HTTP_HOST'], 'localhost:8000') !== false) {
    // PHP built-in server
    $base_path = '';
} else {
    // Apache/Nginx with subdirectory
    $base_path = '/coba';
}

// Remove base path and query parameters
$route = str_replace($base_path, '', parse_url($request_uri, PHP_URL_PATH));

// Start session once for all routes
session_start();

// Define routes
switch ($route) {
    case '':
    case '/':
        // Redirect to login if not authenticated, otherwise show dashboard
        if (isset($_SESSION['username'])) {
            require_once __DIR__ . '/index.php';
        } else {
            header('Location: /coba/login');
            exit();
        }
        break;
        
    case '/login':
        require_once __DIR__ . '/login.php';
        break;
        
    case '/register':
        require_once __DIR__ . '/register.php';
        break;
        
    case '/logout':
        require_once __DIR__ . '/logout.php';
        break;
        
    case '/dashboard':
        if (isset($_SESSION['username'])) {
            require_once __DIR__ . '/index.php';
        } else {
            header('Location: /coba/login');
            exit();
        }
        break;
        
    case '/transaksi':
        if (isset($_SESSION['username'])) {
            require_once __DIR__ . '/pages/transaksi/index.php';
        } else {
            header('Location: /coba/login');
            exit();
        }
        break;
        
    case '/paket':
        if (isset($_SESSION['username'])) {
            require_once __DIR__ . '/pages/paket/index.php';
        } else {
            header('Location: /coba/login');
            exit();
        }
        break;
        
    case '/pelanggan':
        if (isset($_SESSION['username'])) {
            require_once __DIR__ . '/pages/pelanggan/index.php';
        } else {
            header('Location: /coba/login');
            exit();
        }
        break;
        
    case '/user':
        if (isset($_SESSION['username'])) {
            require_once __DIR__ . '/pages/user/index.php';
        } else {
            header('Location: /coba/login');
            exit();
        }
        break;
        
    case '/profile':
        if (isset($_SESSION['username'])) {
            require_once __DIR__ . '/pages/profile/index.php';
        } else {
            header('Location: /coba/login');
            exit();
        }
        break;
        
    // Process routes
    case '/process/login_process':
        require_once __DIR__ . '/process/login_process.php';
        break;
        
    case '/process/register_process':
        require_once __DIR__ . '/process/register_process.php';
        break;
        
    case '/about':
        require_once __DIR__ . '/resources/views/about.php';
        break;
        
    default:
        // 404 Not Found
        http_response_code(404);
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>404 - Page Not Found</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <h1 class="display-1">404</h1>
                        <h2>Page Not Found</h2>
                        <p class="lead">The page you are looking for does not exist.</p>
                        <a href="/coba/" class="btn btn-primary">Back to Home</a>
                    </div>
                </div>
            </div>
        </body>
        </html>';
        break;
}
?>
