<?php

namespace Octopy\L3D\Console\Commands;

use Exception;
use Illuminate\Support\Str;
use Octopy\L3D\Support\Facades\Domain;
use function Laravel\Prompts\select;

class ControllerMakeCommand extends \Illuminate\Routing\Console\ControllerMakeCommand
{
    protected $name = 'make:controller';

    /**
     * @var string
     */
    private string $domain;

    /**
     * @throws Exception
     */
    public function handle() : void
    {
        $this->domain = select('Which domain would you like to use?', Domain::getDomainNames());

        parent::handle();
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getPath($name) : string
    {
        return $this->laravel['path'] . '/Domain/' . $this->domain . '/' . str_replace('\\', '/', Str::replaceFirst($this->rootNamespace(), '', $name)) . '.php';
    }

    /**
     * @return string
     */
    protected function rootNamespace() : string
    {
        return parent::rootNamespace() . 'Domain\\' . $this->domain;
    }
}
