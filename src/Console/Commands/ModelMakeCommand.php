<?php

namespace Octopy\L3D\Console\Commands;

use Octopy\L3D\Console\Commands\Concerns\HasDomain;
use Octopy\L3D\Console\Commands\Concerns\HasGetPath;
use Octopy\L3D\Support\Facades\Domain;
use function Laravel\Prompts\select;

class ModelMakeCommand extends \Illuminate\Foundation\Console\ModelMakeCommand
{
    use HasGetPath, HasDomain;

    protected $name = 'make:model';

    /**
     * @var string|null
     */
    protected string|null $domain;

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
     * @return string
     */
    protected function rootNamespace() : string
    {
        return sprintf('%sDomain\%s\Models', parent::rootNamespace(), $this->domain);
    }
}
