<?php

namespace Octopy\L3D\Console\Commands;

use Exception;
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
    public function handle() : void
    {
        $this->domain = $this->option('domain');

        if (! $this->domain) {
            $this->domain = select('Which domain would you like to use?', Domain::getDomainNames());
        }

        if (! is_dir(domain_path($this->domain))) {
            $this->components->error(sprintf('Domain [%s] does not exists.', $this->domain));
            exit;
        }

        parent::handle();
    }

    /**
     * @return string
     */
    protected function rootNamespace() : string
    {
         return sprintf('%s%s\%s\Models', parent::rootNamespace(), str(config('domain.path'))->afterLast('/'), $this->domain);
    }
}
