<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            position: fixed;
            transition: all 0.3s;
            z-index: 1000;
            width: 250px;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
            left: 0;
            top: 0;
        }
        
        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: flex-start;
            height: 70px;
        }
        
        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0;
            white-space: nowrap;
        }
        
        .sidebar-nav {
            padding: 0;
            list-style: none;
            margin: 1rem 0;
        }
        
        .sidebar-nav li {
            padding: 0;
            margin-bottom: 5px;
        }
        
        .sidebar-nav li.active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .sidebar-nav a:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav a i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            font-size: 16px;
        }
        
        .sidebar-nav a span {
            white-space: nowrap;
        }
        
        .toggle-btn {
            position: fixed;
            left: 250px;
            top: 10px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            padding: 10px;
            cursor: pointer;
            z-index: 1001;
            transition: all 0.3s;
        }
        
        .collapsed .toggle-btn {
            left: 70px;
        }
        
        .collapsed .sidebar {
            width: 70px;
        }
        
        .collapsed .sidebar .logo-text,
        .collapsed .sidebar .user-name,
        .collapsed .sidebar-nav a span {
            display: none;
        }
        
        .collapsed .sidebar-header {
            justify-content: center;
            padding: 20px 5px;
        }
        
        .collapsed .sidebar-nav a {
            justify-content: center;
            padding: 15px 5px;
        }
        
        .collapsed .sidebar-nav a i {
            margin-right: 0;
            font-size: 18px;
        }
        
        .collapsed .user-info {
            justify-content: center;
            padding: 15px 5px;
        }
        
        .collapsed .user-info img {
            margin-right: 0;
        }
        
        .divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1rem 0;
        }
        
        .user-info {
            padding: 15px;
            display: flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.1);
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        
        /* Menambahkan class untuk main content */
        .main-content {
            transition: all 0.3s;
            margin-left: 250px; /* Sama dengan lebar sidebar */
            padding: 20px;
        }
        
        .collapsed .main-content {
            margin-left: 70px; /* Sama dengan lebar sidebar collapsed */
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .logo-text,
            .sidebar .user-name,
            .sidebar-nav a span {
                display: none;
            }
            
            .sidebar-header {
                justify-content: center;
                padding: 20px 5px;
            }
            
            .sidebar-nav a {
                justify-content: center;
                padding: 15px 5px;
            }
            
            .sidebar-nav a i {
                margin-right: 0;
                font-size: 18px;
            }
            
            .user-info {
                justify-content: center;
                padding: 15px 5px;
            }
            
            .user-info img {
                margin-right: 0;
            }
            
            .toggle-btn {
                left: 70px;
            }
            
            .main-content {
                margin-left: 70px; /* Menyesuaikan margin untuk mobile */
            }
            
            body.expanded .sidebar {
                width: 250px;
            }
            
            body.expanded .toggle-btn {
                left: 250px;
            }
            
            body.expanded .sidebar .logo-text,
            body.expanded .sidebar .user-name,
            body.expanded .sidebar-nav a span {
                display: inline-block;
            }
            
            body.expanded .sidebar-header {
                justify-content: flex-start;
                padding: 20px;
            }
            
            body.expanded .sidebar-nav a {
                justify-content: flex-start;
                padding: 12px 15px;
            }
            
            body.expanded .sidebar-nav a i {
                margin-right: 10px;
            }
            
            body.expanded .user-info {
                justify-content: flex-start;
                padding: 15px;
            }
            
            body.expanded .user-info img {
                margin-right: 10px;
            }
            
            body.expanded .main-content {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <button class="toggle-btn" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="sidebar-header">
            <i class="fas fa-coffee me-2"></i>
            <h3 class="logo mb-0"><span class="logo-text">Nama Cafe</span></h3>
        </div>
        
        <ul class="sidebar-nav">
            <li>
                <a href="/categories">
                    <i class="fas fa-list"></i>
                    <span>Category</span>
                </a>
            </li>
            <li>
                <a href="/products">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <hr>
            <li>
                <a href="/logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
        
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="container ms-10">
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap and jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Script -->
    @yield('script')
    <script>
        $(document).ready(function() {
            // Toggle sidebar
            $('#sidebarToggle').on('click', function() {
                $('body').toggleClass('collapsed');
                
                // For mobile devices
                if ($(window).width() <= 768) {
                    $('body').toggleClass('expanded');
                }
            });
            
            // Set initial state based on screen size
            function setInitialState() {
                if ($(window).width() <= 768) {
                    $('body').removeClass('collapsed');
                    $('body').removeClass('expanded');
                } else {
                    $('body').removeClass('collapsed');
                    $('body').removeClass('expanded');
                }
            }
            
            // Call on page load
            setInitialState();
            
            // Call on window resize
            $(window).resize(function() {
                setInitialState();
            });
        });
    </script>
</body>
</html>