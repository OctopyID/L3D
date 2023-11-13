<?php

namespace Octopy\L3D\Console\Commands;

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
     * @return mixed
     */
    public function handle() : mixed
    {
        $location = Domain::path($this->getNameInput());

        if ($this->files->isDirectory($location) && (! $this->hasOption('force') || ! $this->option('force'))) {
            return $this->components->error($this->type . ' already exists.');
        }

        $this->files->makeDirectory($location, 0777, true, true);
        $this->components->info(sprintf(
            '%s [%s] created successfully.', $this->type, $this->getNameInput()
        ));
    }

    /**
     * @return void
     */
    protected function getStub()
    {
        // TODO: Implement getStub() method.
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
