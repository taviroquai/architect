<?php

namespace Arch\Factory;

/**
 * Helper factory
 * 
 * Use this to create a new helper
 *
 * @author mafonso
 */
class Helper extends \Arch\IFactory
{
    /**
     * Holds the application
     * @var \Arch\App
     */
    protected $app;
    
    /**
     * Returns a new Generic view factory
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App $app) {
        $this->app = $app;
    }

    /**
     * Returns a generic view
     * @return \Arch\IHelper
     */
    protected function fabricate($type) {
        $type = (string) $type;
        $pattern = implode(
            DIRECTORY_SEPARATOR,
            array(ARCH_PATH, 'src', 'Arch', 'Helper', '*.php')
        );
        $available = glob($pattern);
        array_walk($available, function(&$item) {
            $item = str_replace('.php', '', basename($item));
        });
        if (!in_array($type, $available)) {
            throw new \Exception(
                'Invalid helper type. '
                .'Use one of the following strings: '.implode(', ', $available)
            );
        }
        $classname = '\Arch\Helper\\'.$type;
        return new $classname($this->app);
    }
    
    /**
     * Returns a new input validator
     * @return \Arch\Helper\Validator
     */
    public function createValidator()
    {
        return new \Arch\Helper\Validator($this->app);
    }
    
    /**
     * Returns a new Idiom object.
     * 
     * Helps to get translations from idiom files (.xml)
     * 
     * @param string $code The ISO code
     * @param string $name The idiom file name
     * @param string $module A module name to resolve idiom file path
     * @return \Arch\Helper\Idiom The idiom object
     */
    public function createIdiom(
        $code = null,
        $name = 'default.xml', 
        $module = 'app'
    ) {
        $helper = new \Arch\Helper\Idiom($this->app);
        $helper->setCode($code);
        $helper->setName($name);
        $helper->setModule($module);
        return $helper;
    }
    
    /**
     * Returns a new image.
     * 
     * It helps to create thumbs.
     * 
     * @param string $filename The image file path
     * @return \Arch\Helper\Image
     */
    public function createImage($filename)
    {
        $helper = new \Arch\Helper\Image($this->app);
        $helper->setFilename($filename);
        return $helper;
    }
    
    /**
     * Sends a redirect HTTP header.
     * 
     * This will send an HTTP location header and exit application
     * if now is true
     * 
     * Use it as <b>app()->redirect(app()->url('/demo'))</b> or 
     * <b>r(u('/demo'))</b>.
     * 
     * @param string $url The URL to redirect to
     * @return \Arch\Helper\Redirect
     */
    public function createRedirect($url = null)
    {
        $helper = new \Arch\Helper\Redirect($this->app);
        $helper->setUrl($url);
        return $helper;
    }
    
    /**
     * Creates a JSON response, sends it and exits.
     * 
     * You can also use it as <b>j(array('hello' => 'world'))</b>.
     * 
     * @param array $data The data to be encoded
     * @return \Arch\Helper\JSON
     */
    public function createJSON($data) {
        $helper = new \Arch\Helper\JSON($this->app);
        $helper->setData($data);
        return $helper;
    }

    /**
     * Builds and returns an internal url.
     * 
     * Example:
     * 
     * <b>app()->url('/list', array('page' => 1))</b> will generate 
     * <b>/index.php/list?page=1</b>
     * 
     * @param string $action The route action
     * @param array $params An associative array with params
     * @param boolean $https Sets a secure URL
     * @return \Arch\Helper\URL
     */
    public function createURL($action = '', $params = array(), $https = false)
    {
        $helper = new \Arch\Helper\URL($this->app);
        $helper->setAction($action);
        $helper->setParams($params);
        $helper->setHTTPS($https);
        return $helper;
    }

    /**
     * Returns the URL content using cURL.
     * 
     * This is a POST request
     * 
     * Use it as:
     * 
     * <b>app()->httpPost(
     *  'http://google.com', 
     *  array('param' => 1)
     * )</b>
     * 
     * @param string $url The target URL
     * @param array $post The data to be posted
     * @return \Arch\Helper\Curl
     */
    public function createCurl($url, $post = array())
    {
        $helper = new \Arch\Helper\Curl($this->app);
        $helper->setUrl($url);
        $helper->setData($post);
        return $helper;
    }
    
    /**
     * Creates a download attachment Output and loads target file
     * 
     * Use it as <b>app()->download('/path/to/attachment.pdf')</b>
     * 
     * @param string $filename The file to be downloaded
     * @param boolean $attachment Whether is should send attachment headers
     * @return \Arch\Helper\Download
     */
    public function createDownload($filename, $attachment = true)
    {
        $helper = new \Arch\Helper\Download($this->app);
        $helper->setFilename($filename);
        $helper->asAttachment($attachment);
        return $helper;
    }
    
    /**
     * Returns a safe url string.
     * 
     * @param string $text The string to be translated
     * @return \Arch\Helper\Slug
     */
    public function createSlug($text)
    { 
        $helper = new \Arch\Helper\Slug($this->app);
        $helper->setText($text);
        return $helper;
    }
    
    /**
     * Returns a new query on a database table (PDO).
     * 
     * Helps to get and put data onto a database.
     * 
     * @param string $tableName The name of the table
     * @return \Arch\Helper\Query
     */
    public function createQuery($tableName)
    {
        $helper = new \Arch\Helper\Query($this->app);
        $helper->setTablename($tableName);
        return $helper;
    }
}