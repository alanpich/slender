<?php
namespace Slender\Website\Docs;

use Github\Client;
use Github\HttpClient\Message\ResponseMediator;
use Slender\Core\DependencyInjector\Annotation as Slender;
use Slender\Core\Util\Util;

class FileTree
{

    /**
     * @var Client
     * @Slender\Inject
     */
    public $github;


    public function getTree()
    {
        $response = $this->github->getHttpClient()->get('repos/alanpich/slender/git/trees/gh-pages?recursive=1');
        $response = ResponseMediator::getContent($response);

        $files = [];

        foreach ($response['tree'] as $blob) {
            if (Util::stringStartsWith($blob['path'], '_content/')) {
                $path = str_replace('_content/', '', $blob['path']);
                if (preg_match("/\\.md/", $path) === 1) {
                    $blob['path'] = str_replace('_content/','',$blob['path']);
                    $files[$path] = $blob;
                }
            }
        }


        $tree = [];

        foreach ($files as $file) {
            // explode into path bits
            $bits = explode('/', $file['path']);
            $filename = array_pop($bits);

            $here = & $tree;
            while ($d = array_shift($bits)) {
                if (!isset($here[$d])) {
                    $here[$d] = array();
                }
                $here = & $here[$d];
            }

            $here[$filename] = $file;
        }

        return $tree;

    }


} 
