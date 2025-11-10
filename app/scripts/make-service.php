<?php
/**
 * Service Generator (Improved)
 * Creates a service using ActiveRecord methods
 * File: Service.php
 */

if ($argc < 3) {
    echo "\n";
    echo "Error: Module name and Service name are required\n";
    echo "\n";
    echo "Usage: php make-service.php <ModuleName> <ServiceName> [ModelName]\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php make-service.php Product ProductService\n";
    echo "  php make-service.php User UserService\n";
    echo "\n";
    exit(1);
}

$moduleName = trim($argv[1]);
$serviceName = trim($argv[2]);
$modelName = isset($argv[3]) && !empty(trim($argv[3])) ? trim($argv[3]) : 'Model';

if (empty($moduleName) || empty($serviceName)) {
    echo "\n";
    echo "Error: Module name and Service name cannot be empty\n";
    echo "\n";
    exit(1);
}

// Validate names
if (!preg_match('/^[a-zA-Z0-9_]+$/', $moduleName) || !preg_match('/^[a-zA-Z0-9_]+$/', $serviceName)) {
    echo "\n";
    echo "Error: Names can only contain letters, numbers, and underscores\n";
    echo "\n";
    exit(1);
}

// Convert to PascalCase
$moduleName = str_replace('_', ' ', $moduleName);
$moduleName = ucwords(strtolower($moduleName));
$moduleName = str_replace(' ', '', $moduleName);
$moduleName = ucfirst($moduleName);

$serviceName = str_replace('_', ' ', $serviceName);
$serviceName = ucwords(strtolower($serviceName));
$serviceName = str_replace(' ', '', $serviceName);
$serviceName = ucfirst($serviceName);

$modelName = str_replace('_', ' ', $modelName);
$modelName = ucwords(strtolower($modelName));
$modelName = str_replace(' ', '', $modelName);
$modelName = ucfirst($modelName);

$modulePath = __DIR__ . '/../../modules/' . $moduleName;

if (!is_dir($modulePath)) {
    echo "\n";
    echo "Error: Module '{$moduleName}' does not exist.\n";
    echo "\n";
    echo "Create it first using:\n";
    echo "  apimake module {$moduleName}\n";
    echo "\n";
    exit(1);
}

$servicePath = $modulePath . '/Service.php';

if (file_exists($servicePath)) {
    echo "\n";
    echo "Error: Service already exists: {$servicePath}\n";
    echo "\n";
    exit(1);
}

$serviceContent = <<<PHP
<?php

namespace App\\Modules\\{$moduleName};

use App\\Core\\Service as BaseService;
use App\\Modules\\{$moduleName}\\Model;

class Service extends BaseService
{
    /**
     * Get all {$serviceName} records
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
     * Get {$serviceName} by ID
     * Uses ActiveRecord: Model::find()
     */
    public function getById(int \$id): ?array
    {
        \$model = Model::find(\$id);
        return \$model ? \$model->toArray() : null;
    }

    /**
     * Create {$serviceName}
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
            'message' => 'Failed to create {$serviceName}'
        ];
    }

    /**
     * Update {$serviceName}
     * Uses ActiveRecord: \$model->save()
     */
    public function update(int \$id, array \$data): array
    {
        \$model = Model::find(\$id);
        
        if (!\$model) {
            return [
                'success' => false,
                'message' => '{$serviceName} not found'
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
            'message' => 'Failed to update {$serviceName}'
        ];
    }

    /**
     * Delete {$serviceName}
     * Uses ActiveRecord: \$model->delete()
     */
    public function delete(int \$id): array
    {
        \$model = Model::find(\$id);
        
        if (!\$model) {
            return [
                'success' => false,
                'message' => '{$serviceName} not found'
            ];
        }

        if (\$model->delete()) {
            return ['success' => true];
        }

        return [
            'success' => false,
            'message' => 'Failed to delete {$serviceName}'
        ];
    }
}
PHP;

file_put_contents($servicePath, $serviceContent);

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "✅ SERVICE GENERATED SUCCESSFULLY!\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "📦 Module: {$moduleName}\n";
echo "📄 File: Service.php\n";
echo "📁 Location: {$servicePath}\n";
echo "📊 Uses Model: {$modelName}\n";
echo "\n💡 Note: Service uses ActiveRecord methods (Model::all(), Model::find(), etc.)\n";
