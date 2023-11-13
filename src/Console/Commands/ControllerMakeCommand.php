<?php

namespace Octopy\L3D\Console\Commands;

use Exception;
use Illuminate\Support\Str;
use Octopy\L3D\Support\Facades\Domain;
use Symfony\Component\Console\Input\InputOption;
use function Laravel\Prompts\select;

class ControllerMakeCommand extends \Illuminate\Routing\Console\ControllerMakeCommand
{
    protected $name = 'make:controller';

    /**
     * @var string|null
     */
    private string|null $domain;

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->domain = $this->option('domain');

        if (! $this->domain) {
            $this->domain = select('Which domain would you like to use?', Domain::getDomainNames());
        }

        if (! is_dir(domain_path($this->domain))) {
            return $this->components->error(sprintf('Domain [%s] does not exists.', $this->domain));
        }

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

    /**
     * @return array
     */
    protected function getOptions() : array
    {
        return array_merge(parent::getOptions(), [
            ['domain', 'd', InputOption::VALUE_REQUIRED, 'Manually specify the domain to use'],
        ]);
    }
}
