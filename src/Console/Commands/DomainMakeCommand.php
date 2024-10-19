<?php

namespace Octopy\L3D\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use function Octopy\L3D\domain_path;

class DomainMakeCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $name = 'make:domain';

    /**
     * @var string
     */
    protected $description = 'Create a new Domain';

    /**
     * @var string
     */
    protected $type = 'Domain';

    /**
     * @throws Exception
     */
    public function handle() : void
    {
        $location = domain_path($this->getNameInput());

        if ($this->files->isDirectory($location) && (! $this->hasOption('force') || ! $this->option('force'))) {
            $this->components->error('Domain already exists.');
            exit;
        }

        if ($this->files->makeDirectory($location, 0755, true)) {
            $this->components->info(sprintf(
                'Domain [%s] created successfully.', $this->getNameInput()
            ));
        } else {
            $this->components->error(sprintf(
                'Failed to create domain [%s].', $this->getNameInput()
            ));
        }
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
}
