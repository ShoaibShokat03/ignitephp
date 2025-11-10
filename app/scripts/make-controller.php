<?php
/**
 * Controller Generator
 * File: Controller.php
 */

if ($argc < 3) {
    echo "\n";
    echo "Error: Module name and Controller name are required\n";
    echo "\n";
    echo "Usage: php make-controller.php <ModuleName> <ControllerName>\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php make-controller.php Product ProductController\n";
    echo "  php make-controller.php User UserController\n";
    echo "\n";
    exit(1);
}

$moduleName = trim($argv[1]);
$controllerName = trim($argv[2]);

if (empty($moduleName) || empty($controllerName)) {
    echo "\n";
    echo "Error: Module name and Controller name cannot be empty\n";
    echo "\n";
    exit(1);
}

// Validate names
if (!preg_match('/^[a-zA-Z0-9_]+$/', $moduleName) || !preg_match('/^[a-zA-Z0-9_]+$/', $controllerName)) {
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

$controllerName = str_replace('_', ' ', $controllerName);
$controllerName = ucwords(strtolower($controllerName));
$controllerName = str_replace(' ', '', $controllerName);
$controllerName = ucfirst($controllerName);

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

// Use Controller.php as standard filename, but class name uses provided controllerName
$controllerPath = $modulePath . '/Controller.php';

if (file_exists($controllerPath)) {
    echo "\n";
    echo "Error: Controller already exists: {$controllerPath}\n";
    echo "\n";
    exit(1);
}

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

file_put_contents($controllerPath, $controllerContent);

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "✅ CONTROLLER GENERATED SUCCESSFULLY!\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "📦 Module: {$moduleName}\n";
echo "📄 File: Controller.php\n";
echo "📁 Location: {$controllerPath}\n";
echo "\n💡 Note: Controller uses Service class for business logic\n";
