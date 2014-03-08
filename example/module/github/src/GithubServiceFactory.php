<?php
namespace AlanPich\Slender\Github;

use Github\HttpClient\CachedHttpClient;
use Github;
use Slender\Interfaces\FactoryInterface;

class GithubServiceFactory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $client = new Github\Client(
            new CachedHttpClient(array('cache_dir' => '/tmp/github-api-cache'))
        );

        return $client;
    }
}
