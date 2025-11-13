<?php

namespace App\Core;

use App\Database\Db;

/**
 * Base Model Class with ActiveRecord-like functionality
 * All models should extend this class
 * 
 * Usage:
 * - UsersModel::all() - Get all records
 * - UsersModel::where('status', 'active')->all() - Query with conditions
 * - UsersModel::where(['id' => 1])->one() - Get single record
 * - UsersModel::with('posts')->all() - Eager load relationships
 */
abstract class Model
{
    protected ?Db $db = null;
    protected string $table;
    protected array $attributes = [];
    protected array $relations = [];
    protected static ?Db $staticDb = null;

    // Relationship cache
    protected static array $relationshipCache = [];
    
    // Reflection cache for performance
    protected static array $reflectionCache = [];
    
    // Query result cache (simple in-memory cache)
    protected static array $queryCache = [];
    protected static int $queryCacheSize = 100;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        // Unset public properties initially so __get() is always called
        // This ensures property access goes through __get() which checks attributes
        // We don't sync here - properties will be set lazily when accessed via __get()
        $this->unsetPublicProperties();
    }
    
    /**
     * Unset all public properties so __get() is called when accessing them
     * This ensures that property access always checks the attributes array first
     */
    protected function unsetPublicProperties(): void
    {
        $className = static::class;
        
        // Cache reflection for performance
        if (!isset(self::$reflectionCache[$className])) {
            $reflection = new \ReflectionClass($this);
            $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
            
            $propertyNames = [];
            foreach ($properties as $property) {
                $name = $property->getName();
                // Skip internal properties
                if ($name !== 'db' && $name !== 'table' && $name !== 'attributes' && $name !== 'relations') {
                    $propertyNames[] = $name;
                }
            }
            self::$reflectionCache[$className] = $propertyNames;
        }
        
        // Unset all public properties so __get() is called
        foreach (self::$reflectionCache[$className] as $name) {
            unset($this->$name);
        }
    }

    /**
     * Get database instance (instance method)
     */
    public function getDb(): Db
    {
        if ($this->db === null) {
            $this->db = new Db();
            $this->db->connect();
        }
        return $this->db;
    }

    /**
     * Get database instance (static method)
     */
    protected static function getStaticDb(): Db
    {
        if (self::$staticDb === null) {
            self::$staticDb = new Db();
            self::$staticDb->connect();
        }
        return self::$staticDb;
    }

    /**
     * Get table name
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Create new instance with attributes
     * Optimized to avoid unnecessary operations
     * Properties are unset initially and will be set lazily when accessed via __get()
     */
    public function newInstance(array $attributes): self
    {
        $instance = new static();
        $instance->attributes = $attributes;
        // Unset properties so __get() is always called when accessing them
        $instance->unsetPublicProperties();
        return $instance;
    }

    /**
     * Sync attributes array to public properties
     * This allows both $model->username and $model->attributes['username'] to work
     * Note: Properties are unset initially, so setting them will make them accessible
     */
    protected function syncAttributesToProperties(): void
    {
        foreach ($this->attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Sync public properties back to attributes array
     * This ensures that when properties are set directly, they're saved to attributes
     * Optimized with reflection caching
     * Note: Only syncs properties that are actually set (not unset)
     */
    protected function syncPropertiesToAttributes(): void
    {
        $className = static::class;
        
        // Use cached reflection to avoid repeated ReflectionClass instantiation
        if (!isset(self::$reflectionCache[$className])) {
            $reflection = new \ReflectionClass($this);
            $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
            
            // Cache property names for this class
            $propertyNames = [];
            foreach ($properties as $property) {
                $name = $property->getName();
                // Skip internal properties
                if ($name !== 'db' && $name !== 'table' && $name !== 'attributes' && $name !== 'relations') {
                    $propertyNames[] = $name;
                }
            }
            self::$reflectionCache[$className] = $propertyNames;
        }
        
        // Use cached property names and check if property is initialized
        $reflection = new \ReflectionClass($this);
        foreach (self::$reflectionCache[$className] as $name) {
            // Check if property exists and is initialized (not unset)
            if ($reflection->hasProperty($name)) {
                $property = $reflection->getProperty($name);
                if ($property->isInitialized($this)) {
                    $this->attributes[$name] = $this->$name;
                }
            }
        }
    }

    /**
     * Get attribute value
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Set attribute value
     */
    public function setAttribute(string $name, $value): void
    {
        $this->attributes[$name] = $value;
        // Sync to public property if it exists
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    /**
     * Get all attributes
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Set relation
     */
    public function setRelation(string $name, $value): void
    {
        $this->relations[$name] = $value;
    }

    /**
     * Get relation
     */
    public function getRelation(string $name)
    {
        return $this->relations[$name] ?? null;
    }

    /**
     * Magic method to get attributes and relations
     * This is called when accessing a property that doesn't exist or is unset
     */
    public function __get(string $name)
    {
        // Check relations first
        if (isset($this->relations[$name])) {
            return $this->relations[$name];
        }

        // Check attributes - this is the primary source of data
        if (isset($this->attributes[$name])) {
            $value = $this->attributes[$name];
            // Set the property so subsequent accesses are faster (but still go through __get if unset)
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
            return $value;
        }

        // Check for relationship method
        if (method_exists($this, $name)) {
            $relation = $this->$name();
            if (is_array($relation) && isset($relation['type'])) {
                return $this->loadRelation($name, $relation);
            }
        }

        return null;
    }

    /**
     * Magic method to set attributes
     */
    public function __set(string $name, $value): void
    {
        $this->attributes[$name] = $value;
        // Sync to public property if it exists
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    /**
     * Check if attribute exists
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]) || isset($this->relations[$name]);
    }

    /**
     * Convert model to array
     */
    public function toArray(): array
    {
        $array = $this->attributes;
        
        // Include relations if loaded
        foreach ($this->relations as $key => $value) {
            if (is_array($value)) {
                $array[$key] = array_map(function($item) {
                    return $item instanceof Model ? $item->toArray() : $item;
                }, $value);
            } elseif ($value instanceof Model) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Load a relationship
     */
    protected function loadRelation(string $name, array $relation)
    {
        $type = $relation['type'];
        $relatedClass = $relation['class'];
        $foreignKey = $relation['foreignKey'];
        $localKey = $relation['localKey'] ?? 'id';

        $localValue = $this->getAttribute($localKey);

        if ($type === 'hasOne') {
            $related = $relatedClass::where($foreignKey, $localValue)->one();
            $this->setRelation($name, $related);
            return $related;
        } elseif ($type === 'hasMany') {
            $related = $relatedClass::where($foreignKey, $localValue)->all();
            $this->setRelation($name, $related);
            return $related;
        } elseif ($type === 'belongsTo') {
            $related = $relatedClass::where($relation['localKey'] ?? 'id', $this->getAttribute($foreignKey))->one();
            $this->setRelation($name, $related);
            return $related;
        }

        return null;
    }

    // ============================================
    // STATIC METHODS (ActiveRecord-like)
    // ============================================

    /**
     * Get all records
     * Usage: UsersModel::all()
     */
    public static function all(): array
    {
        return static::where()->all();
    }

    /**
     * Start a query builder
     * Usage: UsersModel::where('status', 'active')->all()
     * Optimized: Reuses static DB connection
     */
    public static function where(?string $column = null, $operator = null, $value = null): QueryBuilder
    {
        $model = new static();
        // Reuse static DB connection for better performance
        if (self::$staticDb === null) {
            self::$staticDb = new Db();
            self::$staticDb->connect();
        }
        $model->db = self::$staticDb;
        
        $query = new QueryBuilder($model);
        
        if ($column !== null) {
            $query->where($column, $operator, $value);
        }
        
        return $query;
    }
    
    /**
     * Alias for where() - allows filter() method for better readability
     * Usage: Model::filter('status', 'active')->filter('role', 'admin')->all()
     */
    public static function filter(?string $column = null, $operator = null, $value = null): QueryBuilder
    {
        return static::where($column, $operator, $value);
    }

    /**
     * Find record by ID
     * Usage: UsersModel::find(1)
     */
    public static function find(int $id): ?self
    {
        return static::where('id', $id)->one();
    }

    /**
     * Find record by ID or throw exception
     */
    public static function findOrFail(int $id): self
    {
        $record = static::find($id);
        if ($record === null) {
            throw new \Exception("Record with ID {$id} not found in " . static::class);
        }
        return $record;
    }

    /**
     * Create new record
     * Usage: UsersModel::create(['name' => 'John', 'email' => 'john@example.com'])
     * Optimized: Uses single query, avoids unnecessary find() call
     */
    public static function create(array $attributes): ?self
    {
        $model = new static();
        $db = $model->getDb();
        
        $fields = [];
        $values = [];
        
        foreach ($attributes as $key => $value) {
            if ($key !== 'id') {
                $fields[] = $key;
                if ($value === null) {
                    $values[] = "NULL";
                } else {
                    $values[] = "'" . $db->escape($value) . "'";
                }
            }
        }
        
        if (empty($fields)) {
            return null;
        }
        
        $sql = "INSERT INTO `{$model->table}` (`" . implode('`, `', $fields) . "`) VALUES (" . implode(', ', $values) . ")";
        
        if ($db->query($sql)) {
            $id = $db->insert_id();
            // Optimize: Set attributes directly instead of querying again
            $attributes['id'] = $id;
            $model->attributes = $attributes;
            $model->syncAttributesToProperties();
            // Clear query cache for this table
            static::clearQueryCache();
            return $model;
        }
        
        return null;
    }
    
    /**
     * Clear query cache for this model's table
     */
    protected static function clearQueryCache(): void
    {
        $model = new static();
        $table = $model->getTable();
        foreach (self::$queryCache as $key => $value) {
            if (strpos($key, $table) !== false) {
                unset(self::$queryCache[$key]);
            }
        }
    }

    /**
     * Update record
     */
    public function update(array $attributes = []): bool
    {
        if (empty($attributes)) {
            $attributes = $this->attributes;
        }

        $db = $this->getDb();
        $id = $this->getAttribute('id');
        
        if (!$id) {
            return false;
        }
        
        $updates = [];
        foreach ($attributes as $key => $value) {
            if ($key !== 'id') {
                if ($value === null) {
                    $updates[] = "`{$key}` = NULL";
                } else {
                    $updates[] = "`{$key}` = '" . $db->escape($value) . "'";
                }
                $this->setAttribute($key, $value);
            }
        }
        // Ensure all attributes are synced to properties after update
        $this->syncAttributesToProperties();
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE `{$this->table}` SET " . implode(', ', $updates) . " WHERE `id` = " . (int)$id;
        
        return $db->query($sql) !== false;
    }

    /**
     * Delete record
     */
    public function delete(): bool
    {
        $db = $this->getDb();
        $id = $this->getAttribute('id');
        
        if (!$id) {
            return false;
        }
        
        $sql = "DELETE FROM `{$this->table}` WHERE `id` = " . (int)$id;
        
        return $db->query($sql) !== false;
    }

    /**
     * Save record (insert or update)
     */
    public function save(): bool
    {
        // Sync public properties back to attributes before saving
        // This ensures that direct property assignments (e.g., $model->username = 'value')
        // are captured in the attributes array
        $this->syncPropertiesToAttributes();
        
        $id = $this->getAttribute('id');
        
        if ($id) {
            return $this->update();
        } else {
            $created = static::create($this->attributes);
            if ($created) {
                $this->attributes = $created->attributes;
                // Sync attributes to public properties after creation
                $this->syncAttributesToProperties();
                return true;
            }
            return false;
        }
    }

    // ============================================
    // RELATIONSHIP METHODS
    // ============================================

    /**
     * Define hasOne relationship
     * Usage: return $this->hasOne(ProfileModel::class, 'user_id');
     */
    protected function hasOne(string $relatedClass, string $foreignKey, string $localKey = 'id'): array
    {
        return [
            'type' => 'hasOne',
            'class' => $relatedClass,
            'foreignKey' => $foreignKey,
            'localKey' => $localKey
        ];
    }

    /**
     * Define hasMany relationship
     * Usage: return $this->hasMany(PostModel::class, 'user_id');
     */
    protected function hasMany(string $relatedClass, string $foreignKey, string $localKey = 'id'): array
    {
        return [
            'type' => 'hasMany',
            'class' => $relatedClass,
            'foreignKey' => $foreignKey,
            'localKey' => $localKey
        ];
    }

    /**
     * Define belongsTo relationship
     * Usage: return $this->belongsTo(UserModel::class, 'user_id');
     */
    protected function belongsTo(string $relatedClass, string $foreignKey, string $localKey = 'id'): array
    {
        return [
            'type' => 'belongsTo',
            'class' => $relatedClass,
            'foreignKey' => $foreignKey,
            'localKey' => $localKey
        ];
    }

    // ============================================
    // AUTO-RELATIONSHIP DETECTION
    // ============================================

    /**
     * Auto-detect relationships from database schema
     * This method analyzes foreign keys and suggests relationships
     */
    public static function autoDetectRelationships(): array
    {
        $model = new static();
        $db = $model->getDb();
        $table = $model->getTable();
        $dbName = $db->db_name;

        $relationships = [];

        // Get all foreign keys for this table
        $sql = "SELECT 
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = '{$dbName}'
                AND TABLE_NAME = '{$table}'
                AND REFERENCED_TABLE_NAME IS NOT NULL";

        $result = $db->query($sql);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $columnName = $row['COLUMN_NAME'];
                $referencedTable = $row['REFERENCED_TABLE_NAME'];
                $referencedColumn = $row['REFERENCED_COLUMN_NAME'];

                // Try to find the model class for referenced table
                $relatedModelClass = static::findModelClass($referencedTable);
                
                if ($relatedModelClass) {
                    $relationships[] = [
                        'type' => 'belongsTo',
                        'column' => $columnName,
                        'related_table' => $referencedTable,
                        'related_class' => $relatedModelClass,
                        'foreign_key' => $columnName,
                        'local_key' => $referencedColumn
                    ];
                }
            }
        }

        // Get tables that reference this table
        $sql = "SELECT 
                    TABLE_NAME,
                    COLUMN_NAME,
                    REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = '{$dbName}'
                AND REFERENCED_TABLE_NAME = '{$table}'
                AND REFERENCED_COLUMN_NAME IS NOT NULL";

        $result = $db->query($sql);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $referencingTable = $row['TABLE_NAME'];
                $columnName = $row['COLUMN_NAME'];
                $referencedColumn = $row['REFERENCED_COLUMN_NAME'];

                // Try to find the model class for referencing table
                $relatedModelClass = static::findModelClass($referencingTable);
                
                if ($relatedModelClass) {
                    $relationships[] = [
                        'type' => 'hasMany',
                        'column' => $columnName,
                        'related_table' => $referencingTable,
                        'related_class' => $relatedModelClass,
                        'foreign_key' => $columnName,
                        'local_key' => $referencedColumn
                    ];
                }
            }
        }

        return $relationships;
    }

    /**
     * Find model class for a table name
     * Tries to match table name to model class
     */
    protected static function findModelClass(string $tableName): ?string
    {
        // Check cache first
        $cacheKey = $tableName;
        if (isset(self::$relationshipCache[$cacheKey])) {
            return self::$relationshipCache[$cacheKey];
        }

        // Try common naming patterns
        $patterns = [
            $tableName . 'Model',
            ucfirst($tableName) . 'Model',
            ucfirst(rtrim($tableName, 's')) . 'Model', // users -> UserModel
            ucfirst($tableName) . 'sModel', // user -> UsersModel
        ];

        // Search in modules directory
        $modulesPath = dirname(__DIR__, 2) . '/modules';
        if (is_dir($modulesPath)) {
            $modules = glob($modulesPath . '/*', GLOB_ONLYDIR);
            
            foreach ($modules as $modulePath) {
                $moduleName = basename($modulePath);
                $modelFile = $modulePath . '/Model.php';
                
                if (file_exists($modelFile)) {
                    $className = "App\\Modules\\{$moduleName}\\Model";
                    
                    if (class_exists($className)) {
                        $tempModel = new $className();
                        if ($tempModel->getTable() === $tableName) {
                            self::$relationshipCache[$cacheKey] = $className;
                            return $className;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Generate relationship methods from auto-detected relationships
     * This can be called to generate relationship code
     */
    public static function generateRelationshipMethods(): string
    {
        $relationships = static::autoDetectRelationships();
        $methods = [];

        foreach ($relationships as $rel) {
            $methodName = static::generateMethodName($rel['related_table'], $rel['type']);
            $relatedClass = $rel['related_class'];
            
            if ($rel['type'] === 'belongsTo') {
                $methods[] = "    public function {$methodName}()\n    {\n        return \$this->belongsTo({$relatedClass}::class, '{$rel['foreign_key']}');\n    }";
            } elseif ($rel['type'] === 'hasMany') {
                $methods[] = "    public function {$methodName}()\n    {\n        return \$this->hasMany({$relatedClass}::class, '{$rel['foreign_key']}');\n    }";
            }
        }

        return implode("\n\n", $methods);
    }

    /**
     * Generate method name from table name and relationship type
     */
    protected static function generateMethodName(string $tableName, string $type): string
    {
        // Convert table name to method name
        $name = rtrim($tableName, 's'); // Remove plural
        $name = str_replace('_', '', ucwords($name, '_'));
        $name = lcfirst($name);
        
        return $name;
    }
}
