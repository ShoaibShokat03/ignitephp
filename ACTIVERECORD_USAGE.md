# ActiveRecord Usage Guide

The Model class now supports Yii2-like ActiveRecord functionality with fluent query building, relationships, and eager loading.

## Basic Usage

### Get All Records
```php
$users = UsersModel::all();
```

### Query with Conditions
```php
// Simple where
$activeUsers = UsersModel::where('status', 'active')->all();

// Where with operator
$adults = UsersModel::where('age', '>', 18)->all();

// Multiple conditions
$users = UsersModel::where('status', 'active')
    ->andWhere('age', '>', 18)
    ->all();

// OR conditions
$users = UsersModel::where('status', 'active')
    ->orWhere('status', 'pending')
    ->all();

// Array where (multiple AND conditions)
$users = UsersModel::where(['status' => 'active', 'verified' => 1])->all();
```

### Filter Helper
```php
// Alias of where() that keeps the chain expressive
$users = UsersModel::filter(['status' => 'active'])
    ->filter('age', '>', 18)
    ->all();

// Use a callable for complex conditions
$users = UsersModel::filter(function ($query) {
    $query->where('status', 'active')
        ->orWhere('status', 'pending');
})->all();
```

### Get Single Record
```php
// Using where
$user = UsersModel::where('id', 1)->one();

// Using find
$user = UsersModel::find(1);

// Find or throw exception
$user = UsersModel::findOrFail(1);
```

### Ordering and Limiting
```php
$users = UsersModel::where('status', 'active')
    ->orderBy('created_at', 'DESC')
    ->orderBy('name', 'ASC')
    ->limit(10)
    ->offset(20)
    ->all();
```

### Pagination Shortcuts (skip / take)
```php
$users = UsersModel::skip(20)  // same as offset(20)
    ->take(10)                 // same as limit(10)
    ->orderBy('created_at', 'DESC')
    ->all();
```

### Select Specific Columns
```php
$users = UsersModel::select(['id', 'name', 'email'])
    ->where('status', 'active')
    ->all();
```

### Count Records
```php
$count = UsersModel::where('status', 'active')->count();
$exists = UsersModel::where('id', 1)->exists();
```

### Search Across Columns
```php
$term = 'john';

// Generates (name LIKE '%john%' OR email LIKE '%john%')
$users = UsersModel::search($term, ['name', 'email'])->all();

// Combine with additional filters
$users = UsersModel::filter('status', 'active')
    ->search($term, ['name', 'email'])
    ->take(20)
    ->all();
```

### WHERE IN / WHERE NOT IN
```php
$users = UsersModel::whereIn('id', [1, 2, 3, 4, 5])->all();
$users = UsersModel::whereNotIn('status', ['deleted', 'banned'])->all();
```

## Creating and Updating Records

### Create New Record
```php
$user = UsersModel::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'status' => 'active'
]);
```

### Update Record
```php
$user = UsersModel::find(1);
$user->name = 'Jane Doe';
$user->email = 'jane@example.com';
$user->save();

// Or update specific attributes
$user->update(['name' => 'Jane Doe']);
```

### Delete Record
```php
$user = UsersModel::find(1);
$user->delete();
```

## Relationships

### Define Relationships

In your model class, define relationships:

```php
class UsersModel extends Model
{
    protected string $table = 'users';

    // User has many posts
    public function posts()
    {
        return $this->hasMany(PostsModel::class, 'user_id');
    }

    // User has one profile
    public function profile()
    {
        return $this->hasOne(ProfileModel::class, 'user_id');
    }

    // User belongs to a company
    public function company()
    {
        return $this->belongsTo(CompanyModel::class, 'company_id');
    }
}
```

### Access Relationships

```php
$user = UsersModel::find(1);

// Lazy loading
$posts = $user->posts; // Returns array of PostModel instances
$profile = $user->profile; // Returns ProfileModel instance or null
$company = $user->company; // Returns CompanyModel instance or null
```

### Eager Loading (with())

Eager load relationships to avoid N+1 query problem:

```php
// Load users with their posts
$users = UsersModel::with('posts')->all();

// Load multiple relationships
$users = UsersModel::with(['posts', 'profile', 'company'])->all();

// Access relationships
foreach ($users as $user) {
    echo $user->name;
    foreach ($user->posts as $post) {
        echo $post->title;
    }
}
```

## Auto-Detect Relationships from Database

The Model class can automatically detect relationships from database foreign keys:

```php
// Detect all relationships for a model
$relationships = UsersModel::detectRelationships();
print_r($relationships);

// Generate relationship method code
$code = UsersModel::generateRelationships();
echo $code;
// This will output ready-to-use relationship methods based on your database schema
```

## Converting to Array/JSON

```php
$user = UsersModel::find(1);

// Convert to array (includes relationships if loaded)
$array = $user->toArray();

// For JSON response
return json_encode($user->toArray());
```

## Complete Example

```php
// Get active users with their posts, ordered by creation date
$users = UsersModel::where('status', 'active')
    ->with('posts')
    ->orderBy('created_at', 'DESC')
    ->limit(10)
    ->all();

// Process users
foreach ($users as $user) {
    echo "User: {$user->name}\n";
    echo "Email: {$user->email}\n";
    
    // Posts are already loaded (eager loading)
    foreach ($user->posts as $post) {
        echo "  - Post: {$post->title}\n";
    }
}
```

## Relationship Types

### hasOne
One-to-one relationship. User has one Profile.
```php
public function profile()
{
    return $this->hasOne(ProfileModel::class, 'user_id');
}
```

### hasMany
One-to-many relationship. User has many Posts.
```php
public function posts()
{
    return $this->hasMany(PostsModel::class, 'user_id');
}
```

### belongsTo
Many-to-one relationship. Post belongs to User.
```php
public function user()
{
    return $this->belongsTo(UsersModel::class, 'user_id');
}
```

## Notes

- All queries are automatically escaped to prevent SQL injection
- Relationships are lazy-loaded by default (loaded when accessed)
- Use `with()` for eager loading to improve performance
- The `toArray()` method includes relationships if they're loaded
- Auto-detection of relationships works by analyzing foreign keys in your database

