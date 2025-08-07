# 🚀 IgnitePHP

**IgnitePHP** is a blazing-fast, high-performance PHP micro-framework inspired by FastAPI for building modern, scalable, and expressive REST APIs. Built with zero bloat and maximum flexibility, it provides powerful routing, clean syntax, and seamless integration — all while maintaining exceptional performance.

> 🔥 Build lightning-fast APIs in PHP with the elegance of modern frameworks.

---

## ✨ Features

- ⚡ **Super-fast routing engine** with dynamic parameter support
- 🔧 **Minimal setup** with zero external dependencies (except Composer)
- 🧩 **Modular PSR-4 autoloaded architecture**
- 🔁 **Dynamic route parameters** like `/user/{id}` and `/posts/{slug}`
- 💡 **Clean and expressive route definitions**
- 🛡️ **Built-in CORS support** with configurable origins
- 📡 **JSON-first API design** with automatic response handling
- 🔍 **Comprehensive request handling** (JSON, form-data, query params)
- 🚀 **Lightweight core** - only essential features included
- 🎯 **Easy to extend** for middleware, authentication, and more

---

## 📋 Requirements

- **PHP 8.0** or higher
- **Composer** for dependency management
- **Apache/Nginx** with `.htaccess` or URL rewrite support
- **mod_rewrite** enabled (for Apache)

---

## 🛠 Installation

### Option 1: Clone and Install

```bash
git clone https://github.com/ShoaibShokat03/ignitephp.git
cd ignitephp
composer install
```

### Option 2: Create New Project

```bash
composer create-project ignitephp/ignitephp your-project-name
cd your-project-name
```

### Option 3: Manual Setup

1. **Download the framework files**
2. **Install dependencies:**
   ```bash
   composer install
   ```
3. **Configure your web server** to point to the project directory
4. **Set up environment variables** (optional)

---

## 🚀 Quick Start

### 1. Basic Route Definition

```php
<?php
// routes/api.php

use Ignite\Core\Route;

// Simple GET route
Route::get('/', function () {
    return ['message' => 'Welcome to IgnitePHP!'];
});

// Route with dynamic parameter
Route::get('/user/{id}', function ($id) {
    return [
        'user_id' => $id,
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ];
});

// POST route with JSON handling
Route::post('/users', function () {
    $data = \Ignite\Core\Request::getJson();
    return [
        'status' => 'success',
        'data' => $data,
        'message' => 'User created successfully'
    ];
});
```

### 2. Request Handling

```php
<?php
// routes/api.php

use Ignite\Core\Route;
use Ignite\Core\Request;

Route::post('/api/data', function () {
    // Get JSON data
    $jsonData = Request::getJson();
    
    // Get query parameters
    $queryParams = Request::getQueryParams();
    
    // Get specific header
    $authHeader = Request::getHeader('Authorization');
    
    // Get all headers
    $allHeaders = Request::getHeaders();
    
    return [
        'received_data' => $jsonData,
        'query_params' => $queryParams,
        'auth_header' => $authHeader
    ];
});
```

### 3. Advanced Routing

```php
<?php
// routes/api.php

use Ignite\Core\Route;

// Multiple parameters
Route::get('/posts/{category}/{id}', function ($category, $id) {
    return [
        'category' => $category,
        'post_id' => $id,
        'title' => 'Sample Post'
    ];
});

// Complex API endpoints
Route::post('/api/users/{id}/profile', function ($id) {
    $data = \Ignite\Core\Request::getJson();
    
    // Your business logic here
    $profile = updateUserProfile($id, $data);
    
    return [
        'status' => 'success',
        'user_id' => $id,
        'profile' => $profile
    ];
});
```

---

## 📁 Project Structure

```
ignitephp/
├── config/
│   ├── cors.php          # CORS configuration
│   └── .env              # Environment variables
├── public/
│   └── index.php         # API entry point
├── routes/
│   └── api.php           # API route definitions
├── src/
│   └── core/
│       ├── Route.php     # Route facade
│       ├── Router.php    # Core routing engine
│       └── Request.php   # Request handling
├── vendor/               # Composer dependencies
├── .htaccess            # URL rewriting rules
├── composer.json        # Project dependencies
└── index.php           # Welcome page
```

---

## ⚙️ Configuration

### CORS Configuration

Edit `config/cors.php` to configure Cross-Origin Resource Sharing:

```php
<?php
// config/cors.php

return [
    'allow_all_origins' => true,  // Set to false for production
    'allowed_origins' => [
        'https://yourdomain.com',
        'https://api.yourdomain.com',
        'http://localhost:3000'
    ],
];
```

### Environment Variables

Create `config/.env` for environment-specific settings:

```env
# config/.env
BASE_URL="http://localhost/your-project"
APP_ENV="development"
APP_DEBUG="true"
```

---

## 🔧 Advanced Usage

### Custom Middleware (Example)

```php
<?php
// src/Middleware/AuthMiddleware.php

namespace Ignite\Middleware;

class AuthMiddleware
{
    public static function handle($handler)
    {
        // Check authentication
        $token = \Ignite\Core\Request::getHeader('Authorization');
        
        if (!$token || !self::validateToken($token)) {
            http_response_code(401);
            return ['error' => 'Unauthorized'];
        }
        
        // Continue to handler
        return $handler();
    }
    
    private static function validateToken($token)
    {
        // Your token validation logic
        return true;
    }
}
```

### Error Handling

```php
<?php
// routes/api.php

use Ignite\Core\Route;

Route::get('/protected/{id}', function ($id) {
    try {
        // Your logic here
        $data = fetchProtectedData($id);
        return ['data' => $data];
    } catch (Exception $e) {
        http_response_code(500);
        return [
            'error' => 'Internal server error',
            'message' => $e->getMessage()
        ];
    }
});
```

---

## 🌐 API Examples

### Testing Your API

Once your server is running, test the default endpoints:

```bash
# Welcome message
curl http://localhost/your-project/api/

# Hello world
curl http://localhost/your-project/api/hello

# Dynamic parameter
curl http://localhost/your-project/api/user/123

# POST with JSON
curl -X POST http://localhost/your-project/api/users \
  -H "Content-Type: application/json" \
  -d '{"name": "John", "email": "john@example.com"}'
```

### Expected Responses

```json
// GET /api/
{
  "message": "Welcome to Ignite!"
}

// GET /api/user/123
{
  "user_id": "123",
  "name": "John Doe",
  "email": "john@example.com"
}

// POST /api/users
{
  "status": "success",
  "data": {
    "name": "John",
    "email": "john@example.com"
  },
  "message": "User created successfully"
}
```

---

## 🚀 Performance

IgnitePHP is designed for maximum performance:

- **Lightweight core** - Only essential features included
- **Fast routing** - Optimized regex-based route matching
- **Minimal overhead** - No unnecessary abstractions
- **Memory efficient** - Static property usage for request handling
- **Zero external dependencies** - Except Composer autoloader

---

## 🔧 Development

### Local Development Setup

1. **Start your web server** (Apache/Nginx)
2. **Navigate to your project directory**
3. **Access the welcome page:** `http://localhost/your-project/`
4. **Test API endpoints:** `http://localhost/your-project/api/`

### Subdirectory Configuration

If running in a subdirectory, update your `.htaccess`:

```apache
RewriteBase /your-subdirectory/
RewriteCond %{REQUEST_URI} ^/your-subdirectory/api/
```

And set `BASE_URL` in `config/.env`:

```env
BASE_URL="http://localhost/your-subdirectory"
```

---

## 🤝 Contributing

We welcome contributions! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

### Development Guidelines

1. **Follow PSR-4 autoloading standards**
2. **Maintain backward compatibility**
3. **Add tests for new features**
4. **Update documentation**
5. **Follow existing code style**

### Getting Started

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Credits

- **Author:** Muhammad Shoaib
- **Email:** shoaibshokat6@gmail.com
- **Inspired by:** FastAPI (Python)
- **Built with:** PHP 8.0+, Composer

---

## 🆘 Support

- **Documentation:** [GitHub Wiki](https://github.com/ShoaibShokat03/ignitephp/wiki)
- **Issues:** [GitHub Issues](https://github.com/ShoaibShokat03/ignitephp/issues)
- **Discussions:** [GitHub Discussions](https://github.com/ShoaibShokat03/ignitephp/discussions)

---

<div align="center">

**Built for speed. Designed for developers.** 🚀

[Star on GitHub](https://github.com/ShoaibShokat03/ignitephp) • [Report Bug](https://github.com/ShoaibShokat03/ignitephp/issues) • [Request Feature](https://github.com/ShoaibShokat03/ignitephp/issues)

</div>