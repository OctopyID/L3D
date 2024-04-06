<?php

namespace Octopy\L3D\Console\Commands;

use Exception;
use Illuminate\Console\GeneratorCommand;
use Octopy\L3D\Support\Facades\Domain;
use Symfony\Component\Console\Input\InputOption;

class DomainMakeCommand extends GeneratorCommand
{
    protected $name = 'make:domain';

    /**
     * @var string
     */
    protected $description = 'Create a new Domain';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Domain';

    /**
     * @throws Exception
     */
    public function handle()
    {
        $location = Domain::path($this->getNameInput());

        if ($this->files->isDirectory($location) && (! $this->hasOption('force') || ! $this->option('force'))) {
            $this->components->error($this->type . ' already exists.');
            exit;
        }

        $this
            ->createDirectories($location)
            ->createFiles($location);

        $this->components->info(sprintf(
            '%s [%s] created successfully.', $this->type, $this->getNameInput()
        ));
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub()
    {
        //
    }

    /**
     * @return array[]
     */
    protected function getOptions() : array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
        ];
    }

    /**
     * @param  string $location
     * @return $this
     */
    private function createDirectories(string $location) : self
    {
        $paths = [
            'Models',
            'Providers',
            'Http/Middleware',
            'Http/Controllers',
            'Database/Migrations',
            'Database/Factories',
            'Database/Seeders',
        ];

        foreach ($paths as $path) {
            $this->files->makeDirectory(sprintf('%s/%s', $location, $path), 0755, true, true);
        }

        return $this;
    }

    /**
     * @param  string $location
     * @return void
     */
    private function createFiles(string $location) : void
    {
        $files = [
            'web.php',
            'api.php',
        ];

        $content = <<<EOT
            <?php
            
            use Illuminate\Support\Facades\Route;
            
            EOT;

        foreach ($files as $file) {
            $this->files->put(sprintf('%s/%s', $location, $file), $content);
        }
    }
}
