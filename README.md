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
- 🎯 **Complete HTTP method support** (GET, POST, PUT, DELETE, PATCH)
- 🎨 **Beautiful modern UI** with interactive documentation
- 📊 **Rich dummy data** for testing and demonstration
- 🔐 **Authentication endpoints** with JWT-style tokens
- 📈 **Health checks and statistics** endpoints

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

### 2. Complete HTTP Method Support

```php
<?php
// routes/api.php

use Ignite\Core\Route;
use Ignite\Core\Request;

// GET - Retrieve data
Route::get('/users', function () {
    return ['users' => $users];
});

// POST - Create data
Route::post('/users', function () {
    $data = Request::getJson();
    return ['status' => 'created', 'data' => $data];
});

// PUT - Full update
Route::put('/users/{id}', function ($id) {
    $data = Request::getJson();
    return ['status' => 'updated', 'id' => $id];
});

// PATCH - Partial update
Route::patch('/users/{id}', function ($id) {
    $data = Request::getJson();
    return ['status' => 'partially updated', 'id' => $id];
});

// DELETE - Remove data
Route::delete('/users/{id}', function ($id) {
    return ['status' => 'deleted', 'id' => $id];
});
```

### 3. Advanced Request Handling

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

---

## 📁 Project Structure

```
ignitephp/
├── config/
│   └── cors.php              # CORS configuration
├── routes/
│   └── api.php               # API route definitions (557 lines)
├── src/
│   └── core/
│       ├── Route.php         # Route facade (56 lines)
│       ├── Router.php        # Core routing engine (51 lines)
│       └── Request.php       # Request handling (126 lines)
├── vendor/                   # Composer dependencies
├── .htaccess                # URL rewriting rules
├── composer.json            # Project dependencies
├── index.php               # Beautiful welcome page (680 lines)
└── README.md               # This documentation
```

---

## 🌐 API Endpoints

### **📋 GET Endpoints**
- `GET /api/` - Welcome message with API documentation
- `GET /api/hello` - Simple greeting endpoint
- `GET /api/users` - Get all users with pagination
- `GET /api/users/{id}` - Get specific user with profile
- `GET /api/posts` - Get all posts
- `GET /api/posts/{id}` - Get specific post with comments
- `GET /api/search/users` - Search users by query
- `GET /api/health` - Health check endpoint
- `GET /api/stats` - API statistics

### **➕ POST Endpoints**
- `POST /api/users` - Create new user
- `POST /api/posts` - Create new post
- `POST /api/auth/login` - Authentication endpoint
- `POST /api/echo` - Echo endpoint for testing

### **🔄 PUT Endpoints**
- `PUT /api/users/{id}` - Full user update
- `PUT /api/posts/{id}` - Full post update

### **🔧 PATCH Endpoints**
- `PATCH /api/users/{id}` - Partial user update
- `PATCH /api/posts/{id}` - Partial post update

### **🗑️ DELETE Endpoints**
- `DELETE /api/users/{id}` - Delete user
- `DELETE /api/posts/{id}` - Delete post

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

## 🧪 Testing Your API

### Using cURL

```bash
# Welcome message
curl http://localhost/your-project/api/

# Get all users
curl http://localhost/your-project/api/users

# Get specific user
curl http://localhost/your-project/api/users/1

# Create user
curl -X POST http://localhost/your-project/api/users \
  -H "Content-Type: application/json" \
  -d '{"name": "John Doe", "email": "john@example.com"}'

# Update user
curl -X PUT http://localhost/your-project/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "John Updated", "email": "john.updated@example.com"}'

# Delete user
curl -X DELETE http://localhost/your-project/api/users/1

# Health check
curl http://localhost/your-project/api/health
```

### Using the Beautiful UI

Visit `http://localhost/your-project/` to access the interactive documentation with:
- **Real-time API testing**
- **Status indicators**
- **Interactive endpoint cards**
- **Configuration guides**

---

## 📊 Example Responses

### User Data
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "role": "admin",
    "created_at": "2024-01-15T10:30:00Z",
    "status": "active",
    "profile": {
      "avatar": "https://example.com/avatars/john.jpg",
      "bio": "Full-stack developer with 5+ years of experience",
      "location": "San Francisco, CA",
      "website": "https://johndoe.dev"
    }
  }
}
```

### Authentication Response
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com",
      "role": "admin"
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_at": "2024-01-16T10:30:00Z"
  }
}
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

### Search with Query Parameters

```php
<?php
// routes/api.php

Route::get('/search/users', function () {
    $query = Request::getQueryParams()['q'] ?? '';
    
    // Filter users based on query
    $filtered = array_filter($users, function($user) use ($query) {
        return stripos($user['name'], $query) !== false || 
               stripos($user['email'], $query) !== false;
    });

    return [
        'status' => 'success',
        'data' => array_values($filtered),
        'query' => $query,
        'total' => count($filtered)
    ];
});
```

---

## 🚀 Performance

IgnitePHP is designed for maximum performance:

- **Lightweight core** - Only essential features included
- **Fast routing** - Optimized regex-based route matching
- **Minimal overhead** - No unnecessary abstractions
- **Memory efficient** - Static property usage for request handling
- **Zero external dependencies** - Except Composer autoloader
- **Optimized request handling** - Efficient JSON and form data parsing

---

## 🎨 Beautiful UI Features

The framework includes a stunning welcome page with:

- **Modern glassmorphism design** with backdrop blur effects
- **Interactive API testing** with real-time status indicators
- **Responsive grid layouts** for features and endpoints
- **Professional typography** using Inter font
- **Smooth animations** and hover effects
- **Color-coded HTTP methods** (GET, POST, PUT, DELETE, PATCH)
- **Configuration guides** with step-by-step instructions
- **Mobile-first responsive design**

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
- **UI Design:** Modern glassmorphism with CSS variables

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