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
        });
    </script>
</body>

</html>