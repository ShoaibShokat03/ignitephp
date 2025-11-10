<?php
/**
 * Generate Module and Model from Database Table(s)
 * 
 * Usage:
 *   php usetable.php <table_name>     - Generate module/model for one table
 *   php usetable.php all              - Generate modules/models for all tables
 */

require __DIR__ . '/../../vendor/autoload.php';

use App\Database\Db;

// Load environment variables
$envPath = __DIR__ . '/../../config';
if (file_exists($envPath . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable($envPath);
    $dotenv->load();
} elseif (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
}

if ($argc < 2 || trim($argv[1]) !== 'all') {
    echo "Usage: php usetable.php all\n";
    echo "\nThis command generates modules for ALL tables in the database.\n";
    echo "\nExample:\n";
    echo "  php usetable.php all\n";
    echo "  apimake usetable all\n";
    exit(1);
}

// Connect to database
$db = new Db();
$db->connect();

// Only support 'all' command
if (true) {
    // Get all tables
    $result = $db->query("SHOW TABLES");
    $tables = [];
    
    if ($result) {
        $tableKey = "Tables_in_{$db->db_name}";
        while ($row = $result->fetch_assoc()) {
            $tables[] = $row[$tableKey];
        }
    }
    
    if (empty($tables)) {
        echo "No tables found in database.\n";
        exit(1);
    }
    
    echo "\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "🔍 DATABASE TABLE SCAN\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "📊 Found " . count($tables) . " table(s) in database\n";
    echo "🚀 Generating modules for all tables...\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
    $generated = 0;
    $skipped = 0;
    
    foreach ($tables as $table) {
        $moduleName = tableToModuleName($table);
        $modulePath = __DIR__ . '/../../modules/' . $moduleName;
        
        if (is_dir($modulePath)) {
            echo "⚠️  Skipping '{$table}' → Module '{$moduleName}' already exists\n";
            $skipped++;
        } else {
            generateModuleFromTable($db, $table, null);
            $generated++;
        }
    }
    
    echo "\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "✅ BATCH GENERATION COMPLETE!\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "✅ Generated: {$generated} module(s)\n";
    if ($skipped > 0) {
        echo "⚠️  Skipped: {$skipped} module(s) (already exist)\n";
    }
    echo "═══════════════════════════════════════════════════════════\n";
}

/**
 * Generate module and model from database table
 */
function generateModuleFromTable(Db $db, string $tableName, ?string $routePrefix = null)
{
    // Check if table exists
    $result = $db->query("SHOW TABLES LIKE '{$tableName}'");
    if (!$result || $result->num_rows === 0) {
        echo "❌ Table '{$tableName}' does not exist.\n";
        return;
    }
    
    // Get table columns
    $result = $db->query("DESCRIBE `{$tableName}`");
    $columns = [];
    $primaryKey = 'id';
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = [
                'name' => $row['Field'],
                'type' => $row['Type'],
                'null' => $row['Null'],
                'key' => $row['Key'],
                'default' => $row['Default'],
                'extra' => $row['Extra']
            ];
            
            if ($row['Key'] === 'PRI') {
                $primaryKey = $row['Field'];
            }
        }
    }
    
    if (empty($columns)) {
        echo "❌ Table '{$tableName}' has no columns.\n";
        return;
    }
    
    // Generate module name from table name
    $moduleName = tableToModuleName($tableName);
    $modulePath = __DIR__ . '/../../modules/' . $moduleName;
    
    // Check if module already exists
    if (is_dir($modulePath)) {
        echo "⚠️  Module '{$moduleName}' already exists. Skipping...\n";
        return;
    }
    
    // Create module directory
    mkdir($modulePath, 0777, true);
    echo "📁 Created directory: {$modulePath}\n";
    
    // Generate route prefix if not provided
    if ($routePrefix === null) {
        $routePrefix = '/' . strtolower($moduleName);
    }
    
    // Generate Model
    generateModel($modulePath, $moduleName, $tableName, $columns, $primaryKey);
    
    // Generate Service
    generateService($modulePath, $moduleName);
    
    // Generate Controller
    generateController($modulePath, $moduleName);
    
    // Generate Module
    generateModule($modulePath, $moduleName, $routePrefix);
    
    echo "\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "✅ MODULE GENERATED FROM DATABASE TABLE!\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "📦 Module Name: {$moduleName}\n";
    echo "📁 Location: {$modulePath}\n";
    echo "📊 Database Table: {$tableName}\n";
    echo "🔗 Route Prefix: {$routePrefix}\n";
    echo "📝 Fields Detected: " . count($columns) . "\n";
    echo "\n📄 Files Generated:\n";
    echo "   ✅ Module.php       - Route registration\n";
    echo "   ✅ Controller.php   - HTTP request handlers (CRUD)\n";
    echo "   ✅ Service.php      - Business logic (ActiveRecord)\n";
    echo "   ✅ Model.php        - Database model with " . count($columns) . " fields\n";
    echo "\n";
}

/**
 * Convert table name to module name
 */
function tableToModuleName(string $tableName): string
{
    // Remove common prefixes/suffixes
    $name = $tableName;
    $name = preg_replace('/^tbl_/i', '', $name);
    $name = preg_replace('/_tbl$/i', '', $name);
    
    // Convert snake_case to PascalCase
    $parts = explode('_', $name);
    $parts = array_map('ucfirst', $parts);
    $moduleName = implode('', $parts);
    
    // Handle plural to singular (basic)
    if (substr($moduleName, -1) === 's' && strlen($moduleName) > 1) {
        $moduleName = substr($moduleName, 0, -1);
    }
    
    return ucfirst($moduleName);
}

/**
 * Generate Model file with field definitions
 */
function generateModel(string $modulePath, string $moduleName, string $tableName, array $columns, string $primaryKey)
{
    $fields = [];
    $fieldComments = [];
    
    foreach ($columns as $column) {
        $fieldName = $column['name'];
        $fieldType = mapSqlTypeToPhpType($column['type']);
        $nullable = $column['null'] === 'YES' ? 'true' : 'false';
        
        $fields[] = "    public \${$fieldName};";
        
        $comment = "     * @var {$fieldType}";
        if ($nullable === 'true') {
            $comment .= "|null";
        }
        $comment .= " {$fieldName}";
        if ($column['key'] === 'PRI') {
            $comment .= " (Primary Key)";
        }
        if ($column['extra'] === 'auto_increment') {
            $comment .= " (Auto Increment)";
        }
        $fieldComments[] = $comment;
    }
    
    $fieldsStr = implode("\n", $fields);
    $commentsStr = implode("\n", $fieldComments);
    
    $modelContent = <<<PHP
<?php

namespace App\\Modules\\{$moduleName};

use App\\Core\\Model as BaseModel;

/**
 * {$moduleName} Model
 * 
 * Table: {$tableName}
 * Primary Key: {$primaryKey}
 * 
 * Fields:
{$commentsStr}
 */
class Model extends BaseModel
{
    protected string \$table = '{$tableName}';
    
    // Model fields (auto-populated from database)
{$fieldsStr}
    
    /**
     * Get table columns information
     */
    public static function getColumns(): array
    {
        return [
PHP;
    
    foreach ($columns as $column) {
        $modelContent .= "\n            '{$column['name']}' => [\n";
        $modelContent .= "                'type' => '{$column['type']}',\n";
        $modelContent .= "                'null' => " . ($column['null'] === 'YES' ? 'true' : 'false') . ",\n";
        $modelContent .= "                'key' => '{$column['key']}',\n";
        if ($column['default'] !== null) {
            $modelContent .= "                'default' => '{$column['default']}',\n";
        }
        if ($column['extra']) {
            $modelContent .= "                'extra' => '{$column['extra']}',\n";
        }
        $modelContent .= "            ],";
    }
    
    $modelContent .= <<<PHP

        ];
    }
    
    /**
     * Get primary key column name
     */
    public static function getPrimaryKey(): string
    {
        return '{$primaryKey}';
    }
}
PHP;
    
    file_put_contents($modulePath . '/Model.php', $modelContent);
    echo "   ✅ Created: Model.php\n";
}

/**
 * Generate Service file using ActiveRecord
 */
function generateService(string $modulePath, string $moduleName)
{
    $serviceContent = <<<PHP
<?php

namespace App\\Modules\\{$moduleName};

use App\\Core\\Service as BaseService;
use App\\Modules\\{$moduleName}\\Model;

class Service extends BaseService
{
    /**
     * Get all {$moduleName} records
     * Uses ActiveRecord: Model::all()
     */
    public function getAll(): array
    {
        \$models = Model::all();
        return array_map(function(\$model) {
            return \$model->toArray();
        }, \$models);
    }

    /**
     * Get {$moduleName} by ID
     * Uses ActiveRecord: Model::find()
     */
    public function getById(int \$id): ?array
    {
        \$model = Model::find(\$id);
        return \$model ? \$model->toArray() : null;
    }

    /**
     * Create {$moduleName}
     * Uses ActiveRecord: Model::create()
     */
    public function create(array \$data): array
    {
        // Add validation here
        \$errors = [];
        
        // Example validation - customize as needed
        // if (empty(\$data['name'])) {
        //     \$errors['name'] = 'The name field is required';
        // }

        if (!empty(\$errors)) {
            return [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => \$errors
            ];
        }

        \$model = Model::create(\$data);

        if (\$model) {
            return [
                'success' => true,
                'data' => \$model->toArray()
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create {$moduleName}'
        ];
    }

    /**
     * Update {$moduleName}
     * Uses ActiveRecord: \$model->update()
     */
    public function update(int \$id, array \$data): array
    {
        \$model = Model::find(\$id);
        
        if (!\$model) {
            return [
                'success' => false,
                'message' => '{$moduleName} not found'
            ];
        }

        // Update attributes
        foreach (\$data as \$key => \$value) {
            \$model->\$key = \$value;
        }

        if (\$model->save()) {
            return [
                'success' => true,
                'data' => \$model->toArray()
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to update {$moduleName}'
        ];
    }

    /**
     * Delete {$moduleName}
     * Uses ActiveRecord: \$model->delete()
     */
    public function delete(int \$id): array
    {
        \$model = Model::find(\$id);
        
        if (!\$model) {
            return [
                'success' => false,
                'message' => '{$moduleName} not found'
            ];
        }

        if (\$model->delete()) {
            return ['success' => true];
        }

        return [
            'success' => false,
            'message' => 'Failed to delete {$moduleName}'
        ];
    }
}
PHP;
    
    file_put_contents($modulePath . '/Service.php', $serviceContent);
    echo "   ✅ Created: Service.php\n";
}

/**
 * Generate Controller file
 */
function generateController(string $modulePath, string $moduleName)
{
    $controllerContent = <<<PHP
<?php

namespace App\\Modules\\{$moduleName};

use App\\Core\\Controller as BaseController;
use App\\Modules\\{$moduleName}\\Service;

class Controller extends BaseController
{
    protected Service \$service;

    public function __construct()
    {
        \$this->service = new Service();
    }

    /**
     * Get all {$moduleName} records
     */
    public function index(): array
    {
        \$data = \$this->service->getAll();
        return \$this->success(\$data, '{$moduleName} records retrieved successfully');
    }

    /**
     * Get {$moduleName} by ID
     */
    public function show(string \$id): array
    {
        \$data = \$this->service->getById((int)\$id);
        
        if (\$data) {
            return \$this->success(\$data, '{$moduleName} retrieved successfully');
        }

        return \$this->error('{$moduleName} not found', [], 404);
    }

    /**
     * Create new {$moduleName}
     */
    public function store(): array
    {
        \$requestData = \$this->getRequestData();
        
        \$result = \$this->service->create(\$requestData);
        
        if (\$result['success']) {
            return \$this->success(\$result['data'], '{$moduleName} created successfully', 201);
        }

        return \$this->error(\$result['message'], \$result['errors'] ?? [], 400);
    }

    /**
     * Update {$moduleName}
     */
    public function update(string \$id): array
    {
        \$requestData = \$this->getRequestData();
        
        \$result = \$this->service->update((int)\$id, \$requestData);
        
        if (\$result['success']) {
            return \$this->success(\$result['data'], '{$moduleName} updated successfully');
        }

        return \$this->error(\$result['message'], \$result['errors'] ?? [], 400);
    }

    /**
     * Delete {$moduleName}
     */
    public function destroy(string \$id): array
    {
        \$result = \$this->service->delete((int)\$id);
        
        if (\$result['success']) {
            return \$this->success([], '{$moduleName} deleted successfully');
        }

        return \$this->error(\$result['message'], [], 404);
    }
}
PHP;
    
    file_put_contents($modulePath . '/Controller.php', $controllerContent);
    echo "   ✅ Created: Controller.php\n";
}

/**
 * Generate Module file
 */
function generateModule(string $modulePath, string $moduleName, string $routePrefix)
{
    $moduleContent = <<<PHP
<?php

namespace App\\Modules\\{$moduleName};

use App\\Core\\Module as BaseModule;
use App\\Modules\\{$moduleName}\\Controller;
use Ignitephp\\Core\\Route;

/**
 * {$moduleName} Module
 */
class Module extends BaseModule
{
    public function __construct()
    {
        parent::__construct('{$moduleName}', '{$routePrefix}');
    }

    public function registerRoutes(): void
    {
        \$controller = new Controller();

        // Register routes
        Route::get('{$routePrefix}', [\$controller, 'index']);
        Route::get('{$routePrefix}/{id}', [\$controller, 'show']);
        Route::post('{$routePrefix}', [\$controller, 'store']);
        Route::put('{$routePrefix}/{id}', [\$controller, 'update']);
        Route::delete('{$routePrefix}/{id}', [\$controller, 'destroy']);
    }
}
PHP;
    
    file_put_contents($modulePath . '/Module.php', $moduleContent);
    echo "   ✅ Created: Module.php\n";
}

/**
 * Map SQL type to PHP type
 */
function mapSqlTypeToPhpType(string $sqlType): string
{
    $sqlType = strtolower($sqlType);
    
    if (strpos($sqlType, 'int') !== false) {
        return 'int';
    }
    if (strpos($sqlType, 'float') !== false || strpos($sqlType, 'double') !== false || strpos($sqlType, 'decimal') !== false) {
        return 'float';
    }
    if (strpos($sqlType, 'bool') !== false || strpos($sqlType, 'tinyint(1)') !== false) {
        return 'bool';
    }
    if (strpos($sqlType, 'date') !== false || strpos($sqlType, 'time') !== false) {
        return 'string'; // Dates as strings for now
    }
    
    return 'string'; // Default to string
}

