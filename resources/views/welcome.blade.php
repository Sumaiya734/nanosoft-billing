<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Nanosoft - Internet Billing Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
   
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">
            <i class="fas fa-wifi me-2"></i>Nanosoft Billing
        </a>

        <!-- Hamburger toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
            </ul>

            <!-- Right-side user menu -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link btn btn-primary text-white" href="#" id="userMenu"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @auth
                            {{ Auth::user()->name }}
                        @else
                            Login
                        @endauth
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <!-- Guest links -->
                        @guest
                            <li><a class="dropdown-item" href="{{ route('customer.login') }}">Customer Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.login') }}">Admin Login</a></li>
                        @endguest

                        <!-- Authenticated links (hidden by default) -->
                        @auth
                            <li>
                                <hr class="dropdown-divider">
                                @if(Auth::user()->role === 'customer')
                                    <a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a>
                                @elseif(Auth::user()->role === 'admin')
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                @endif
                                <form method="POST" action="{{ Auth::user()->role === 'customer' ? route('customer.logout') : route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </li>
            </ul>
=======
    <title>Nanosoft - Professional Billing Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2c5aa0;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
        }

        .hero-section {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>');
        }

        .feature-icon {
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--primary), #4f86f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            display: inline-block;
            padding: 20px;
            border-radius: 20px;
            background-color: rgba(44, 90, 160, 0.1);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            background: linear-gradient(135deg, var(--primary), #4f86f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-brand i {
            margin-right: 10px;
            font-size: 2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #4f86f7);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 90, 160, 0.3);
        }

        .hero-feature {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin: 10px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-feature:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .hero-feature i {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #a8e6cf;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 5px solid var(--primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .section-title {
            position: relative;
            margin-bottom: 50px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary), #4f86f7);
            border-radius: 2px;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: var(--primary);
        }

        .service-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .footer-section {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            padding: 60px 0 30px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
        }

        .social-icon:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(44, 90, 160, 0.05);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .benefit-item:hover {
            background: rgba(44, 90, 160, 0.1);
            transform: translateX(10px);
        }

        .benefit-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-right: 20px;
            min-width: 60px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-money-bill-wave"></i>
                Nanosoft-Billing
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="#services"><i class="fas fa-cogs me-1"></i>Services</a>
                <a class="nav-link" href="#features"><i class="fas fa-star me-1"></i>Features</a>
                <a class="nav-link" href="#benefits"><i class="fas fa-check-circle me-1"></i>Benefits</a>
                <a class="nav-link" href="#about"><i class="fas fa-info-circle me-1"></i>About</a>
                <a class="nav-link" href="{{ route('customer.login') }}"><i class="fas fa-user me-1"></i>Client Portal</a>
                <a class="nav-link btn btn-primary text-white ms-2" href="{{ route('admin.login') }}">
                    <i class="fas fa-lock me-1"></i>Admin Login
                </a>
            </div>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
        </div>
    </div>
</nav>

<<<<<<< HEAD
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('assets/netbill04.jpg') }}" class="d-block w-100" alt="NetBill BD" style="object-fit: cover; height: 400px; width: 100%;">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="display-4 fw-bold mb-4">Smart Internet Billing Solution</h1>
                        <p class="lead mb-4">Automated monthly billing for internet service providers in Bangladesh</p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="row text-start">
                                    <div class="col-md-4 mb-3"><i class="fas fa-bolt"></i> Easy product Management</div>
                                    <div class="col-md-4 mb-3"><i class="fas fa-file-invoice"></i> Automated Monthly Billing</div>
                                    <div class="col-md-4 mb-3"><i class="fas fa-mobile-alt"></i> Customer Self-Service Portal</div>
                                </div>
                            </div>
=======
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container position-relative">
            <h1 class="display-3 fw-bold mb-4">Nanosoft Billing & Invoicing Solution</h1>
            <p class="lead mb-5 fs-4">Streamline your software company's financial operations with automated billing</p>
            
            <div class="row justify-content-center mb-5">
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="hero-feature">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <h6>Automated Invoicing</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="hero-feature">
                                <i class="fas fa-chart-line"></i>
                                <h6>Revenue Analytics</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="hero-feature">
                                <i class="fas fa-cube"></i>
                                <h6>Product Management</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="#services" class="btn btn-light btn-lg px-5 py-3">
                <i class="fas fa-rocket me-2"></i>Explore Solutions
            </a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold section-title">Our Billing Services</h2>
                <p class="text-muted fs-5">Comprehensive billing solutions for software companies</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-code"></i>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                        </div>
                        <h4>Software License Billing</h4>
                        <p class="text-muted">Manage recurring payments for software licenses and subscriptions with automated billing cycles.</p>
                    </div>
                </div>
<<<<<<< HEAD
                <div class="carousel-item">
                    <img src="{{ asset('assets/netbill05.png') }}" class="d-block w-100" alt="NetBill BD" style="object-fit: cover; height: 400px; width: 100%;">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="display-4 fw-bold mb-4">Streamlined Customer Management</h1>
                        <p class="lead mb-4">Efficiently manage customer accounts, subscriptions, and billing</p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="row text-start">
                                    <div class="col-md-4 mb-3"><i class="fas fa-users"></i> Customer Database</div>
                                    <div class="col-md-4 mb-3"><i class="fas fa-sync-alt"></i> Subscription Renewals</div>
                                    <div class="col-md-4 mb-3"><i class="fas fa-chart-bar"></i> Usage Analytics</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/netbill06.jpg') }}" class="d-block w-100" alt="NetBill BD" style="object-fit: cover; height: 400px; width: 100%;">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="display-4 fw-bold mb-4">Comprehensive Reporting</h1>
                        <p class="lead mb-4">Detailed financial reports and revenue tracking</p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="row text-start">
                                    <div class="col-md-4 mb-3"><i class="fas fa-file-invoice-dollar"></i> Monthly Invoices</div>
                                    <div class="col-md-4 mb-3"><i class="fas fa-money-bill-wave"></i> Payment Tracking</div>
                                    <div class="col-md-4 mb-3"><i class="fas fa-chart-line"></i> Revenue Analytics</div>
                                </div>
                            </div>
=======
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-cloud"></i>
                        </div>
                        <h4>SaaS Subscription Management</h4>
                        <p class="text-muted">Handle monthly/annual SaaS subscriptions with prorated billing and automated renewals.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-headset"></i>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                        </div>
                        <h4>Support & Maintenance</h4>
                        <p class="text-muted">Bill for technical support, maintenance contracts, and professional services.</p>
                    </div>
                </div>
            </div>

<<<<<<< HEAD
            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- CTA Button below carousel -->
        <div class="text-center mt-4">
            <a href="#products" class="btn btn-outline-primary btn-lg px-4">View Products</a>
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="products" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Internet products</h2>
            <p class="text-muted">Affordable products for every need</p>
        </div>

        <div class="row">
            <!-- Regular products -->
            <div class="col-md-6 mb-4">
                <h4 class="text-center mb-4">Regular products</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card product-card h-100">
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title text-primary">Basic Speed</h5>
                                <h3 class="text-success">৳500<small>/month</small></h3>
                                <p class="text-muted flex-grow-1">Perfect for everyday browsing and emails</p>
                                <ul class="list-unstyled mt-auto">
                                    <li><i class="fas fa-check text-success"></i> Standard Speed</li>
                                    <li><i class="fas fa-check text-success"></i> Unlimited Data</li>
                                    <li><i class="fas fa-check text-success"></i> 1 Device</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card product-card h-100">
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title text-primary">Fast Speed</h5>
                                <h3 class="text-success">৳800<small>/month</small></h3>
                                <p class="text-muted flex-grow-1">Great for streaming and downloads</p>
                                <ul class="list-unstyled mt-auto">
                                    <li><i class="fas fa-check text-success"></i> High Speed</li>
                                    <li><i class="fas fa-check text-success"></i> Unlimited Data</li>
                                    <li><i class="fas fa-check text-success"></i> Up to 3 Devices</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add-on products -->
            <div class="col-md-6 mb-4">
                <h4 class="text-center mb-4">Add-on products</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card product-card h-100">
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title text-primary">Gaming Boost</h5>
                                <h3 class="text-success">৳200<small>/month</small></h3>
                                <p class="text-muted flex-grow-1">Low latency for gaming</p>
                                <ul class="list-unstyled mt-auto">
                                    <li><i class="fas fa-check text-success"></i> Reduced Ping</li>
                                    <li><i class="fas fa-check text-success"></i> Priority Gaming</li>
                                    <li><i class="fas fa-check text-success"></i> 24/7 Support</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card product-card h-100">
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title text-primary">Streaming Plus</h5>
                                <h3 class="text-success">৳150<small>/month</small></h3>
                                <p class="text-muted flex-grow-1">Optimized for HD streaming</p>
                                <ul class="list-unstyled mt-auto">
                                    <li><i class="fas fa-check text-success"></i> HD Streaming</li>
                                    <li><i class="fas fa-check text-success"></i> Buffer-Free</li>
                                    <li><i class="fas fa-check text-success"></i> Multiple Platforms</li>
                                </ul>
                            </div>
                        </div>
                    </div>
=======
    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold section-title">Powerful Features</h2>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h5>Automated Billing</h5>
                    <p class="text-muted">Automatic invoice generation, payment tracking, and recurring billing for subscriptions.</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h5>Client Self-Service</h5>
                    <p class="text-muted">Client portal for viewing invoices, making payments, and managing subscriptions.</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h5>Financial Analytics</h5>
                    <p class="text-muted">Comprehensive revenue reports, cash flow analysis, and financial forecasting.</p>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                </div>
            </div>
        </div>
    </div>
</section>

<<<<<<< HEAD
<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <div class="text-center mb-5"><h2 class="fw-bold">Why Choose NetBill BD?</h2></div>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon"><i class="fas fa-rocket"></i></div>
                <h5>Automated Billing</h5>
                <p class="text-muted">Automatic monthly invoice generation and payment tracking</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon"><i class="fas fa-user-shield"></i></div>
                <h5>Customer Portal</h5>
                <p class="text-muted">Self-service portal for customers to view bills and make payments</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h5>Real-time Reports</h5>
                <p class="text-muted">Comprehensive analytics and financial reporting</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="fas fa-wifi me-2"></i>Nanosoft Billing</h5>
                <p>Smart internet billing solution for Bangladesh</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p>Contact: info@nanosoftbilling.com <br> 
                Phone: +880 123-456-7890</p>
                <p>© {{ date('Y') }} Nanosoft Billing. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Set the user's name in the dropdown toggle when authenticated
    const userMenuToggle = document.getElementById('userMenu');
    
    // Check if user is authenticated and update the display
    @auth
        userMenuToggle.innerHTML = '{{ Auth::user()->name }} <b class="caret"></b>';
    @endauth
</script>
=======
    <!-- Benefits Section -->
    <section id="benefits" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold section-title">Business Benefits</h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h5>Time Savings</h5>
                            <p class="text-muted mb-0">Reduce manual billing tasks by 80% with automated processes.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h5>Reduced Errors</h5>
                            <p class="text-muted mb-0">Eliminate billing mistakes with automated calculations and validation.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <h5>Faster Payments</h5>
                            <p class="text-muted mb-0">Improve cash flow with automated payment reminders and online payments.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h5>Better Insights</h5>
                            <p class="text-muted mb-0">Gain real-time visibility into revenue, churn, and customer lifetime value.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </div>
                        <div>
                            <h5>Scalable Growth</h5>
                            <p class="text-muted mb-0">Handle unlimited clients and transactions as your business grows.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-smile"></i>
                        </div>
                        <div>
                            <h5>Happy Clients</h5>
                            <p class="text-muted mb-0">Professional invoices and easy payment options improve client satisfaction.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="fw-bold">500+</h3>
                        <p class="text-muted">Software Companies</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h3 class="fw-bold">50K+</h3>
                        <p class="text-muted">Invoices Processed</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3 class="fw-bold">$10M+</h3>
                        <p class="text-muted">Revenue Managed</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3 class="fw-bold">15+</h3>
                        <p class="text-muted">Countries Served</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4><i class="fas fa-money-bill-wave me-2"></i>Nanosoft-Billing</h4>
                    <p class="mb-3">Professional billing solution for software companies worldwide.</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li><a href="#services" class="text-light text-decoration-none">Services</a></li>
                                <li><a href="#features" class="text-light text-decoration-none">Features</a></li>
                                <li><a href="#benefits" class="text-light text-decoration-none">Benefits</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li><a href="{{ route('customer.login') }}" class="text-light text-decoration-none">Client Portal</a></li>
                                <li><a href="{{ route('admin.login') }}" class="text-light text-decoration-none">Admin Login</a></li>
                                <li><a href="#about" class="text-light text-decoration-none">About Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Information</h5>
                    <p><i class="fas fa-envelope me-2"></i>info@nanosoft-billing.com</p>
                    <p><i class="fas fa-phone me-2"></i>+880 1XXX-XXXXXX</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Dhaka, Bangladesh</p>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p>&copy; 2024 Nanosoft-Billing. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
</body>
</html>