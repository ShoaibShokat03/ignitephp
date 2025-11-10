<?php
/**
 * Module Generator (Improved)
 * Creates a complete module with ActiveRecord support
 * Files: Module.php, Controller.php, Service.php, Model.php
 */

if ($argc < 2) {
    echo "\n";
    echo "Error: Module name is required\n";
    echo "\n";
    echo "Usage: php make-module.php <ModuleName> [route-prefix]\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php make-module.php Product\n";
    echo "  php make-module.php Product /products\n";
    echo "  php make-module.php products\n";
    echo "\n";
    exit(1);
}

// Get module name and ensure it's not empty
$moduleName = trim($argv[1]);
if (empty($moduleName)) {
    echo "\n";
    echo "Error: Module name cannot be empty\n";
    echo "\n";
    exit(1);
}

// Validate module name (alphanumeric and underscores only)
if (!preg_match('/^[a-zA-Z0-9_]+$/', $moduleName)) {
    echo "\n";
    echo "Error: Module name can only contain letters, numbers, and underscores\n";
    echo "Invalid module name: $moduleName\n";
    echo "\n";
    exit(1);
}

// Convert to PascalCase (handle snake_case and lowercase)
$moduleName = str_replace('_', ' ', $moduleName);
$moduleName = ucwords(strtolower($moduleName));
$moduleName = str_replace(' ', '', $moduleName);
$moduleName = ucfirst($moduleName);

$routePrefix = isset($argv[2]) && !empty(trim($argv[2])) ? trim($argv[2]) : '/' . strtolower($moduleName);
$modulePath = __DIR__ . '/../../modules/' . $moduleName;

// Ensure modules directory exists
$modulesDir = __DIR__ . '/../../modules';
if (!is_dir($modulesDir)) {
    if (!mkdir($modulesDir, 0777, true)) {
        echo "\n";
        echo "Error: Could not create modules directory: $modulesDir\n";
        echo "\n";
        exit(1);
    }
}

// Create module directory
if (!is_dir($modulePath)) {
    if (!mkdir($modulePath, 0777, true)) {
        echo "\n";
        echo "Error: Could not create module directory: $modulePath\n";
        echo "\n";
        exit(1);
    }
    echo "Created directory: $modulePath\n";
} else {
    echo "\n";
    echo "Error: Module directory already exists: $modulePath\n";
    echo "\n";
    echo "If you want to add files to an existing module, use:\n";
    echo "  apimake controller $moduleName <ControllerName>\n";
    echo "  apimake model $moduleName <ModelName>\n";
    echo "  apimake service $moduleName <ServiceName>\n";
    echo "\n";
    exit(1);
}

// Generate Module.php
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
echo "✅ Created: Module.php\n";

// Generate Controller.php
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
echo "✅ Created: Controller.php\n";

// Generate Service.php (using ActiveRecord)
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
        \$required = ['name']; // Update required fields
        \$errors = [];

        foreach (\$required as \$field) {
            if (empty(\$data[\$field])) {
                \$errors[\$field] = "The {\$field} field is required";
            }
        }

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
     * Uses ActiveRecord: \$model->save()
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
echo "✅ Created: Service.php\n";

// Generate Model.php (with field definitions only, no CRUD)
$tableName = strtolower($moduleName) . 's';
$modelContent = <<<PHP
<?php

namespace App\\Modules\\{$moduleName};

use App\\Core\\Model as BaseModel;

/**
 * {$moduleName} Model
 * 
 * Uses ActiveRecord pattern - no CRUD methods needed!
 * 
 * Usage:
 * - Model::all() - Get all records
 * - Model::where('status', 'active')->all() - Query with conditions
 * - Model::find(1) - Find by ID
 * - Model::create(['name' => 'John']) - Create new record
 * - \$model->save() - Update record
 * - \$model->delete() - Delete record
 * 
 * See ACTIVERECORD_USAGE.md for more examples
 */
class Model extends BaseModel
{
    protected string \$table = '{$tableName}';
    
    // Model fields - add your table fields here
    // Example:
    // public \$id;
    // public \$name;
    // public \$email;
    // public \$created_at;
}
PHP;

file_put_contents($modulePath . '/Model.php', $modelContent);
echo "✅ Created: Model.php\n";

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "✅ MODULE GENERATED SUCCESSFULLY!\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "📦 Module Name: {$moduleName}\n";
echo "📁 Location: {$modulePath}\n";
echo "🔗 Route Prefix: {$routePrefix}\n";
echo "\n📄 Files Generated:\n";
echo "   ✅ Module.php       - Route registration\n";
echo "   ✅ Controller.php   - HTTP request handlers\n";
echo "   ✅ Service.php      - Business logic\n";
echo "   ✅ Model.php        - Database model (ActiveRecord)\n";
echo "\nNext steps:\n";
echo "1. Update the table name in Model.php if needed\n";
echo "2. Add field definitions in Model.php (optional - for IDE autocomplete)\n";
echo "3. Add validation rules in Service.php\n";
echo "4. Customize the controller methods as needed\n";
echo "\n💡 Tip: Use 'apimake usetable <table_name>' to auto-generate from database!\n";
