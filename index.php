<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IgnitePHP - High-Performance PHP Micro-Framework</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Enhanced Color Palette */
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary: #64748b;
            --accent: #f59e0b;
            --accent-light: #fbbf24;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --text: #334155;
            --text-light: #64748b;
            --text-lighter: #94a3b8;
            --white: #ffffff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            
            /* Enhanced Gradients */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-accent: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-dark: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            --gradient-hero: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            
            /* Enhanced Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            
            /* Enhanced Spacing */
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            --spacing-3xl: 4rem;
            --spacing-4xl: 5rem;
            
            /* Enhanced Border Radius */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;
            
            /* Enhanced Transitions */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gradient-hero);
            color: var(--white);
            min-height: 100vh;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-xl);
        }

        /* Header Section */
        .header {
            text-align: center;
            padding: var(--spacing-4xl) 0 var(--spacing-3xl);
        }

        .logo {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            background-clip: text;
            margin-bottom: var(--spacing-lg);
            letter-spacing: -0.02em;
        }

        .tagline {
            font-size: 1.25rem;
            color: var(--gray-200);
            margin-bottom: var(--spacing-xl);
            font-weight: 400;
        }

        .description {
            font-size: 1.1rem;
            color: var(--gray-300);
            max-width: 600px;
            margin: 0 auto var(--spacing-2xl);
            line-height: 1.7;
        }

        /* Features Grid */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing-xl);
            margin: var(--spacing-3xl) 0;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-xl);
            padding: var(--spacing-xl);
            transition: var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-accent);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-2xl);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-md);
            display: block;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: var(--spacing-sm);
            color: var(--white);
        }

        .feature-description {
            color: var(--gray-300);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* API Testing Section */
        .api-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-2xl);
            padding: var(--spacing-2xl);
            margin: var(--spacing-3xl) 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: var(--spacing-lg);
            text-align: center;
            color: var(--white);
        }

        .api-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
        }

        .api-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            text-align: center;
            transition: var(--transition-normal);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .api-card:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
        }

        .api-method {
            display: inline-block;
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: var(--spacing-sm);
        }

        .method-get {
            background: var(--success);
            color: var(--white);
        }

        .method-post {
            background: var(--accent);
            color: var(--white);
        }

        .api-endpoint {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.9rem;
            color: var(--accent-light);
            margin-bottom: var(--spacing-sm);
        }

        .api-description {
            font-size: 0.85rem;
            color: var(--gray-300);
        }

        /* Status Indicator */
        .status-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            margin: var(--spacing-lg) 0;
            padding: var(--spacing-md);
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: var(--radius-full);
            background: var(--success);
            animation: pulse 2s infinite;
        }

        .status-text {
            font-size: 0.9rem;
            color: var(--gray-200);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-lg);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition-normal);
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-group {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
            flex-wrap: wrap;
            margin: var(--spacing-xl) 0;
        }

        /* Configuration Section */
        .config-section {
            background: rgba(0, 0, 0, 0.2);
            border-radius: var(--radius-xl);
            padding: var(--spacing-xl);
            margin: var(--spacing-2xl) 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .config-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: var(--spacing-lg);
            color: var(--accent-light);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .config-content {
            background: rgba(0, 0, 0, 0.3);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.85rem;
            line-height: 1.6;
            color: var(--gray-200);
            overflow-x: auto;
        }

        .config-step {
            margin-bottom: var(--spacing-md);
            padding: var(--spacing-sm);
            border-left: 3px solid var(--primary);
            background: rgba(37, 99, 235, 0.1);
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: var(--spacing-3xl) 0 var(--spacing-xl);
            color: white;
            font-size: 0.9rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
            flex-wrap: wrap;
        }

        .footer-link {
            color: var(--gray-300);
            text-decoration: none;
            transition: var(--transition-fast);
        }

        .footer-link:hover {
            color: var(--accent-light);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: var(--spacing-lg);
            }

            .logo {
                font-size: 2.5rem;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .api-grid {
                grid-template-columns: 1fr;
            }

            .btn-group {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: var(--spacing-2xl) 0 var(--spacing-xl);
            }

            .logo {
                font-size: 2rem;
            }

            .tagline {
                font-size: 1.1rem;
            }

            .description {
                font-size: 1rem;
            }
        }

        /* Routes Section */
        .routes-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-2xl);
            padding: var(--spacing-2xl);
            margin: var(--spacing-3xl) 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .section-description {
            text-align: center;
            color: var(--gray-300);
            margin-bottom: var(--spacing-xl);
            font-size: 1.1rem;
        }

        /* Routes Navigation */
        .routes-nav {
            display: flex;
            justify-content: center;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-xl);
            flex-wrap: wrap;
        }

        .nav-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white);
            padding: var(--spacing-sm) var(--spacing-md);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: var(--transition-normal);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .nav-btn.active {
            background: var(--gradient-primary);
            border-color: transparent;
            box-shadow: var(--shadow-lg);
        }

        /* Routes Categories */
        .routes-category {
            display: none;
            margin-bottom: var(--spacing-2xl);
        }

        .routes-category.active {
            display: block;
        }

        .category-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: var(--spacing-lg);
            color: var(--white);
            text-align: center;
        }

        /* Routes Grid */
        .routes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: var(--spacing-lg);
        }

        .route-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition-normal);
        }

        .route-card:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .route-header {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }

        .method-badge {
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .method-get {
            background: var(--success);
            color: var(--white);
        }

        .method-post {
            background: var(--accent);
            color: var(--white);
        }

        .method-put {
            background: var(--primary);
            color: var(--white);
        }

        .method-patch {
            background: var(--warning);
            color: var(--white);
        }

        .method-delete {
            background: var(--error);
            color: var(--white);
        }

        .endpoint-path {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.9rem;
            color: var(--accent-light);
            font-weight: 500;
        }

        .route-content h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: var(--spacing-sm);
            color: var(--white);
        }

        .route-content p {
            color: var(--gray-300);
            font-size: 0.9rem;
            margin-bottom: var(--spacing-md);
            line-height: 1.5;
        }

        .route-actions {
            display: flex;
            gap: var(--spacing-sm);
        }

        .route-link {
            background: var(--gradient-primary);
            color: var(--white);
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: var(--transition-fast);
            border: none;
            cursor: pointer;
        }

        .route-link:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .route-docs-btn {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition-fast);
        }

        .route-docs-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: var(--dark-light);
            margin: 5% auto;
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            width: 90%;
            max-width: 600px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .close {
            color: var(--gray-400);
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: var(--spacing-lg);
            top: var(--spacing-md);
        }

        .close:hover {
            color: var(--white);
        }

        .modal h3 {
            color: var(--white);
            margin-bottom: var(--spacing-lg);
            font-size: 1.5rem;
        }

        .modal-detail {
            margin-bottom: var(--spacing-md);
        }

        .modal-detail strong {
            color: var(--accent-light);
            display: inline-block;
            width: 120px;
        }

        .modal-detail span {
            color: var(--gray-300);
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-full);
            border-top-color: var(--white);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="logo">🚀 IgnitePHP</div>
            <p class="tagline">High-Performance PHP Micro-Framework</p>
            <p class="description">
                Build lightning-fast REST APIs with the elegance of modern frameworks. 
                Zero bloat, maximum flexibility, and exceptional performance.
            </p>
            
            <div class="btn-group">
                <a href="#api-testing" class="btn btn-primary">
                    <span>🧪</span>
                    Test APIs
                </a>
                <a href="#routes" class="btn btn-secondary">
                    <span>📋</span>
                    All Routes
                </a>
                <a href="https://github.com/ShoaibShokat03/ignitephp" class="btn btn-secondary" target="_blank">
                    <span>📚</span>
                    Documentation
                </a>
            </div>
        </header>

        <!-- Features Section -->
        <section class="features">
            <div class="feature-card">
                <span class="feature-icon">⚡</span>
                <h3 class="feature-title">Lightning Fast</h3>
                <p class="feature-description">
                    Optimized routing engine with minimal overhead. Built for speed and efficiency.
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">🔧</span>
                <h3 class="feature-title">Zero Dependencies</h3>
                <p class="feature-description">
                    No external dependencies except Composer. Keep your project lean and fast.
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">🎯</span>
                <h3 class="feature-title">Easy to Use</h3>
                <p class="feature-description">
                    Clean, expressive syntax inspired by FastAPI. Get started in minutes.
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">🛡️</span>
                <h3 class="feature-title">Built-in CORS</h3>
                <p class="feature-description">
                    Cross-origin resource sharing support out of the box. Ready for production.
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">📡</span>
                <h3 class="feature-title">JSON First</h3>
                <p class="feature-description">
                    Automatic JSON handling and response formatting. Perfect for modern APIs.
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">🚀</span>
                <h3 class="feature-title">Production Ready</h3>
                <p class="feature-description">
                    Built with performance and scalability in mind. Deploy with confidence.
                </p>
            </div>
        </section>

        <!-- API Testing Section -->
        <section id="api-testing" class="api-section">
            <h2 class="section-title">🧪 API Testing</h2>
            
            <div class="api-grid">
                <div class="api-card">
                    <span class="api-method method-get">GET</span>
                    <div class="api-endpoint">/api/</div>
                    <div class="api-description">Welcome message</div>
                </div>
                
                <div class="api-card">
                    <span class="api-method method-get">GET</span>
                    <div class="api-endpoint">/api/hello</div>
                    <div class="api-description">Hello World endpoint</div>
                </div>
                
                <div class="api-card">
                    <span class="api-method method-get">GET</span>
                    <div class="api-endpoint">/api/user/{id}</div>
                    <div class="api-description">Dynamic user endpoint</div>
                </div>
                
                <div class="api-card">
                    <span class="api-method method-post">POST</span>
                    <div class="api-endpoint">/api/users</div>
                    <div class="api-description">Create user endpoint</div>
                </div>
            </div>

            <!-- Status Indicator -->
            <div class="status-indicator">
                <div class="status-dot"></div>
                <span class="status-text" id="status-text">Checking API status...</span>
            </div>

            <div class="btn-group">
                <a href="api/" class="btn btn-primary" target="_blank">
                    <span>🌐</span>
                    Test Welcome API
                </a>
                <a href="api/hello" class="btn btn-secondary" target="_blank">
                    <span>👋</span>
                    Test Hello API
                </a>
                <a href="api/user/123" class="btn btn-secondary" target="_blank">
                    <span>👤</span>
                    Test User API
                </a>
            </div>
        </section>

        <!-- Routes Documentation Section -->
        <section id="routes" class="routes-section">
            <h2 class="section-title">📋 API Routes Documentation</h2>
            <p class="section-description">Complete list of all available API endpoints with HTTP methods and descriptions.</p>
            
            <!-- Routes Navigation -->
            <div class="routes-nav">
                <button class="nav-btn active" data-category="all">All Routes</button>
                <button class="nav-btn" data-category="get">GET</button>
                <button class="nav-btn" data-category="post">POST</button>
                <button class="nav-btn" data-category="put">PUT</button>
                <button class="nav-btn" data-category="patch">PATCH</button>
                <button class="nav-btn" data-category="delete">DELETE</button>
            </div>

            <!-- GET Routes -->
            <div class="routes-category" data-category="get">
                <h3 class="category-title">📋 GET Endpoints</h3>
                <div class="routes-grid">
                    <div class="route-card" data-method="GET" data-endpoint="/api/">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/</span>
                        </div>
                        <div class="route-content">
                            <h4>Welcome Message</h4>
                            <p>Welcome message with API documentation</p>
                            <div class="route-actions">
                                <a href="api/" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/', 'Welcome message with API documentation', 'None', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="GET" data-endpoint="/api/hello">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/hello</span>
                        </div>
                        <div class="route-content">
                            <h4>Hello World</h4>
                            <p>Simple greeting endpoint</p>
                            <div class="route-actions">
                                <a href="api/hello" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/hello', 'Simple greeting endpoint', 'None', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="GET" data-endpoint="/api/users">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/users</span>
                        </div>
                        <div class="route-content">
                            <h4>Get All Users</h4>
                            <p>Retrieve all users with pagination</p>
                            <div class="route-actions">
                                <a href="api/users" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/users', 'Retrieve all users with pagination', 'None', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="GET" data-endpoint="/api/users/{id}">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/users/{id}</span>
                        </div>
                        <div class="route-content">
                            <h4>Get User by ID</h4>
                            <p>Retrieve specific user with detailed profile</p>
                            <div class="route-actions">
                                <a href="api/users/1" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/users/{id}', 'Retrieve specific user with detailed profile', 'id (path parameter)', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="GET" data-endpoint="/api/posts">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/posts</span>
                        </div>
                        <div class="route-content">
                            <h4>Get All Posts</h4>
                            <p>Retrieve all posts with metadata</p>
                            <div class="route-actions">
                                <a href="api/posts" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/posts', 'Retrieve all posts with metadata', 'None', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="GET" data-endpoint="/api/posts/{id}">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/posts/{id}</span>
                        </div>
                        <div class="route-content">
                            <h4>Get Post by ID</h4>
                            <p>Retrieve specific post with comments</p>
                            <div class="route-actions">
                                <a href="api/posts/1" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/posts/{id}', 'Retrieve specific post with comments', 'id (path parameter)', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="GET" data-endpoint="/api/search/users">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/search/users</span>
                        </div>
                        <div class="route-content">
                            <h4>Search Users</h4>
                            <p>Search users by name or email</p>
                            <div class="route-actions">
                                <a href="api/search/users?q=john" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/search/users', 'Search users by name or email', 'q (query parameter)', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="GET" data-endpoint="/api/health">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/health</span>
                        </div>
                        <div class="route-content">
                            <h4>Health Check</h4>
                            <p>API health status</p>
                            <div class="route-actions">
                                <a href="api/health" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/health', 'API health status', 'None', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="GET" data-endpoint="/api/stats">
                        <div class="route-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/stats</span>
                        </div>
                        <div class="route-content">
                            <h4>API Statistics</h4>
                            <p>API usage statistics</p>
                            <div class="route-actions">
                                <a href="api/stats" class="route-link" target="_blank">Test</a>
                                <button class="route-docs-btn" onclick="showRouteDetails('GET', '/api/stats', 'API usage statistics', 'None', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- POST Routes -->
            <div class="routes-category" data-category="post">
                <h3 class="category-title">➕ POST Endpoints</h3>
                <div class="routes-grid">
                    <div class="route-card" data-method="POST" data-endpoint="/api/users">
                        <div class="route-header">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/users</span>
                        </div>
                        <div class="route-content">
                            <h4>Create User</h4>
                            <p>Create a new user</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testPostEndpoint('/api/users', 'Create User')">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('POST', '/api/users', 'Create a new user', 'None', 'Content-Type: application/json')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="POST" data-endpoint="/api/posts">
                        <div class="route-header">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/posts</span>
                        </div>
                        <div class="route-content">
                            <h4>Create Post</h4>
                            <p>Create a new post</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testPostEndpoint('/api/posts', 'Create Post')">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('POST', '/api/posts', 'Create a new post', 'None', 'Content-Type: application/json')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="POST" data-endpoint="/api/auth/login">
                        <div class="route-header">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/auth/login</span>
                        </div>
                        <div class="route-content">
                            <h4>User Login</h4>
                            <p>Authenticate user and get access token</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testLoginEndpoint()">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('POST', '/api/auth/login', 'Authenticate user and get access token', 'None', 'Content-Type: application/json')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="POST" data-endpoint="/api/echo">
                        <div class="route-header">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/echo</span>
                        </div>
                        <div class="route-content">
                            <h4>Echo Test</h4>
                            <p>Echo back request data for testing</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testEchoEndpoint()">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('POST', '/api/echo', 'Echo back request data for testing', 'None', 'Content-Type: application/json')">Docs</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PUT Routes -->
            <div class="routes-category" data-category="put">
                <h3 class="category-title">🔄 PUT Endpoints</h3>
                <div class="routes-grid">
                    <div class="route-card" data-method="PUT" data-endpoint="/api/users/{id}">
                        <div class="route-header">
                            <span class="method-badge method-put">PUT</span>
                            <span class="endpoint-path">/api/users/{id}</span>
                        </div>
                        <div class="route-content">
                            <h4>Update User</h4>
                            <p>Fully update a user (all fields required)</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testPutEndpoint('/api/users/1', 'Update User')">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('PUT', '/api/users/{id}', 'Fully update a user', 'id (path parameter)', 'Content-Type: application/json')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="PUT" data-endpoint="/api/posts/{id}">
                        <div class="route-header">
                            <span class="method-badge method-put">PUT</span>
                            <span class="endpoint-path">/api/posts/{id}</span>
                        </div>
                        <div class="route-content">
                            <h4>Update Post</h4>
                            <p>Fully update a post (all fields required)</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testPutEndpoint('/api/posts/1', 'Update Post')">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('PUT', '/api/posts/{id}', 'Fully update a post', 'id (path parameter)', 'Content-Type: application/json')">Docs</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PATCH Routes -->
            <div class="routes-category" data-category="patch">
                <h3 class="category-title">🔧 PATCH Endpoints</h3>
                <div class="routes-grid">
                    <div class="route-card" data-method="PATCH" data-endpoint="/api/users/{id}">
                        <div class="route-header">
                            <span class="method-badge method-patch">PATCH</span>
                            <span class="endpoint-path">/api/users/{id}</span>
                        </div>
                        <div class="route-content">
                            <h4>Partially Update User</h4>
                            <p>Partially update a user (only provided fields)</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testPatchEndpoint('/api/users/1', 'Partially Update User')">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('PATCH', '/api/users/{id}', 'Partially update a user', 'id (path parameter)', 'Content-Type: application/json')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="PATCH" data-endpoint="/api/posts/{id}">
                        <div class="route-header">
                            <span class="method-badge method-patch">PATCH</span>
                            <span class="endpoint-path">/api/posts/{id}</span>
                        </div>
                        <div class="route-content">
                            <h4>Partially Update Post</h4>
                            <p>Partially update a post (only provided fields)</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testPatchEndpoint('/api/posts/1', 'Partially Update Post')">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('PATCH', '/api/posts/{id}', 'Partially update a post', 'id (path parameter)', 'Content-Type: application/json')">Docs</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DELETE Routes -->
            <div class="routes-category" data-category="delete">
                <h3 class="category-title">🗑️ DELETE Endpoints</h3>
                <div class="routes-grid">
                    <div class="route-card" data-method="DELETE" data-endpoint="/api/users/{id}">
                        <div class="route-header">
                            <span class="method-badge method-delete">DELETE</span>
                            <span class="endpoint-path">/api/users/{id}</span>
                        </div>
                        <div class="route-content">
                            <h4>Delete User</h4>
                            <p>Delete a user</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testDeleteEndpoint('/api/users/1', 'Delete User')">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('DELETE', '/api/users/{id}', 'Delete a user', 'id (path parameter)', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>

                    <div class="route-card" data-method="DELETE" data-endpoint="/api/posts/{id}">
                        <div class="route-header">
                            <span class="method-badge method-delete">DELETE</span>
                            <span class="endpoint-path">/api/posts/{id}</span>
                        </div>
                        <div class="route-content">
                            <h4>Delete Post</h4>
                            <p>Delete a post</p>
                            <div class="route-actions">
                                <button class="route-link" onclick="testDeleteEndpoint('/api/posts/1', 'Delete Post')">Test</button>
                                <button class="route-docs-btn" onclick="showRouteDetails('DELETE', '/api/posts/{id}', 'Delete a post', 'id (path parameter)', 'None')">Docs</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Configuration Section -->
        <section class="config-section">
            <h3 class="config-title">
                <span>⚙️</span>
                Configuration Guide
            </h3>
            
            <div class="config-content">
                <div class="config-step">
                    <strong>1. Environment Setup</strong><br>
                    Create <code>config/.env</code> file:<br>
                    <code>BASE_URL="http://localhost/your-project"</code>
                </div>
                
                <div class="config-step">
                    <strong>2. Subdirectory Configuration</strong><br>
                    If running in subdirectory, update <code>.htaccess</code>:<br>
                    <code>RewriteBase /your-subdirectory/</code><br>
                    <code>RewriteCond %{REQUEST_URI} ^/your-subdirectory/api/</code>
                </div>
                
                <div class="config-step">
                    <strong>3. CORS Configuration</strong><br>
                    Edit <code>config/cors.php</code> for production:<br>
                    <code>'allow_all_origins' => false</code><br>
                    <code>'allowed_origins' => ['https://yourdomain.com']</code>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-links">
                <a href="https://github.com/ShoaibShokat03/ignitephp" class="footer-link" target="_blank">GitHub</a>
                <a href="https://github.com/ShoaibShokat03/ignitephp/issues" class="footer-link" target="_blank">Issues</a>
                <a href="https://github.com/ShoaibShokat03/ignitephp/discussions" class="footer-link" target="_blank">Discussions</a>
                <a href="mailto:shoaibshokat6@gmail.com" class="footer-link">Contact</a>
            </div>
            
            <p>Built with ❤️ by Muhammad Shoaib</p>
            <p>Inspired by FastAPI • Powered by PHP 8.0+</p>
        </footer>
    </div>

    <!-- Modal for Route Details -->
    <div id="routeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3 id="modalTitle">Route Details</h3>
            <div class="modal-detail">
                <strong>Method:</strong>
                <span id="modalMethod"></span>
            </div>
            <div class="modal-detail">
                <strong>Endpoint:</strong>
                <span id="modalEndpoint"></span>
            </div>
            <div class="modal-detail">
                <strong>Description:</strong>
                <span id="modalDescription"></span>
            </div>
            <div class="modal-detail">
                <strong>Parameters:</strong>
                <span id="modalParameters"></span>
            </div>
            <div class="modal-detail">
                <strong>Headers:</strong>
                <span id="modalHeaders"></span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const statusText = document.getElementById('status-text');
            const statusDot = document.querySelector('.status-dot');
            
            // Test API connectivity
            fetch("api/hello")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('API not responding');
                    }
                    return response.json();
                })
                .then(data => {
                    statusText.textContent = `API Connected: ${data.message || 'Success!'}`;
                    statusDot.style.background = 'var(--success)';
                })
                .catch(error => {
                    statusText.textContent = 'API not responding. Check your setup.';
                    statusDot.style.background = 'var(--error)';
                    console.error('API Error:', error);
                });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading animation to buttons
            document.querySelectorAll('.btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.href && this.href.includes('api/')) {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<span class="loading"></span> Testing...';
                        
                        setTimeout(() => {
                            this.innerHTML = originalText;
                        }, 2000);
                    }
                });
            });

            // Routes Navigation
            const navBtns = document.querySelectorAll('.nav-btn');
            const routeCategories = document.querySelectorAll('.routes-category');

            navBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    
                    // Update active button
                    navBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show/hide categories
                    routeCategories.forEach(cat => {
                        cat.classList.remove('active');
                        if (category === 'all' || cat.getAttribute('data-category') === category) {
                            cat.classList.add('active');
                        }
                    });
                });
            });

            // Show all categories by default
            document.querySelector('[data-category="all"]').click();

            // Modal functionality
            const modal = document.getElementById('routeModal');
            const closeBtn = document.querySelector('.close');

            closeBtn.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });

        // Global functions for route testing and documentation
        function showRouteDetails(method, endpoint, description, parameters, headers) {
            document.getElementById('modalTitle').textContent = `${method} ${endpoint}`;
            document.getElementById('modalMethod').textContent = method;
            document.getElementById('modalEndpoint').textContent = endpoint;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('modalParameters').textContent = parameters;
            document.getElementById('modalHeaders').textContent = headers;
            
            document.getElementById('routeModal').style.display = "block";
        }

        function testPostEndpoint(endpoint, title) {
            const payload = getDefaultPayload(title);
            
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                alert(`${title} Response:\n${JSON.stringify(data, null, 2)}`);
            })
            .catch(error => {
                alert(`Error testing ${title}: ${error.message}`);
            });
        }

        function testPutEndpoint(endpoint, title) {
            const payload = getDefaultPayload(title);
            
            fetch(endpoint, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                alert(`${title} Response:\n${JSON.stringify(data, null, 2)}`);
            })
            .catch(error => {
                alert(`Error testing ${title}: ${error.message}`);
            });
        }

        function testPatchEndpoint(endpoint, title) {
            const payload = getDefaultPayload(title);
            
            fetch(endpoint, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                alert(`${title} Response:\n${JSON.stringify(data, null, 2)}`);
            })
            .catch(error => {
                alert(`Error testing ${title}: ${error.message}`);
            });
        }

        function testDeleteEndpoint(endpoint, title) {
            if (confirm(`Are you sure you want to test ${title}?`)) {
                fetch(endpoint, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    alert(`${title} Response:\n${JSON.stringify(data, null, 2)}`);
                })
                .catch(error => {
                    alert(`Error testing ${title}: ${error.message}`);
                });
            }
        }

        function testLoginEndpoint() {
            const payload = {
                email: 'admin@example.com',
                password: 'password'
            };
            
            fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                alert(`Login Response:\n${JSON.stringify(data, null, 2)}`);
            })
            .catch(error => {
                alert(`Error testing login: ${error.message}`);
            });
        }

        function testEchoEndpoint() {
            const payload = {
                test: 'data',
                message: 'Hello from IgnitePHP!',
                timestamp: new Date().toISOString()
            };
            
            fetch('/api/echo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                alert(`Echo Response:\n${JSON.stringify(data, null, 2)}`);
            })
            .catch(error => {
                alert(`Error testing echo: ${error.message}`);
            });
        }

        function getDefaultPayload(title) {
            switch(title) {
                case 'Create User':
                    return {
                        name: 'Test User',
                        email: 'test@example.com',
                        role: 'user'
                    };
                case 'Create Post':
                    return {
                        title: 'Test Post',
                        content: 'This is a test post content.',
                        author_id: 1,
                        author_name: 'Test Author',
                        tags: ['test', 'demo']
                    };
                case 'Update User':
                    return {
                        name: 'Updated Test User',
                        email: 'updated@example.com',
                        role: 'moderator',
                        status: 'active'
                    };
                case 'Update Post':
                    return {
                        title: 'Updated Test Post',
                        content: 'This is an updated test post content.',
                        author_id: 1,
                        author_name: 'Updated Author',
                        tags: ['updated', 'test'],
                        likes: 10,
                        comments: 5
                    };
                case 'Partially Update User':
                    return {
                        name: 'Partially Updated User'
                    };
                case 'Partially Update Post':
                    return {
                        title: 'Partially Updated Post',
                        likes: 25
                    };
                default:
                    return {
                        test: 'data',
                        message: 'Test payload'
                    };
            }
        }
    </script>
</body>

</html>