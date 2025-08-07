<?php

use Ignite\Core\Route;
use Ignite\Core\Request;

// ============================================
// GET ROUTES - Retrieve Data
// ============================================

Route::get('/', function () {
    return [
        'message' => 'Welcome to IgnitePHP API!',
        'version' => '1.0.0',
        'status' => 'running',
        'timestamp' => date('Y-m-d H:i:s'),
        'endpoints' => [
            'GET /api/users' => 'Get all users',
            'GET /api/users/{id}' => 'Get user by ID',
            'POST /api/users' => 'Create new user',
            'PUT /api/users/{id}' => 'Update user',
            'DELETE /api/users/{id}' => 'Delete user',
            'PATCH /api/users/{id}' => 'Partially update user'
        ]
    ];
});

Route::get('/hello', function () {
    return [
        'message' => 'Hello, World!',
        'greeting' => 'Welcome to IgnitePHP',
        'timestamp' => date('Y-m-d H:i:s')
    ];
});

// Get all users
Route::get('/users', function () {
    $users = [
        [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'role' => 'admin',
            'created_at' => '2024-01-15T10:30:00Z',
            'status' => 'active'
        ],
        [
            'id' => 2,
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'role' => 'user',
            'created_at' => '2024-01-20T14:45:00Z',
            'status' => 'active'
        ],
        [
            'id' => 3,
            'name' => 'Bob Johnson',
            'email' => 'bob.johnson@example.com',
            'role' => 'moderator',
            'created_at' => '2024-01-25T09:15:00Z',
            'status' => 'inactive'
        ]
    ];

    return [
        'status' => 'success',
        'data' => $users,
        'total' => count($users),
        'page' => 1,
        'per_page' => 10
    ];
});

// Get user by ID
Route::get('/users/{id}', function ($id) {
    $users = [
        1 => [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'role' => 'admin',
            'created_at' => '2024-01-15T10:30:00Z',
            'status' => 'active',
            'profile' => [
                'avatar' => 'https://example.com/avatars/john.jpg',
                'bio' => 'Full-stack developer with 5+ years of experience',
                'location' => 'San Francisco, CA',
                'website' => 'https://johndoe.dev'
            ]
        ],
        2 => [
            'id' => 2,
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'role' => 'user',
            'created_at' => '2024-01-20T14:45:00Z',
            'status' => 'active',
            'profile' => [
                'avatar' => 'https://example.com/avatars/jane.jpg',
                'bio' => 'UX Designer passionate about user experience',
                'location' => 'New York, NY',
                'website' => 'https://janesmith.design'
            ]
        ],
        3 => [
            'id' => 3,
            'name' => 'Bob Johnson',
            'email' => 'bob.johnson@example.com',
            'role' => 'moderator',
            'created_at' => '2024-01-25T09:15:00Z',
            'status' => 'inactive',
            'profile' => [
                'avatar' => 'https://example.com/avatars/bob.jpg',
                'bio' => 'Community manager and content creator',
                'location' => 'Austin, TX',
                'website' => 'https://bobjohnson.com'
            ]
        ]
    ];

    if (!isset($users[$id])) {
        http_response_code(404);
        return [
            'status' => 'error',
            'message' => 'User not found',
            'user_id' => $id
        ];
    }

    return [
        'status' => 'success',
        'data' => $users[$id]
    ];
});

// Get posts
Route::get('/posts', function () {
    $posts = [
        [
            'id' => 1,
            'title' => 'Getting Started with IgnitePHP',
            'content' => 'Learn how to build fast APIs with IgnitePHP...',
            'author_id' => 1,
            'author_name' => 'John Doe',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
            'tags' => ['php', 'api', 'framework'],
            'likes' => 42,
            'comments' => 8
        ],
        [
            'id' => 2,
            'title' => 'Advanced Routing Techniques',
            'content' => 'Explore advanced routing patterns and best practices...',
            'author_id' => 2,
            'author_name' => 'Jane Smith',
            'created_at' => '2024-01-20T14:45:00Z',
            'updated_at' => '2024-01-22T16:20:00Z',
            'tags' => ['routing', 'patterns', 'best-practices'],
            'likes' => 28,
            'comments' => 15
        ]
    ];

    return [
        'status' => 'success',
        'data' => $posts,
        'total' => count($posts),
        'page' => 1,
        'per_page' => 10
    ];
});

// Get post by ID
Route::get('/posts/{id}', function ($id) {
    $posts = [
        1 => [
            'id' => 1,
            'title' => 'Getting Started with IgnitePHP',
            'content' => 'IgnitePHP is a blazing-fast, high-performance PHP micro-framework inspired by FastAPI for building modern, scalable, and expressive REST APIs. Built with zero bloat and maximum flexibility, it provides powerful routing, clean syntax, and seamless integration — all while maintaining exceptional performance.',
            'author_id' => 1,
            'author_name' => 'John Doe',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
            'tags' => ['php', 'api', 'framework'],
            'likes' => 42,
            'comments' => 8,
            'comments_data' => [
                [
                    'id' => 1,
                    'user_id' => 2,
                    'user_name' => 'Jane Smith',
                    'content' => 'Great article! Very helpful for beginners.',
                    'created_at' => '2024-01-15T11:00:00Z'
                ],
                [
                    'id' => 2,
                    'user_id' => 3,
                    'user_name' => 'Bob Johnson',
                    'content' => 'The routing examples are excellent.',
                    'created_at' => '2024-01-15T12:30:00Z'
                ]
            ]
        ]
    ];

    if (!isset($posts[$id])) {
        http_response_code(404);
        return [
            'status' => 'error',
            'message' => 'Post not found',
            'post_id' => $id
        ];
    }

    return [
        'status' => 'success',
        'data' => $posts[$id]
    ];
});

// Search users
Route::get('/search/users', function () {
    $query = Request::getQueryParams()['q'] ?? '';
    
    $users = [
        [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'role' => 'admin'
        ],
        [
            'id' => 2,
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'role' => 'user'
        ]
    ];

    // Simulate search
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

// ============================================
// POST ROUTES - Create Data
// ============================================

// Create new user
Route::post('/users', function () {
    $data = Request::getJson();
    
    // Validate required fields
    if (!isset($data['name']) || !isset($data['email'])) {
        http_response_code(400);
        return [
            'status' => 'error',
            'message' => 'Name and email are required',
            'errors' => [
                'name' => isset($data['name']) ? null : 'Name is required',
                'email' => isset($data['email']) ? null : 'Email is required'
            ]
        ];
    }

    // Simulate user creation
    $newUser = [
        'id' => rand(100, 999),
        'name' => $data['name'],
        'email' => $data['email'],
        'role' => $data['role'] ?? 'user',
        'created_at' => date('Y-m-d\TH:i:s\Z'),
        'status' => 'active'
    ];

    return [
        'status' => 'success',
        'message' => 'User created successfully',
        'data' => $newUser
    ];
});

// Create new post
Route::post('/posts', function () {
    $data = Request::getJson();
    
    if (!isset($data['title']) || !isset($data['content'])) {
        http_response_code(400);
        return [
            'status' => 'error',
            'message' => 'Title and content are required'
        ];
    }

    $newPost = [
        'id' => rand(100, 999),
        'title' => $data['title'],
        'content' => $data['content'],
        'author_id' => $data['author_id'] ?? 1,
        'author_name' => $data['author_name'] ?? 'Anonymous',
        'created_at' => date('Y-m-d\TH:i:s\Z'),
        'updated_at' => date('Y-m-d\TH:i:s\Z'),
        'tags' => $data['tags'] ?? [],
        'likes' => 0,
        'comments' => 0
    ];

    return [
        'status' => 'success',
        'message' => 'Post created successfully',
        'data' => $newPost
    ];
});

// Login endpoint
Route::post('/auth/login', function () {
    $data = Request::getJson();
    
    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        return [
            'status' => 'error',
            'message' => 'Email and password are required'
        ];
    }

    // Simulate authentication
    if ($data['email'] === 'admin@example.com' && $data['password'] === 'password') {
        return [
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => 1,
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'role' => 'admin'
                ],
                'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwibmFtZSI6IkFkbWluIFVzZXIiLCJlbWFpbCI6ImFkbWluQGV4YW1wbGUuY29tIiwicm9sZSI6ImFkbWluIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c',
                'expires_at' => date('Y-m-d\TH:i:s\Z', strtotime('+24 hours'))
            ]
        ];
    }

    http_response_code(401);
    return [
        'status' => 'error',
        'message' => 'Invalid credentials'
    ];
});

// ============================================
// PUT ROUTES - Update Data (Full Update)
// ============================================

// Update user (full update)
Route::put('/users/{id}', function ($id) {
    $data = Request::getJson();
    
    if (!isset($data['name']) || !isset($data['email'])) {
        http_response_code(400);
        return [
            'status' => 'error',
            'message' => 'Name and email are required for full update'
        ];
    }

    // Simulate user update
    $updatedUser = [
        'id' => (int)$id,
        'name' => $data['name'],
        'email' => $data['email'],
        'role' => $data['role'] ?? 'user',
        'created_at' => '2024-01-15T10:30:00Z',
        'updated_at' => date('Y-m-d\TH:i:s\Z'),
        'status' => $data['status'] ?? 'active'
    ];

    return [
        'status' => 'success',
        'message' => 'User updated successfully',
        'data' => $updatedUser
    ];
});

// Update post (full update)
Route::put('/posts/{id}', function ($id) {
    $data = Request::getJson();
    
    if (!isset($data['title']) || !isset($data['content'])) {
        http_response_code(400);
        return [
            'status' => 'error',
            'message' => 'Title and content are required for full update'
        ];
    }

    $updatedPost = [
        'id' => (int)$id,
        'title' => $data['title'],
        'content' => $data['content'],
        'author_id' => $data['author_id'] ?? 1,
        'author_name' => $data['author_name'] ?? 'Anonymous',
        'created_at' => '2024-01-15T10:30:00Z',
        'updated_at' => date('Y-m-d\TH:i:s\Z'),
        'tags' => $data['tags'] ?? [],
        'likes' => $data['likes'] ?? 0,
        'comments' => $data['comments'] ?? 0
    ];

    return [
        'status' => 'success',
        'message' => 'Post updated successfully',
        'data' => $updatedPost
    ];
});

// ============================================
// PATCH ROUTES - Partial Update
// ============================================

// Partially update user
Route::patch('/users/{id}', function ($id) {
    $data = Request::getJson();
    
    // Simulate partial update
    $existingUser = [
        'id' => (int)$id,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'role' => 'admin',
        'created_at' => '2024-01-15T10:30:00Z',
        'status' => 'active'
    ];

    // Apply partial updates
    $updatedUser = array_merge($existingUser, $data);
    $updatedUser['updated_at'] = date('Y-m-d\TH:i:s\Z');

    return [
        'status' => 'success',
        'message' => 'User partially updated successfully',
        'data' => $updatedUser
    ];
});

// Partially update post
Route::patch('/posts/{id}', function ($id) {
    $data = Request::getJson();
    
    $existingPost = [
        'id' => (int)$id,
        'title' => 'Getting Started with IgnitePHP',
        'content' => 'Learn how to build fast APIs...',
        'author_id' => 1,
        'author_name' => 'John Doe',
        'created_at' => '2024-01-15T10:30:00Z',
        'tags' => ['php', 'api'],
        'likes' => 42,
        'comments' => 8
    ];

    $updatedPost = array_merge($existingPost, $data);
    $updatedPost['updated_at'] = date('Y-m-d\TH:i:s\Z');

    return [
        'status' => 'success',
        'message' => 'Post partially updated successfully',
        'data' => $updatedPost
    ];
});

// ============================================
// DELETE ROUTES - Remove Data
// ============================================

// Delete user
Route::delete('/users/{id}', function ($id) {
    // Simulate user deletion
    return [
        'status' => 'success',
        'message' => 'User deleted successfully',
        'deleted_user_id' => (int)$id,
        'deleted_at' => date('Y-m-d\TH:i:s\Z')
    ];
});

// Delete post
Route::delete('/posts/{id}', function ($id) {
    return [
        'status' => 'success',
        'message' => 'Post deleted successfully',
        'deleted_post_id' => (int)$id,
        'deleted_at' => date('Y-m-d\TH:i:s\Z')
    ];
});

// ============================================
// UTILITY ROUTES
// ============================================

// Health check
Route::get('/health', function () {
    return [
        'status' => 'healthy',
        'timestamp' => date('Y-m-d\TH:i:s\Z'),
        'uptime' => '2 days, 5 hours, 30 minutes',
        'version' => '1.0.0',
        'environment' => 'development'
    ];
});

// API statistics
Route::get('/stats', function () {
    return [
        'status' => 'success',
        'data' => [
            'total_users' => 1250,
            'total_posts' => 3420,
            'total_comments' => 15680,
            'active_users_today' => 89,
            'new_users_this_week' => 45,
            'api_requests_today' => 15420,
            'average_response_time' => '12ms'
        ],
        'timestamp' => date('Y-m-d\TH:i:s\Z')
    ];
});

// Echo route for testing
Route::post('/echo', function () {
    $data = Request::getJson();
    $headers = Request::getHeaders();
    $queryParams = Request::getQueryParams();
    
    return [
        'status' => 'success',
        'message' => 'Echo response',
        'data' => [
            'body' => $data,
            'headers' => $headers,
            'query_params' => $queryParams,
            'method' => Request::getMethod(),
            'timestamp' => date('Y-m-d\TH:i:s\Z')
        ]
    ];
});
