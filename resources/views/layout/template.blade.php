<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- Enhanced Global Styles -->
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #5dade2;
            --accent-color: #2ecc71;
            --text-primary: #2c3e50;
            --text-secondary: #7fb3d3;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fbff;
            --border-color: rgba(52, 152, 219, 0.1);
            --shadow-light: 0 5px 15px rgba(52, 152, 219, 0.08);
            --shadow-medium: 0 10px 30px rgba(52, 152, 219, 0.15);
            --shadow-heavy: 0 20px 40px rgba(52, 152, 219, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-primary), var(--bg-secondary));
            color: var(--text-primary);
            line-height: 1.6;
        }

        .container-xxl {
            background: var(--bg-primary);
            min-height: 100vh;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        /* Enhanced Spinner */
        #spinner {
            background: linear-gradient(135deg, var(--bg-primary), var(--bg-secondary));
            backdrop-filter: blur(10px);
        }

        .spinner-border {
            width: 3rem !important;
            height: 3rem !important;
            border-width: 0.3em;
            border-color: var(--primary-color) transparent var(--primary-color) transparent;
        }

        /* Content Area */
        .content {
            background: linear-gradient(135deg, var(--bg-primary), var(--bg-secondary));
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Form Enhancements */
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-light);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        /* Table Enhancements */
        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(52, 152, 219, 0.05);
            transform: scale(1.01);
        }

        /* Card Enhancements */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            background: white;
        }

        .card:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--bg-primary), var(--bg-secondary));
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Alert Enhancements */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-light);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.1), rgba(125, 230, 176, 0.1));
            color: #27ae60;
            border-left: 4px solid #2ecc71;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.1), rgba(245, 183, 177, 0.1));
            color: #c0392b;
            border-left: 4px solid #e74c3c;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(155, 206, 235, 0.1));
            color: #2980b9;
            border-left: 4px solid #3498db;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(250, 211, 144, 0.1));
            color: #d68910;
            border-left: 4px solid #f39c12;
        }

        /* Animation Classes */
        .fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }

        .slideUp {
            animation: slideUp 0.5s ease-out;
        }

        .scaleIn {
            animation: scaleIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }

            .card {
                margin-bottom: 20px;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }

        /* Loading States */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.2), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }
    </style>

    @livewireStyles
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"
            style="z-index: 9999;">
            <div class="d-flex flex-column align-items-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="text-primary" style="font-weight: 600;">Memuat...</div>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        @include('layout.sidebar')
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            @include('layout.navbar')
            <!-- Navbar End -->

            <!-- Main Content -->
            <main class="fadeIn">
                @yield('content')
            </main>

            <!-- Footer Start -->
            <footer class="container-fluid pt-4 px-4 mt-5">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            <div style="color: var(--text-secondary);">
                                &copy; <strong>Rent Car Management System</strong>, All Rights Reserved.
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <div style="color: var(--text-secondary);">
                                Designed & Developed with <i class="fa fa-heart text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Footer End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-lg-square back-to-top"
            style="position: fixed; bottom: 20px; right: 20px; z-index: 99; display: none; border-radius: 50%; width: 50px; height: 50px; box-shadow: var(--shadow-medium);">
            <i class="fa fa-arrow-up"></i>
        </a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Enhanced Template Javascript -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Enhanced Loading Animation
        $(document).ready(function() {
            // Hide spinner after page load
            setTimeout(function() {
                $('#spinner').removeClass('show');
            }, 1000);

            // Back to top button
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('.back-to-top').fadeIn('slow');
                } else {
                    $('.back-to-top').fadeOut('slow');
                }
            });

            $('.back-to-top').click(function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 1000, 'easeInOutExpo');
                return false;
            });

            // Add hover effects to cards
            $('.card').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );

            // Add loading animation to buttons
            $('.btn').click(function() {
                var $this = $(this);
                var originalText = $this.text();
                $this.addClass('loading').text('Memuat...');

                setTimeout(function() {
                    $this.removeClass('loading').text(originalText);
                }, 2000);
            });
        });
    </script>

    @livewireScripts
</body>

</html>
