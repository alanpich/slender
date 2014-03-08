<?php
/**
 * Slim - a micro PHP 5 framework
 *
 * @author      Josh Lockhart
 * @author      Andrew Smith
 * @link        http://www.slimframework.com
 * @copyright   2013 Josh Lockhart
 * @version     0.1.0
 * @package     SlimViews
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Slender\Module\Twig;

use Slender\Core\DependencyInjector\Annotation as Slender;
use Slender\Module\Twig\Exception\MissingDependencyException;

/**
 * Twig view
 *
 * The Twig view is a custom View class that renders templates using the Twig
 * template language (http://www.twig-project.org/).
 *
 * Two fields that you, the developer, will need to change are:
 * - parserDirectory
 * - parserOptions
 */
class View extends \Slender\Core\View
{

    /**
     * @var \Twig_Environment The Twig environment for rendering templates.
     * @Slender\Inject("twig")
     */
    private $parserInstance = null;

    /**
     * Render Twig Template
     *
     * This method will output the rendered template content
     *
     * @param  string $template The path to the Twig template, relative to the Twig templates directory.
     * @param  array  $data
     * @return void
     */
    public function render($template, array $data = array())
    {
        // Append .twig to end of path
        if (substr($template, -5) != '.twig') {
            $template .= '.twig';
        }
        $env = $this->getInstance();
        $parser = $env->loadTemplate($template);
        $this->replace($data);

        return $parser->render($this->all(), $data);
    }

    /**
     * DEPRECATION WARNING! This method will be removed in the next major point release
     *
     * Use getInstance method instead
     *
     * @deprecated
     */
    public function getEnvironment()
    {
        return $this->getInstance();
    }

    /**
     * Returns the Twig environment View is sitting on,
     * or throws a RuntimeException if parser hasnt been
     * set
     *
     * @throws \RuntimeException
     * @return \Twig_Environment
     */
    public function getInstance()
    {
        // Parser is injected please!
        if (!$this->parserInstance) {
            throw new MissingDependencyException("No parser instance available in " . __CLASS__);
        }
        return $this->parserInstance;
    }

    /**
     * Allow injecting the Twig_Environment instance
     *
     * @param \Twig_Environment $parserInstance
     */
    public function setParserInstance($parserInstance)
    {
        $this->parserInstance = $parserInstance;
    }


}
