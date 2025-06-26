<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDomainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria toda a estrutura base (model, service, dto, testes) para um novo domínio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = Str::studly($name);

        $this->comment("A iniciar a criação da estrutura completa para o domínio: {$model}");

        $this->createApplicationStructure($model);
        $this->createTestStructure($model);

        $this->info("✅ Estrutura de domínio para '{$model}' criada com sucesso!");
    }

    /**
     * Cria a estrutura de ficheiros da aplicação principal.
     */
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

    /**
     * Cria a estrutura de testes organizada por domínio.
     */
    protected function createTestStructure(string $model): void
    {
        $this->line("\n-> A criar a estrutura de testes...");
        $this->createFeatureTests($model);
        $this->createUnitTests($model);
    }

    protected function createFeatureTests(string $model): void
    {
        $baseDir = "tests/Domains/{$model}/Feature";

        $this->createFileWithDirectory(base_path("{$baseDir}/{$model}ServiceTest.php"), $this->getFeatureTestTemplate($model, "{$model}ServiceTest"));
        $this->createFileWithDirectory(base_path("{$baseDir}/{$model}PolicyTest.php"), $this->getFeatureTestTemplate($model, "{$model}PolicyTest"));
        $this->createFileWithDirectory(base_path("{$baseDir}/Http/Controllers/{$model}ControllerTest.php"), $this->getFeatureTestTemplate($model, "Http\\Controllers\\{$model}ControllerTest"));
        $this->createFileWithDirectory(base_path("{$baseDir}/Http/Requests/Store{$model}RequestTest.php"), $this->getFeatureTestTemplate($model, "Http\\Requests\\Store{$model}RequestTest"));
        $this->createFileWithDirectory(base_path("{$baseDir}/Http/Requests/Update{$model}RequestTest.php"), $this->getFeatureTestTemplate($model, "Http\\Requests\\Update{$model}RequestTest"));
    }

    protected function createUnitTests(string $model): void
    {
        $baseDir = "tests/Domains/{$model}/Unit";
        $this->createFileWithDirectory(base_path("{$baseDir}/{$model}Test.php"), $this->getUnitTestTemplate($model, "{$model}Test"));
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

class {$model}Service extends BaseService
{
    /**
     * O model principal que o serviço gerencia.
     */
    protected string \$modelClass = {$model}::class;

    /**
     * Cria um novo registo de {$model}.
     */
    public function create({$model}Data \$dto): {$model}
    {
        // TODO: Adicionar lógica de negócio aqui, se necessário.

        // 1. Cria o modelo com os dados preenchíveis.
        \$instance = \$this->modelClass::create(
            \$dto->getFillableData()
        );

        // 2. Associa os relacionamentos.
        // TODO: Implementar a lógica de associação para este modelo.
        // Exemplo:
        // if (\$dto->parentId) {
        //     \$instance->parent()->associate(\$dto->parentId);
        // }
        // \$instance->user()->associate(\$dto->user);
        // \$instance->save();

        return \$instance;
    }

    /**
     * Atualiza um registo existente de {$model}.
     */
    public function update(int \$id, {$model}Data \$dto): ?{$model}
    {
        \$instance = \$this->find(\$id);

        if (!\$instance) {
            return null;
        }

        // TODO: Adicionar lógica de negócio aqui (ex: verificar permissões).

        \$instance->update(
            \$dto->getFillableData()
        );
        
        // TODO: Implementar a lógica de atualização de relacionamentos.

        return \$instance;
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

/**
 * Data Transfer Object para a entidade {$model}.
 * Serve como uma estrutura de dados segura e tipada entre as camadas da aplicação.
 */
class {$model}Data
{
    public function __construct(
        // TODO: Adicione aqui as propriedades públicas e 'readonly' do seu DTO.
        // Exemplo:
        // public readonly string \$name,
        // public readonly ?int \$parentId,
        // public readonly User \$user,
    ) {
    }

    /**
     * Cria uma instância do DTO a partir de um FormRequest validado.
     */
    public static function fromRequest(FormRequest \$request): self
    {
        return new self(
            // TODO: Mapeie os dados validados do request para as propriedades do DTO.
            // Exemplo:
            // name: \$request->validated('name'),
            // parentId: \$request->validated('parent_id'),
            // user: auth()->user(),
        );
    }

    /**
     * Retorna apenas os dados que podem ser preenchidos diretamente em 'create' ou 'update'.
     */
    public function getFillableData(): array
    {
        // TODO: Retorne um array com os dados que correspondem às colunas da sua tabela.
        // A lógica de relacionamento será tratada no Service.
        return [
            // 'name' => \$this->name,
        ];
    }
}
PHP;
    }

    private function getUnitTestTemplate(string $model, string $class)
    {
        $namespace = "Tests\\Domains\\{$model}\\Unit";
        $className = class_basename($class);

        return <<<PHP
<?php

namespace {$namespace};

test('exemplo de teste unitário para {$className}', function () {
    expect(true)->toBeTrue();
});
PHP;
    }

    private function getFeatureTestTemplate(string $model, string $class)
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

use Tests\TestCase;

uses(TestCase::class);

test('exemplo de teste de funcionalidade para {$className}', function () {
    // Ex: \$this->get('/rota');
    expect(true)->toBeTrue();
});
PHP;
    }
}
