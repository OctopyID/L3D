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
        $this->path = storage_path('framework/cache/l3d.json');
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

        $data = json_decode(file_get_contents($this->path), true);

        return
            array_map(function (array $row) {
                return new Domain($row['namespace'], $row['realpath']);
            }, $data);
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

        if (! is_dir(storage_path('framework/cache'))) {
            $created = mkdir(storage_path('framework/cache'), 0777, true);

            if (! $created) {
                throw new L3DCacheException(
                    sprintf('Can\'t make cache directory: %s', $this->path),
                );
            }
        }

        return
            file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT));
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
