<?php

namespace App\Services;

use Illuminate\Support\Str;

class LaravelGenerator
{
    protected array $schema;
    protected string $resourceName;
    protected string $modelName;
    protected string $tableName;

    public function __construct(array $schema)
    {
        $this->schema = $schema;
        $this->resourceName = $schema['resource_name'] ?? 'Resource';
        $this->modelName = Str::studly(Str::singular($this->resourceName));
        $this->tableName = Str::snake(Str::plural($this->resourceName));
    }

    public function generate(): array
    {
        return [
            'Migration' => $this->generateMigration(),
            'Model' => $this->generateModel(),
            'Controller' => $this->generateController(),
            'Routes' => $this->generateRoutes(),
        ];
    }

    protected function generateMigration(): string
    {
        $fields = "";
        foreach ($this->schema['fields'] as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'string';
            $nullable = ($field['is_nullable'] ?? false) ? '->nullable()' : '';
            
            if (($field['is_primary'] ?? false) && $name === 'id') {
                if ($type === 'uuid') {
                    $fields .= "            \$table->uuid('id')->primary();\n";
                } else {
                    $fields .= "            \$table->id();\n";
                }
                continue;
            }

            $fields .= "            \$table->{$type}('{$name}'){$nullable};\n";
        }

        $className = "Create" . Str::studly($this->tableName) . "Table";

        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$this->tableName}', function (Blueprint \$table) {
{$fields}            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$this->tableName}');
    }
};
PHP;
    }

    protected function generateModel(): string
    {
        $fillableArray = [];
        $hasUuid = false;

        foreach ($this->schema['fields'] as $field) {
            if ($field['name'] !== 'id') {
                $fillableArray[] = "'" . $field['name'] . "'";
            } else if (($field['type'] ?? '') === 'uuid') {
                $hasUuid = true;
            }
        }

        $fillable = implode(",\n        ", $fillableArray);
        
        $uuidImport = $hasUuid ? "use Illuminate\Database\Eloquent\Concerns\HasUuids;\n" : "";
        $uuidTrait = $hasUuid ? ", HasUuids" : "";

        return <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
{$uuidImport}
class {$this->modelName} extends Model
{
    use HasFactory{$uuidTrait};

    protected \$fillable = [
        {$fillable}
    ];
}
PHP;
    }

    protected function generateController(): string
    {
        $controllerName = $this->modelName . 'Controller';
        $variableName = Str::camel($this->modelName);
        $variableNamePlural = Str::plural($variableName);

        $validationRules = [];
        foreach ($this->schema['fields'] as $field) {
            if ($field['name'] === 'id') continue;
            
            $rules = [];
            $rules[] = ($field['is_nullable'] ?? false) ? 'nullable' : 'required';
            
            if ($field['type'] === 'string' || $field['type'] === 'text') $rules[] = 'string';
            if ($field['type'] === 'integer') $rules[] = 'integer';
            if ($field['type'] === 'boolean') $rules[] = 'boolean';
            
            $validationRules[] = "'{$field['name']}' => '" . implode('|', $rules) . "'";
        }
        $validationString = implode(",\n            ", $validationRules);

        return <<<PHP
<?php

namespace App\Http\Controllers;

use App\Models\\{$this->modelName};
use Illuminate\Http\Request;

class {$controllerName} extends Controller
{
    public function index()
    {
        \${$variableNamePlural} = {$this->modelName}::all();
        return response()->json(\${$variableNamePlural});
    }

    public function store(Request \$request)
    {
        \$validated = \$request->validate([
            {$validationString}
        ]);

        \${$variableName} = {$this->modelName}::create(\$validated);
        return response()->json(\${$variableName}, 201);
    }

    public function show(\$id)
    {
        \${$variableName} = {$this->modelName}::findOrFail(\$id);
        return response()->json(\${$variableName});
    }

    public function update(Request \$request, \$id)
    {
        \${$variableName} = {$this->modelName}::findOrFail(\$id);

        \$validated = \$request->validate([
            {$validationString}
        ]);

        \${$variableName}->update(\$validated);
        return response()->json(\${$variableName});
    }

    public function destroy(\$id)
    {
        \${$variableName} = {$this->modelName}::findOrFail(\$id);
        \${$variableName}->delete();
        return response()->json(null, 204);
    }
}
PHP;
    }

    protected function generateRoutes(): string
    {
        $controllerName = $this->modelName . 'Controller';
        $routeName = Str::kebab(Str::plural($this->modelName));

        return <<<PHP
use App\Http\Controllers\\{$controllerName};
use Illuminate\Support\Facades\Route;

Route::apiResource('{$routeName}', {$controllerName}::class);
PHP;
    }
}
