<?php
namespace Slender\Module\AssetExposer;

use Slender\Core\Util\Util;

class AssetExposer
{

    protected $moduleConfig;
    protected $config;

    protected $notFoundCallable;
    protected $returnFileCallable;


    public function serveAsset($module, $relPath)
    {
        $relPath = implode(DIRECTORY_SEPARATOR, $relPath);

        // Look up module
        if (!isset($this->moduleConfig[$module])) {
            // 404 not found
            $cb = $this->notFoundCallable;
            $cb();
            return;
        }

        // Build filepath
        $path = $this->moduleConfig[$module]['path'];


        // Check this module wishes to expose assets
        if (!@isset($this->moduleConfig[$module]['public']['expose'])) {
            // 404 not found - this module does not expose assets
            $cb = $this->notFoundCallable;
            $cb();
            return;
        }

        // Merge config with defaults to be safe
        $assetConf = array_merge(array(
                'dir' => 'public',
                'expose' => ['\.js','\.css']
            ),$this->moduleConfig[$module]['public']);


        // Grab the patterns to match files against
        $patterns = $assetConf['expose'];


        // Check file extension is in allow list
        if (!Util::stringMatchesPattern($relPath,$patterns)) {
            // 404 not found
            $cb = $this->notFoundCallable;
            $cb();
            return;
        }

        $path .= DIRECTORY_SEPARATOR;
        $path .= 'public';
        $path .= DIRECTORY_SEPARATOR;
        $path .= $relPath;

        // Check path exists
        if(!is_readable($path)){
            // 404 Not Found - the file doesnt exist or isnt readable
            $cb = $this->notFoundCallable;
            $cb();
            return;
        }

        // Server file
        $serve = $this->returnFileCallable;
        $serve($path);
    }

    /**
     * @param mixed $moduleConfig
     */
    public function setModuleConfig($moduleConfig)
    {
        $this->moduleConfig = $moduleConfig;
    }

    /**
     * @return mixed
     */
    public function getModuleConfig()
    {
        return $this->moduleConfig;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $notFoundCallable
     */
    public function setNotFoundCallable($notFoundCallable)
    {
        $this->notFoundCallable = $notFoundCallable;
    }

    /**
     * @return mixed
     */
    public function getNotFoundCallable()
    {
        return $this->notFoundCallable;
    }

    /**
     * @param mixed $returnFileCallable
     */
    public function setReturnFileCallable($returnFileCallable)
    {
        $this->returnFileCallable = $returnFileCallable;
    }

    /**
     * @return mixed
     */
    public function getReturnFileCallable()
    {
        return $this->returnFileCallable;
    }





}
