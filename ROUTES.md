# 🚀 IgnitePHP API Routes Documentation

Complete list of all available API endpoints with HTTP methods, payloads, and example responses.

---

## 📋 Table of Contents

- [GET Endpoints](#-get-endpoints)
- [POST Endpoints](#-post-endpoints)
- [PUT Endpoints](#-put-endpoints)
- [PATCH Endpoints](#-patch-endpoints)
- [DELETE Endpoints](#-delete-endpoints)
- [Testing Examples](#-testing-examples)

---

## 📋 GET Endpoints

### 1. Welcome Message
**Endpoint:** `GET /api/`  
**Description:** Welcome message with API documentation  
**Parameters:** None  
**Headers:** None  

**Response:**
```json
{
  "message": "Welcome to IgnitePHP API!",
  "version": "1.0.0",
  "status": "running",
  "timestamp": "2024-01-15 10:30:00",
  "endpoints": {
    "GET /api/users": "Get all users",
    "GET /api/users/{id}": "Get user by ID",
    "POST /api/users": "Create new user",
    "PUT /api/users/{id}": "Update user",
    "DELETE /api/users/{id}": "Delete user",
    "PATCH /api/users/{id}": "Partially update user"
  }
}
```

### 2. Hello World
**Endpoint:** `GET /api/hello`  
**Description:** Simple greeting endpoint  
**Parameters:** None  
**Headers:** None  

**Response:**
```json
{
  "message": "Hello, World!",
  "greeting": "Welcome to IgnitePHP",
  "timestamp": "2024-01-15 10:30:00"
}
```

### 3. Get All Users
**Endpoint:** `GET /api/users`  
**Description:** Retrieve all users with pagination  
**Parameters:** None  
**Headers:** None  

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "role": "admin",
      "created_at": "2024-01-15T10:30:00Z",
      "status": "active"
    },
    {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane.smith@example.com",
      "role": "user",
      "created_at": "2024-01-20T14:45:00Z",
      "status": "active"
    },
    {
      "id": 3,
      "name": "Bob Johnson",
      "email": "bob.johnson@example.com",
      "role": "moderator",
      "created_at": "2024-01-25T09:15:00Z",
      "status": "inactive"
    }
  ],
  "total": 3,
  "page": 1,
  "per_page": 10
}
```

### 4. Get User by ID
**Endpoint:** `GET /api/users/{id}`  
**Description:** Retrieve specific user with detailed profile  
**Parameters:** 
- `id` (path parameter) - User ID (integer)

**Headers:** None  

**Response (Success):**
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

**Response (Error - 404):**
```json
{
  "status": "error",
  "message": "User not found",
  "user_id": "999"
}
```

### 5. Get All Posts
**Endpoint:** `GET /api/posts`  
**Description:** Retrieve all posts with metadata  
**Parameters:** None  
**Headers:** None  

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Getting Started with IgnitePHP",
      "content": "Learn how to build fast APIs with IgnitePHP...",
      "author_id": 1,
      "author_name": "John Doe",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "tags": ["php", "api", "framework"],
      "likes": 42,
      "comments": 8
    },
    {
      "id": 2,
      "title": "Advanced Routing Techniques",
      "content": "Explore advanced routing patterns and best practices...",
      "author_id": 2,
      "author_name": "Jane Smith",
      "created_at": "2024-01-20T14:45:00Z",
      "updated_at": "2024-01-22T16:20:00Z",
      "tags": ["routing", "patterns", "best-practices"],
      "likes": 28,
      "comments": 15
    }
  ],
  "total": 2,
  "page": 1,
  "per_page": 10
}
```

### 6. Get Post by ID
**Endpoint:** `GET /api/posts/{id}`  
**Description:** Retrieve specific post with comments  
**Parameters:** 
- `id` (path parameter) - Post ID (integer)

**Headers:** None  

**Response (Success):**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "title": "Getting Started with IgnitePHP",
    "content": "IgnitePHP is a blazing-fast, high-performance PHP micro-framework inspired by FastAPI for building modern, scalable, and expressive REST APIs. Built with zero bloat and maximum flexibility, it provides powerful routing, clean syntax, and seamless integration — all while maintaining exceptional performance.",
    "author_id": 1,
    "author_name": "John Doe",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "tags": ["php", "api", "framework"],
    "likes": 42,
    "comments": 8,
    "comments_data": [
      {
        "id": 1,
        "user_id": 2,
        "user_name": "Jane Smith",
        "content": "Great article! Very helpful for beginners.",
        "created_at": "2024-01-15T11:00:00Z"
      },
      {
        "id": 2,
        "user_id": 3,
        "user_name": "Bob Johnson",
        "content": "The routing examples are excellent.",
        "created_at": "2024-01-15T12:30:00Z"
      }
    ]
  }
}
```

**Response (Error - 404):**
```json
{
  "status": "error",
  "message": "Post not found",
  "post_id": "999"
}
```

### 7. Search Users
**Endpoint:** `GET /api/search/users`  
**Description:** Search users by name or email  
**Parameters:** 
- `q` (query parameter) - Search query (string)

**Headers:** None  

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "role": "admin"
    }
  ],
  "query": "john",
  "total": 1
}
```

### 8. Health Check
**Endpoint:** `GET /api/health`  
**Description:** API health status  
**Parameters:** None  
**Headers:** None  

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2024-01-15T10:30:00Z",
  "uptime": "2 days, 5 hours, 30 minutes",
  "version": "1.0.0",
  "environment": "development"
}
```

### 9. API Statistics
**Endpoint:** `GET /api/stats`  
**Description:** API usage statistics  
**Parameters:** None  
**Headers:** None  

**Response:**
```json
{
  "status": "success",
  "data": {
    "total_users": 1250,
    "total_posts": 3420,
    "total_comments": 15680,
    "active_users_today": 89,
    "new_users_this_week": 45,
    "api_requests_today": 15420,
    "average_response_time": "12ms"
  },
  "timestamp": "2024-01-15T10:30:00Z"
}
```

---

## ➕ POST Endpoints

### 10. Create User
**Endpoint:** `POST /api/users`  
**Description:** Create a new user  
**Parameters:** None  
**Headers:** `Content-Type: application/json`  

**Payload:**
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "role": "user"
}
```

**Response (Success):**
```json
{
  "status": "success",
  "message": "User created successfully",
  "data": {
    "id": 456,
    "name": "New User",
    "email": "newuser@example.com",
    "role": "user",
    "created_at": "2024-01-15T10:30:00Z",
    "status": "active"
  }
}
```

**Response (Error - 400):**
```json
{
  "status": "error",
  "message": "Name and email are required",
  "errors": {
    "name": "Name is required",
    "email": null
  }
}
```

### 11. Create Post
**Endpoint:** `POST /api/posts`  
**Description:** Create a new post  
**Parameters:** None  
**Headers:** `Content-Type: application/json`  

**Payload:**
```json
{
  "title": "New Blog Post",
  "content": "This is the content of the new blog post...",
  "author_id": 1,
  "author_name": "John Doe",
  "tags": ["blog", "tutorial"]
}
```

**Response (Success):**
```json
{
  "status": "success",
  "message": "Post created successfully",
  "data": {
    "id": 789,
    "title": "New Blog Post",
    "content": "This is the content of the new blog post...",
    "author_id": 1,
    "author_name": "John Doe",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "tags": ["blog", "tutorial"],
    "likes": 0,
    "comments": 0
  }
}
```

**Response (Error - 400):**
```json
{
  "status": "error",
  "message": "Title and content are required"
}
```

### 12. User Login
**Endpoint:** `POST /api/auth/login`  
**Description:** Authenticate user and get access token  
**Parameters:** None  
**Headers:** `Content-Type: application/json`  

**Payload:**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

**Response (Success):**
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
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwibmFtZSI6IkFkbWluIFVzZXIiLCJlbWFpbCI6ImFkbWluQGV4YW1wbGUuY29tIiwicm9sZSI6ImFkbWluIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c",
    "expires_at": "2024-01-16T10:30:00Z"
  }
}
```

**Response (Error - 400):**
```json
{
  "status": "error",
  "message": "Email and password are required"
}
```

**Response (Error - 401):**
```json
{
  "status": "error",
  "message": "Invalid credentials"
}
```

### 13. Echo Test
**Endpoint:** `POST /api/echo`  
**Description:** Echo back request data for testing  
**Parameters:** None  
**Headers:** `Content-Type: application/json`  

**Payload:**
```json
{
  "test": "data",
  "message": "Hello World"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Echo response",
  "data": {
    "body": {
      "test": "data",
      "message": "Hello World"
    },
    "headers": {
      "Content-Type": "application/json",
      "User-Agent": "curl/7.68.0"
    },
    "query_params": [],
    "method": "POST",
    "timestamp": "2024-01-15T10:30:00Z"
  }
}
```

---

## 🔄 PUT Endpoints

### 14. Update User (Full Update)
**Endpoint:** `PUT /api/users/{id}`  
**Description:** Fully update a user (all fields required)  
**Parameters:** 
- `id` (path parameter) - User ID (integer)

**Headers:** `Content-Type: application/json`  

**Payload:**
```json
{
  "name": "Updated Name",
  "email": "updated@example.com",
  "role": "moderator",
  "status": "active"
}
```

**Response (Success):**
```json
{
  "status": "success",
  "message": "User updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Name",
    "email": "updated@example.com",
    "role": "moderator",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "status": "active"
  }
}
```

**Response (Error - 400):**
```json
{
  "status": "error",
  "message": "Name and email are required for full update"
}
```

### 15. Update Post (Full Update)
**Endpoint:** `PUT /api/posts/{id}`  
**Description:** Fully update a post (all fields required)  
**Parameters:** 
- `id` (path parameter) - Post ID (integer)

**Headers:** `Content-Type: application/json`  

**Payload:**
```json
{
  "title": "Updated Post Title",
  "content": "Updated post content...",
  "author_id": 2,
  "author_name": "Jane Smith",
  "tags": ["updated", "content"],
  "likes": 50,
  "comments": 10
}
```

**Response (Success):**
```json
{
  "status": "success",
  "message": "Post updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Post Title",
    "content": "Updated post content...",
    "author_id": 2,
    "author_name": "Jane Smith",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "tags": ["updated", "content"],
    "likes": 50,
    "comments": 10
  }
}
```

**Response (Error - 400):**
```json
{
  "status": "error",
  "message": "Title and content are required for full update"
}
```

---

## 🔧 PATCH Endpoints

### 16. Partially Update User
**Endpoint:** `PATCH /api/users/{id}`  
**Description:** Partially update a user (only provided fields)  
**Parameters:** 
- `id` (path parameter) - User ID (integer)

**Headers:** `Content-Type: application/json`  

**Payload:**
```json
{
  "name": "New Name Only"
}
```

**Response (Success):**
```json
{
  "status": "success",
  "message": "User partially updated successfully",
  "data": {
    "id": 1,
    "name": "New Name Only",
    "email": "john.doe@example.com",
    "role": "admin",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "status": "active"
  }
}
```

### 17. Partially Update Post
**Endpoint:** `PATCH /api/posts/{id}`  
**Description:** Partially update a post (only provided fields)  
**Parameters:** 
- `id` (path parameter) - Post ID (integer)

**Headers:** `Content-Type: application/json`  

**Payload:**
```json
{
  "title": "Updated Title Only",
  "likes": 100
}
```

**Response (Success):**
```json
{
  "status": "success",
  "message": "Post partially updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Title Only",
    "content": "Learn how to build fast APIs...",
    "author_id": 1,
    "author_name": "John Doe",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "tags": ["php", "api"],
    "likes": 100,
    "comments": 8
  }
}
```

---

## 🗑️ DELETE Endpoints

### 18. Delete User
**Endpoint:** `DELETE /api/users/{id}`  
**Description:** Delete a user  
**Parameters:** 
- `id` (path parameter) - User ID (integer)

**Headers:** None  

**Response (Success):**
```json
{
  "status": "success",
  "message": "User deleted successfully",
  "deleted_user_id": 1,
  "deleted_at": "2024-01-15T10:30:00Z"
}
```

### 19. Delete Post
**Endpoint:** `DELETE /api/posts/{id}`  
**Description:** Delete a post  
**Parameters:** 
- `id` (path parameter) - Post ID (integer)

**Headers:** None  

**Response (Success):**
```json
{
  "status": "success",
  "message": "Post deleted successfully",
  "deleted_post_id": 1,
  "deleted_at": "2024-01-15T10:30:00Z"
}
```

---

## 🧪 Testing Examples

### Using cURL

```bash
# GET requests
curl http://localhost/your-project/api/
curl http://localhost/your-project/api/users
curl http://localhost/your-project/api/users/1
curl http://localhost/your-project/api/posts
curl http://localhost/your-project/api/health
curl "http://localhost/your-project/api/search/users?q=john"

# POST requests
curl -X POST http://localhost/your-project/api/users \
  -H "Content-Type: application/json" \
  -d '{"name": "New User", "email": "new@example.com"}'

curl -X POST http://localhost/your-project/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password"}'

# PUT requests
curl -X PUT http://localhost/your-project/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "Updated Name", "email": "updated@example.com"}'

# PATCH requests
curl -X PATCH http://localhost/your-project/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "New Name Only"}'

# DELETE requests
curl -X DELETE http://localhost/your-project/api/users/1
```

### Using JavaScript/Fetch

```javascript
// GET request
fetch('/api/users')
  .then(response => response.json())
  .then(data => console.log(data));

// POST request
fetch('/api/users', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: 'New User',
    email: 'new@example.com'
  })
})
.then(response => response.json())
.then(data => console.log(data));

// PUT request
fetch('/api/users/1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: 'Updated Name',
    email: 'updated@example.com'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Using Python/Requests

```python
import requests

# GET request
response = requests.get('http://localhost/your-project/api/users')
print(response.json())

# POST request
data = {
    'name': 'New User',
    'email': 'new@example.com'
}
response = requests.post('http://localhost/your-project/api/users', json=data)
print(response.json())

# PUT request
data = {
    'name': 'Updated Name',
    'email': 'updated@example.com'
}
response = requests.put('http://localhost/your-project/api/users/1', json=data)
print(response.json())
```

---

## 📊 HTTP Status Codes

| Status Code | Description | Usage |
|-------------|-------------|-------|
| 200 | OK | Successful GET, PUT, PATCH requests |
| 201 | Created | Successful POST requests |
| 400 | Bad Request | Invalid payload or missing required fields |
| 401 | Unauthorized | Invalid credentials |
| 404 | Not Found | Resource not found |
| 500 | Internal Server Error | Server error |

---

## 🔐 Authentication

For protected endpoints, include the Authorization header:

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost/your-project/api/protected-endpoint
```

**Note:** Currently, the framework includes a demo authentication endpoint. For production use, implement proper JWT validation and middleware.

---

## 📝 Notes

- All timestamps are in ISO 8601 format
- All responses are in JSON format
- The framework automatically handles CORS for cross-origin requests
- Error responses include appropriate HTTP status codes
- Query parameters are case-sensitive
- Path parameters must be valid integers for ID-based endpoints

---

<div align="center">

**🚀 Built with IgnitePHP - High-Performance PHP Micro-Framework**

</div>
