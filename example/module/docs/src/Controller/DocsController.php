<?php
namespace Slender\Website\Docs\Controller;

use Github\Client;
use Slender\Core\DependencyInjector\Annotation\Inject;
use Slender\Core\DependencyInjector\Annotation as Slender;
use Slender\Core\Util\Util;
use Slender\Module\RouteManager\Controller\AbstractController;
use \Michelf\MarkdownExtra;
use Slender\Website\Docs\FileTree;

class DocsController extends AbstractController
{

    /**
     * @var FileTree
     * @Slender\Inject("docs.filetree")
     */
    public $docsTree;



    public function index()
    {
        $this->render('home',array());
    }


    public function getPage()
    {

        $base = '/docs/';
        $path = str_replace($base,'',$this->request->getPath());
        $path = Util::ensureStringEndsWith($path,'.md');

//        dump($this->docsTree->getTree());
        $this->render('docs/page',array(
                'tree' => $this->docsTree->getTree()
            ));

//        // Grab the markdown
//        $base = '/docs/';
//        $path = str_replace($base,'',$this->request->getPath());
//        $path = Util::ensureStringEndsWith($path,'.md');
//
//        $fileToFetch = "https://raw.github.com/alanpich/slender/gh-pages/".$path;
//
//        $markdown = file_get_contents($fileToFetch);
//
//
//        $parser = new MarkdownExtra();
//        $parser->fn_id_prefix = "post22-";
//        $html = $parser->transform($markdown);
//
//        $this->render('docs/page',array(
//                'content' => $html
//            ));
    }

} 
