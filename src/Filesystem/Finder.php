<?php

namespace Octopy\L3D\Filesystem;

use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Contracts\L3DExcludable;
use Octopy\L3D\Domain;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Finder class responsible for discovering and organizing domain-based architecture components.
 *
 * This class scans filesystem directories to identify domains, their models, policies,
 * and service providers, then organizes them into structured Domain objects.
 */
class Finder
{
    /**
     * @param  string $namespace The root namespace for all domains
     * @param  string $location  The filesystem path where domains are located
     */
    public function __construct(protected string $namespace, protected string $location)
    {
        //
    }

    /**
     * Discover all domains in the configured location.
     *
     * Scans the base location for directories at depth 0 (immediate subdirectories),
     * treating each as a separate domain. For each domain, it automatically discovers
     * associated model policies and service providers.
     *
     * @return Domain[] An associative array of Domain objects keyed by namespace
     */
    public function domains() : array
    {
        $domains = [];

        // Find all immediate subdirectories in the base location
        $results = new SymfonyFinder()->in($this->location)->directories()->depth(0);

        foreach ($results as $spl) {
            // Use Laravel's tap helper to create and configure domain in one go
            $domain = tap(new Domain($this->getNamespace($spl), $spl->getRealPath()), function (Domain $domain) {
                // Automatically discover and assign domain components
                $domain->policies = $this->findModelPolicies($domain);
                $domain->providers = $this->findServiceProviders($domain);
            });

            // Index domains by their namespace for easy access
            $domains[$domain->namespace] = $domain;
        }

        return $domains;
    }

    /**
     * Find all model policies for a given domain.
     *
     * Scans the Models directory within a domain and attempts to find corresponding
     * Policy classes. Uses Laravel's naming convention where a Model named "User"
     * would have a corresponding "UserPolicy" in the Policies directory.
     *
     * @param  Domain $domain The domain to scan for model policies
     * @return array<string, string> Array mapping model class names to policy class names
     */
    private function findModelPolicies(Domain $domain) : array
    {
        // Early return if Models directory doesn't exist
        if (! is_dir($domain->basepath('Models'))) {
            return [];
        }

        $policies = [];
        $files = $this->getFilesInDirectory($domain->basepath('Models'));

        foreach ($files as $file) {
            // Convert file path to namespace structure
            $namespace = $this->getFileNamespace($file);

            // Build the full model class name
            $model = $domain->namespace . '\\Models\\' . $namespace . $file->getBasename('.php');

            // Convert model class name to expected policy class name
            // Example: App\Domain\User\Models\User -> App\Domain\User\Policies\UserPolicy
            $policy = str_replace('Models', 'Policies', $model) . 'Policy';

            // Only include policies that actually exist as classes
            if (class_exists($policy)) {
                $policies[$model] = $policy;
            }
        }

        return $policies;
    }

    /**
     * Find all service providers for a given domain.
     *
     * Scans the Providers directory within a domain for classes that extend
     * Laravel's ServiceProvider and are not marked as excludable via L3DExcludable interface.
     *
     * @param  Domain $domain The domain to scan for service providers
     * @return array<string> Array of service provider class names
     */
    private function findServiceProviders(Domain $domain) : array
    {
        // Early return if Providers directory doesn't exist
        if (! is_dir($domain->basepath('Providers'))) {
            return [];
        }

        $providers = [];
        $files = $this->getFilesInDirectory($domain->basepath('Providers'));

        foreach ($files as $file) {
            // Convert file path to namespace structure
            $namespace = $this->getFileNamespace($file);

            // Build the full provider class name
            $provider = $domain->namespace . '\\Providers\\' . $namespace . $file->getBasename('.php');

            // Include only valid service providers that are not explicitly excluded
            if (is_subclass_of($provider, ServiceProvider::class) &&
                ! is_subclass_of($provider, L3DExcludable::class)) {
                $providers[] = $provider;
            }
        }

        return $providers;
    }

    /**
     * Get all PHP files in a specific directory using Symfony Finder.
     *
     * This is a helper method to avoid repeating the Finder instantiation
     * and configuration across multiple methods.
     *
     * @param  string $directory The directory path to scan
     * @return SymfonyFinder Iterator of SplFileInfo objects for found files
     */
    private function getFilesInDirectory(string $directory) : SymfonyFinder
    {
        return new SymfonyFinder()->in($directory)->files();
    }

    /**
     * Convert a file's relative path to a PHP namespace segment.
     *
     * Takes the relative path of a file within a directory structure and converts
     * it to the corresponding namespace format by replacing directory separators
     * with namespace separators and adding trailing backslash if needed.
     *
     * Examples:
     * - "Admin/User.php" -> "Admin\\"
     * - "User.php" -> ""
     *
     * @param  SplFileInfo $file The file to get namespace for
     * @return string The namespace segment with trailing backslash, or empty string
     */
    private function getFileNamespace(SplFileInfo $file) : string
    {
        $relatives = $file->getRelativePath();

        // Convert directory separators to namespace separators and add trailing backslash
        return $relatives ? str_replace('/', '\\', $relatives) . '\\' : '';
    }

    /**
     * Generate the full namespace for a domain directory.
     *
     *
     * @param  SplFileInfo $spl The directory SplFileInfo object
     * @return string The complete namespace for the domain
     */
    private function getNamespace(SplFileInfo $spl) : string
    {
        return $this->namespace . $spl->getBasename();
    }
}
