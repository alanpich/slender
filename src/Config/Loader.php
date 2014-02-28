<?php
namespace Slender\Config;

use Slender\Config\Parser\ParserInterface;
use Slender\Config\Parser\YML;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Loader implements \ArrayAccess
{

    protected $config = array();

    protected $parsers = array();

    function __construct()
    {
        $this->addParser('yml', new YML());
    }

    public function toArray()
    {
        return $this->config;
    }

    public function addParser($ext, ParserInterface $parser)
    {
        $this->parsers[$ext] = $parser;
    }

    /**
     * @param      $path
     * @param bool $returnValue if true will return data instead of merging it
     * @return mixed
     */
    public function loadFile($path, $returnValue = false)
    {
        // Choose parser based on file extension
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $parser = $this->parsers[$ext];
        $data = $parser->parse(file_get_contents($path));
        if ($returnValue) {
            return $data;
        } else {
            $this->merge($data);
        }
    }


    public function merge($data)
    {
        $this->config = array_merge_recursive($this->config, $data);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }
}