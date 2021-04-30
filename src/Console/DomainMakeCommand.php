<?php

namespace GoldcarrotLaravel\Base\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DomainMakeCommand extends Command
{
    protected $name = 'make:domain';

    protected $description = 'Create a model, presenter, repository, service and validator classes';

    protected Filesystem $files;

    protected array $reservedNames = [
        '__halt_compiler',
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'eval',
        'exit',
        'extends',
        'final',
        'finally',
        'fn',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
    ];

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle(): bool
    {
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');
            return false;
        }

        $namespace = $this->qualifyNamespace($this->getNameInput());
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error("$namespace already exists!");

            return false;
        }

        $this->createModel($namespace);
        $this->createEnums($namespace);
        $this->createRepository($namespace);
        $this->createPresenter($namespace);
        $this->createValidator($namespace);
        $this->createService($namespace);

        $this->info('Domain successfully created!');
        return true;
    }

    protected function getModelClass($namespace): array|string
    {
        $defaultClass = "$namespace\\Entities\\{$this->parseDomainName($namespace)}";

        return $this->argument('model') ?: $defaultClass;
    }

    protected function createModel($namespace): int
    {
        $arguments = array_filter([
            'name' => $this->getModelClass($namespace),
            '--force' => $this->option('force'),
        ]);

        return $this->call('make:model', $arguments);
    }

    protected function getEnumsClass($namespace): array|string
    {
        $defaultClass = "$namespace\\Enums\\{$this->parseDomainName($namespace)}StatusEnums";

        return $this->argument('enums') ?: $defaultClass;
    }

    protected function createEnums($namespace): int
    {
        $arguments = array_filter([
            'name' => $this->getEnumsClass($namespace),
            '--force' => $this->option('force'),
            '--status' => true,
        ]);

        return $this->call('make:enums', $arguments);
    }

    protected function getPresenterClass($namespace): array|string
    {
        $defaultClass = "$namespace\\Presenters\\{$this->parseDomainName($namespace)}Presenter";

        return $this->argument('presenter') ?: $defaultClass;
    }

    protected function createPresenter($namespace): int
    {
        $arguments = array_filter([
            'name' => $this->getPresenterClass($namespace),
            '--model' => $this->getModelClass($namespace),
            '--force' => $this->option('force'),
        ]);

        return $this->call('make:presenter', $arguments);
    }

    protected function getRepositoryClass($namespace): array|string
    {
        $defaultClass = "$namespace\\Repositories\\{$this->parseDomainName($namespace)}Repository";

        return $this->argument('repository') ?: $defaultClass;
    }

    protected function createRepository($namespace): int
    {
        $arguments = array_filter([
            'name' => $this->getRepositoryClass($namespace),
            '--model' => $this->getModelClass($namespace),
            '--force' => $this->option('force'),
        ]);

        return $this->call('make:repository', $arguments);
    }

    protected function getValidatorClass($namespace): array|string
    {
        $defaultClass = "$namespace\\Validators\\{$this->parseDomainName($namespace)}Validator";

        return $this->argument('validator') ?: $defaultClass;
    }

    protected function createValidator($namespace): int
    {
        $arguments = array_filter([
            'name' => $this->getValidatorClass($namespace),
            '--model' => $this->getModelClass($namespace),
            '--force' => $this->option('force'),
        ]);

        return $this->call('make:validator', $arguments);
    }

    protected function getServiceClass($namespace): array|string
    {
        $defaultClass = "$namespace\\Services\\{$this->parseDomainName($namespace)}Service";

        return $this->argument('service') ?: $defaultClass;
    }

    protected function createService($namespace): int
    {
        $arguments = array_filter([
            'name' => $this->getServiceClass($namespace),
            '--model' => $this->getModelClass($namespace),
            '--force' => $this->option('force'),
        ]);

        return $this->call('make:service', $arguments);
    }

    protected function parseDomainName($namespace)
    {
        return collect(explode('\\', $namespace))->last();
    }

    protected function qualifyNamespace($name): array|string
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyNamespace(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name
        );
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    protected function alreadyExists($rawName): bool
    {
        return $this->files->exists($this->qualifyNamespace($rawName));
    }

    protected function getNamespace($name): string
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    protected function getNameInput(): string
    {
        return trim($this->argument('name'));
    }

    protected function rootNamespace(): string
    {
        return $this->laravel->getNamespace() . 'Domain';
    }

    protected function isReservedName($name): bool
    {
        $name = strtolower($name);

        return in_array($name, $this->reservedNames);
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of domain entity'],
            ['model', InputArgument::OPTIONAL, 'The name of model class'],
            ['enums', InputArgument::OPTIONAL, 'The name of enums class'],
            ['presenter', InputArgument::OPTIONAL, 'The name of presenter class'],
            ['repository', InputArgument::OPTIONAL, 'The name of repository class'],
            ['validator', InputArgument::OPTIONAL, 'The name of validator class'],
            ['service', InputArgument::OPTIONAL, 'The name of service class'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Rewrite existing classes.'],
        ];
    }
}
