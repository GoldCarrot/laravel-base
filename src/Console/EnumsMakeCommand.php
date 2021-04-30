<?php

namespace Goldcarrot\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class EnumsMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:enums';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new enums class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Enums';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->option('status')
            ? $this->resolveStubPath('/stubs/enums.status.stub')
            : $this->resolveStubPath('/stubs/enums.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['status', 's', InputOption::VALUE_NONE, 'Create status enums.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the presenter already exists'],
        ];
    }
}
