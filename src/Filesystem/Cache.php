<?php

namespace Octopy\L3D\Filesystem;

use Octopy\L3D\Domain;
use Octopy\L3D\Exceptions\L3DCacheException;

class Cache
{
    protected string $path;

    /**
     * Cache constructor.
     */
    public function __construct()
    {
        $this->path = base_path('bootstrap/cache/l3d.php');
    }

    /**
     * @return bool
     */
    public function exists() : bool
    {
        return file_exists($this->path);
    }

    /**
     * @return array
     * @throws L3DCacheException
     */
    public function get() : array
    {
        if (! $this->exists()) {
            throw new L3DCacheException(
                'L3D cache not found.',
            );
        }

        return once(function () {
            return array_map(static fn(array $row) => new Domain()->fromArray($row), require $this->path);
        });
    }

    /**
     * @param  array $domains
     * @return false|int
     * @throws L3DCacheException
     */
    public function put(array $domains) : false|int
    {
        $data = [];

        foreach ($domains as $domain) {
            $data[$domain->namespace] = $domain->toArray();
        }

        if (! is_dir(base_path('bootstrap/cache'))) {
            $created = mkdir(base_path('bootstrap/cache'), 0777, true);

            if (! $created) {
                throw new L3DCacheException(
                    sprintf('Can\'t make cache directory: %s', $this->path),
                );
            }
        }

        return
            file_put_contents($this->path, '<?php return ' . var_export($data, true) . ';');
    }

    /**
     * @return bool
     */
    public function clear() : bool
    {
        if ($this->exists()) {
            return unlink($this->path);
        }

        return true;
    }
}
