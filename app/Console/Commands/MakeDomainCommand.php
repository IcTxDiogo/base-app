<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDomainCommand extends Command
{
    protected $signature = 'make:domain {name}';

    protected $description = 'Cria toda a estrutura base (model, service, dto, testes) para um novo domínio';

    public function handle()
    {
        $name = $this->argument('name');
        $model = Str::studly($name);

        $this->comment("A iniciar a criação da estrutura completa para o domínio: {$model}");

        $this->createApplicationStructure($model);
        $this->createTestStructure($model);

        $this->info("✅ Estrutura de domínio para '{$model}' criada com sucesso!");
    }

    protected function createApplicationStructure(string $model): void
    {
        $this->line("\n-> A criar a estrutura da aplicação...");

        $this->call('make:model', [
            'name' => $model,
            '--all' => true,
        ]);

        $this->createFileWithDirectory(app_path("Services/{$model}Service.php"), $this->getServiceTemplate($model));
        $this->createFileWithDirectory(app_path("Data/{$model}Data.php"), $this->getDTOTemplate($model));
    }

    protected function createTestStructure(string $model): void
    {
        $this->line("\n-> A criar a estrutura de testes (Pest)...");

        $baseDir = "tests/Domains/{$model}/Feature";

        $this->createFileWithDirectory(base_path("{$baseDir}/{$model}Test.php"), $this->getFeatureTestTemplate($model, "{$model}Test"));
        $this->createFileWithDirectory(base_path("{$baseDir}/{$model}ServiceTest.php"), $this->getFeatureTestTemplate($model, "{$model}ServiceTest"));
        $this->createFileWithDirectory(base_path("{$baseDir}/Http/Controllers/{$model}ControllerTest.php"), $this->getFeatureTestTemplate($model, "Http\\Controllers\\{$model}ControllerTest"));
    }

    protected function createFileWithDirectory(string $path, string $content): void
    {
        $directory = dirname($path);

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        if (!File::exists($path)) {
            File::put($path, $content);
            $this->line("   ✓ Ficheiro criado: ".Str::after($path, base_path().'/'));
        }
    }

    protected function getServiceTemplate(string $model): string
    {
        return <<<PHP
<?php

namespace App\Services;

use App\Data\\{$model}Data;
use App\Models\\{$model};

class {$model}Service
{
    public function create({$model}Data \$dto): {$model}
    {
        // 1. Cria a instância do modelo em memória.
        \$instance = new {$model}(\$dto->getFillableData());

        // TODO: Implementar a lógica de associação para este modelo.
        // Exemplo:
        // \$instance->user()->associate(\$dto->user);

        \$instance->save();

        return \$instance;
    }

    public function update({$model} \$instance, {$model}Data \$dto): {$model}
    {
        \$instance->update(\$dto->getFillableData());

        // TODO: Implementar a lógica de atualização de relacionamentos.

        return \$instance;
    }

    public function delete({$model} \$instance): ?bool
    {
        return \$instance->delete();
    }
}
PHP;
    }

    protected function getDTOTemplate(string $model): string
    {
        return <<<PHP
<?php

namespace App\Data;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class {$model}Data
{
    public function __construct(
        // --- Propriedades para os campos da tabela ---
        //public readonly string \$title,
        //public readonly ?string \$content,

        // --- Propriedades para os relacionamentos ---
        // É uma boa prática passar o objeto completo do utilizador autenticado.
        //public readonly User \$user,

        // Para outros relacionamentos, podemos passar o ID ou o objeto.
        // Exemplo:
        // public readonly ?int \$categoryId,
    ) {
    }

    public static function fromRequest(FormRequest \$request): self
    {
        return new self(
            // TODO: Mapeie os dados validados do request para as propriedades do DTO.
        );
    }

    public function getFillableData(): array
    {
        // TODO: Retorne um array com os dados que correspondem às colunas da sua tabela.
        return [];
    }
}
PHP;
    }

    private function getFeatureTestTemplate(string $model, string $class): string
    {
        $namespace = "Tests\\Domains\\{$model}\\Feature";
        if (str_contains($class, '\\')) {
            $parts = explode('\\', $class);
            $className = array_pop($parts);
            $namespace .= '\\' . implode('\\', $parts);
        } else {
            $className = $class;
        }

        return <<<PHP
<?php

namespace {$namespace};

test('exemplo de teste de funcionalidade para {$className}', function () {
    expect(true)->toBeTrue();
});
PHP;
    }
}
