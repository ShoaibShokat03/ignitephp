# IgnitePHP 🚀

A modern, lightweight PHP framework designed for rapid API development with built-in security features, AI integration, and enterprise-grade capabilities.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Architecture](#architecture)
- [Security Features](#security-features)
- [AI Integration](#ai-integration)
- [Database Management](#database-management)
- [API Development](#api-development)
- [Configuration](#configuration)
- [Use Cases](#use-cases)
- [Why Choose IgnitePHP](#why-choose-ignitephp)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Overview

IgnitePHP is a powerful, security-first PHP framework built for modern web applications and APIs. It combines the simplicity of traditional PHP with modern development practices, offering enterprise-grade security, AI integration capabilities, and a clean, intuitive API.

### Key Highlights

- **🛡️ Security-First Design**: Built-in rate limiting, CORS protection, and security headers
- **🤖 AI Integration**: Native support for AI models like Gemini for intelligent applications
- **⚡ High Performance**: Lightweight core with optimized routing and request handling
- **🔧 Developer Friendly**: Clean API, comprehensive documentation, and easy setup
- **🏢 Enterprise Ready**: Built for scalability and production environments

## ✨ Features

### Core Framework Features

- **Modern Routing System**: RESTful API routing with parameter support
- **Request/Response Handling**: Comprehensive HTTP request parsing and response management
- **Middleware Support**: Built-in security and CORS middleware
- **Environment Configuration**: Dotenv support for environment variables
- **Database Integration**: MySQL database abstraction layer
- **File Upload Support**: Multipart form data handling

### Security Features

- **🛡️ Rate Limiting**: IP-based request rate limiting with configurable limits
- **🔒 Security Headers**: XSS protection, content type sniffing prevention, frame options
- **🌐 CORS Management**: Configurable Cross-Origin Resource Sharing
- **🚫 Method Filtering**: Blocking of dangerous HTTP methods (TRACE, CONNECT, PATCH)
- **🧹 Input Sanitization**: Automatic HTML entity encoding for user inputs
- **🔍 Header Validation**: Protection against suspicious request headers

### AI & Intelligence Features

- **🤖 Gemini AI Integration**: Native support for Google's Gemini AI models
- **📊 Database Analysis**: AI-powered database schema analysis and insights
- **💬 Intelligent Chat**: AI chatbot capabilities for user interaction
- **📈 Data Visualization**: Chart generation and data analysis tools
- **🔍 Smart Caching**: Query caching for improved performance

### Development Tools

- **🚀 Development Server**: Built-in PHP development server with batch script
- **📝 Comprehensive Logging**: Detailed request and AI interaction logging
- **🔧 Easy Configuration**: Simple configuration files for CORS and other settings
- **📦 Composer Integration**: Modern dependency management

## 🚀 Installation

### Prerequisites

- PHP 7.4 or higher
- Composer
- MySQL (for database features)
- Web server (Apache/Nginx) or PHP built-in server

### Quick Installation

#### Method 1: Using Composer (Recommended)

Create a new IgnitePHP project using Composer:

```bash
composer create-project ignitephp/project my-ignitephp-app
cd my-ignitephp-app
```

This will create a new project with all dependencies installed and ready to use.

#### Method 2: Manual Installation

1. **Clone the repository:**
```bash
git clone https://github.com/your-username/ignitephp.git
cd ignitephp
```

2. **Install dependencies:**
```bash
composer install
```

### Post-Installation Setup

1. **Configure environment:**
```bash
cp .env.example .env
# Edit .env with your configuration
```

2. **Start development server:**
```bash
# Windows
serve.bat

# Linux/Mac
php -S localhost:8000
```

## 🏃‍♂️ Quick Start

### Basic API Endpoint

```php
<?php
// routes/api.php
use Ignitephp\Core\Route;
use Ignitephp\Core\Request;

Route::get("/", function () {
    return [
        "message" => "Hello, Welcome to IgnitePHP!",
        "method" => Request::getMethod(),
        "timestamp" => date('Y-m-d H:i:s')
    ];
});

Route::post("/users", function () {
    $data = Request::getJson();
    
    return [
        "status" => "success",
        "message" => "User created",
        "data" => $data
    ];
});
```

### Database Operations

```php
<?php
// app/Database/Db.php usage
use App\Database\Db;

$db = new Db();
$db->connect();

// Insert data
$result = $db->query("INSERT INTO users (name, email) VALUES ('John', 'john@example.com')");
$userId = $db->insert_id();

// Select data
$result = $db->query("SELECT * FROM users WHERE id = $userId");
```

## 🏗️ Architecture

### Project Structure

```
ignitephp/
├── app/                    # Application logic
│   └── Database/          # Database classes
├── config/                # Configuration files
│   └── cors.php          # CORS configuration
├── routes/                # API routes
│   └── api.php           # Main API routes
├── src/                   # Framework core
│   └── core/             # Core framework classes
│       ├── Route.php     # Routing system
│       ├── Router.php    # Route dispatcher
│       ├── Request.php   # Request handling
│       └── Fetch.php     # HTTP client
├── storage/               # Storage directories
│   └── ratelimit/        # Rate limiting data
├── log/                   # Application logs
├── vendor/                # Composer dependencies
├── index.php             # Application entry point
└── serve.bat            # Development server script
```

### Core Components

1. **Route Class**: Handles HTTP method routing (GET, POST, PUT, DELETE, PATCH)
2. **Router Class**: Manages route registration and dispatching
3. **Request Class**: Parses and provides access to HTTP request data
4. **Database Class**: MySQL database abstraction layer
5. **Security Middleware**: Built-in security features and rate limiting

## 🛡️ Security Features

### Rate Limiting

```php
// Automatic rate limiting (100 requests per minute per IP)
// Configurable in index.php
function rateLimit($ip, $limit = 100, $seconds = 60)
```

### Security Headers

```php
// Automatically applied security headers:
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer-when-downgrade");
header("Content-Security-Policy: default-src 'self'");
```

### CORS Configuration

```php
// config/cors.php
return [
    'allow_all_origins' => true,
    'allowed_origins' => [
        'https://example.com',
        'https://anotherdomain.com',
    ],
];
```

## 🤖 AI Integration

### Gemini AI Support

IgnitePHP includes native support for Google's Gemini AI models, enabling:

- **Database Analysis**: AI-powered schema analysis and insights
- **Intelligent Chatbots**: Conversational AI for user interaction
- **Data Processing**: Smart data analysis and visualization
- **Content Generation**: AI-assisted content creation

### AI Features in Action

The framework has been used to analyze complex database schemas for various industries:

- **Healthcare Systems**: Orthodontic treatment management
- **Education**: School management systems
- **Business**: Supply chain and inventory management
- **Retail**: Fuel station management systems

## 💾 Database Management

### Database Configuration

```php
// app/Database/Db.php
class Db
{
    private $localhost = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "your_database";
}
```

### Database Operations

```php
$db = new Db();
$db->connect();

// Query execution
$result = $db->query("SELECT * FROM users");

// Insert operations
$db->query("INSERT INTO users (name) VALUES ('John')");
$newId = $db->insert_id();

// Error handling
if ($db->error()) {
    echo "Database error: " . $db->error();
}
```

## 🔧 API Development

### HTTP Methods Support

```php
// GET requests
Route::get("/users/{id}", function ($id) {
    return ["user_id" => $id];
});

// POST requests
Route::post("/users", function () {
    $data = Request::getJson();
    return ["created" => $data];
});

// PUT requests
Route::put("/users/{id}", function ($id) {
    $data = Request::getJson();
    return ["updated" => $id, "data" => $data];
});

// DELETE requests
Route::delete("/users/{id}", function ($id) {
    return ["deleted" => $id];
});
```

### Request Data Access

```php
// Get request method
$method = Request::getMethod();

// Get query parameters
$params = Request::getQueryParams();
$specificParam = Request::get('param_name');

// Get JSON data
$jsonData = Request::getJson();

// Get form data
$formData = Request::post('field_name');

// Get files
$uploadedFile = Request::files('file_field');
```

## ⚙️ Configuration

### Environment Variables

Create a `.env` file in the `config/` directory:

```env
# Database Configuration
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_NAME=your_database

# AI Configuration
GEMINI_API_KEY=your_gemini_api_key

# Security Configuration
RATE_LIMIT=100
RATE_LIMIT_WINDOW=60
```

### CORS Configuration

```php
// config/cors.php
return [
    'allow_all_origins' => false, // Set to true for development
    'allowed_origins' => [
        'https://yourdomain.com',
        'https://api.yourdomain.com',
    ],
];
```

## 🎯 Use Cases

### 1. Healthcare Management Systems

**Perfect for**: Medical practice management, patient tracking, treatment planning

**Features Used**:
- Secure patient data handling
- AI-powered treatment analysis
- Complex database relationships
- Compliance-ready security

**Example**: Orthodontic treatment management with 3D planning, patient monitoring, and AI-assisted treatment recommendations.

### 2. Educational Platforms

**Perfect for**: School management, student information systems, learning management

**Features Used**:
- Multi-tenant architecture
- Role-based access control
- Academic session management
- Student performance tracking

**Example**: Comprehensive school management system with attendance, grades, library management, and parent communication.

### 3. Business Management Systems

**Perfect for**: CRM, inventory management, supply chain, financial tracking

**Features Used**:
- Complex business logic
- Multi-company support
- Financial transaction handling
- AI-powered insights

**Example**: B2B supply chain management with inventory tracking, customer relationship management, and automated reporting.

### 4. Retail & Service Industries

**Perfect for**: Point of sale systems, service management, customer tracking

**Features Used**:
- Real-time data processing
- Financial management
- Customer relationship management
- Performance analytics

**Example**: Fuel station management system with sales tracking, inventory management, and financial reporting.

### 5. API Development

**Perfect for**: RESTful APIs, microservices, third-party integrations

**Features Used**:
- Clean routing system
- JSON API responses
- Security middleware
- Rate limiting

## 🌟 Why Choose IgnitePHP

### 1. **Security-First Approach**
- Built-in security features from day one
- No need for additional security plugins
- Enterprise-grade protection out of the box

### 2. **AI-Ready Architecture**
- Native AI integration capabilities
- Pre-built AI analysis tools
- Future-proof for AI-driven applications

### 3. **Developer Experience**
- Clean, intuitive API
- Comprehensive documentation
- Easy setup and configuration
- Modern PHP practices

### 4. **Performance Optimized**
- Lightweight core framework
- Efficient routing system
- Built-in caching mechanisms
- Optimized for production

### 5. **Enterprise Features**
- Rate limiting and security headers
- Comprehensive logging
- Database abstraction
- CORS management

### 6. **Flexibility**
- Modular architecture
- Easy to extend
- Works with existing PHP applications
- No vendor lock-in

### 7. **Real-World Proven**
- Used in production healthcare systems
- Handles complex business logic
- Scalable for large applications
- Battle-tested security features

## 🤝 Contributing

We welcome contributions! Please see our contributing guidelines:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

- **Documentation**: [Framework Documentation](docs/)
- **Issues**: [GitHub Issues](https://github.com/your-username/ignitephp/issues)
- **Email**: shoaibshokat6@gmail.com

---

**IgnitePHP** - Ignite your PHP development with modern, secure, and intelligent web applications! 🚀
