<?php

namespace Arch\IFactory;

/**
 * Description of HelperFactory
 *
 * @author mafonso
 */
class HelperFactory extends \Arch\IFactory
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
     * @return \Arch\IView
     */
    protected function fabricate($type) {
        $type = (string) $type;
        $available = glob(ARCH_PATH.'/src/Arch/View/*.php');
        array_walk($available, function(&$item) {
            $item = str_replace('.php', '', basename($item));
        });
        if (!in_array($type, $available)) {
            throw new Exception('Invalid generic view type');
        }
        $classname = '\Arch\View\\'.$type.'.php';
        return new $classname;
    }
    
    /**
     * Returns a new input validator
     * @return \Arch\Validator
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
     * @return \Arch\Idiom The idiom object
     */
    public function createIdiom(
        $code = null,
        $name = 'default.xml', 
        $module = 'app'
    ) {
        $helper = new \Arch\Helper\CreateIdiom($this->app);
        $helper->setCode($code);
        $helper->setName($name);
        $helper->setModule($module);
        return $helper->execute();
    }
    
    /**
     * Returns a new image.
     * 
     * It helps to create thumbs.
     * 
     * @param string $filename The image file path
     * @return \Arch\Image
     */
    public function createImage($filename)
    {
        $helper = new \Arch\Helper\CreateImage($this->app);
        $helper->setFilename($filename);
        return $helper->execute();
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
     * @param boolean $now If true, just exit, do not proceed to the next stage
     */
    public function redirect($url = null)
    {
        $helper = new \Arch\Helper\Redirect($this->app);
        $helper->setUrl($url);
        $helper->execute();
    }
    
    /**
     * Creates a JSON response, sends it and exits.
     * 
     * You can also use it as <b>j(array('hello' => 'world'))</b>.
     * 
     * @param array $data
     */
    public function sendJSON($data) {
        $helper = new \Arch\Helper\SendJSON($this->app);
        $helper->setData($data);
        $helper->execute();
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
     * @return string The resulting URL
     */
    public function url($action = '', $params = array(), $https = false)
    {
        $helper = new \Arch\Helper\CreateURL($this->app);
        $helper->setAction($action);
        $helper->setParams($params);
        $helper->setHTTPS($https);
        return $helper;
    }
    
    /**
     * Returns an hash of a string.
     * 
     * Instead of using diferent encryptions spread in the application,
     * use this centralized method.
     * 
     * Use it as <b>app()->encrypt('password')</b> or just <b>s('password')</b>
     * 
     * @param string $string The string to be secured
     * @param string $salt A salt
     * @return string The secured string
     */
    public function encrypt($string, $salt = 'sha256')
    {
        $helper = new \Arch\Helper\Crypt($this->app);
        $helper->setString($string);
        $helper->setSalt($salt);
        return $helper->execute();
    }
    
    /**
     * Returns the URL content using cURL.
     * 
     * This is a GET request
     * 
     * Use it as <b>app()->httpGet('http://google.com')</b>
     * 
     * @param string $url The target URL
     * @param boolean $debug If true, debug information will be logged
     * @return string The response body
     */
    public function httpGet($url)
    {
        $helper = new \Arch\Helper\Curl($this->app);
        $helper->setUrl($url);
        return $helper->execute();
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
     * @return string The response body
     */
    public function httpPost($url, $post = array())
    {
        $helper = new \Arch\Helper\Curl($this->app);
        $helper->setUrl($url);
        $helper->setData($post);
        return $helper->execute();
    }
    
    /**
     * Creates a download attachment Output and loads target file
     * 
     * Use it as <b>app()->download('/path/to/attachment.pdf')</b>
     * 
     * @param string $filename The file to be donwloaded
     * @return boolean
     */
    public function download($filename, $attachment = true)
    {
        $helper = new \Arch\Helper\Download($this->app);
        $helper->setFilename($filename);
        $helper->asAttachment($attachment);
        return $helper->execute();
    }
    
    /**
     * Returns a safe url string.
     * 
     * @param string $text The string to be translated
     * @return string The resulting string
     */
    public function slug($text)
    { 
        $helper = new \Arch\Helper\Slug($this->app);
        $helper->setText($text);
        return $helper->execute();
    }
    
    /**
     * Returns a new query on a database table (PDO).
     * 
     * Helps to get and put data onto a database.
     * 
     * @param string $tableName The name of the table
     * @return \Arch\Table The table object
     */
    public function createQuery($tableName)
    {
        $helper = new \Arch\Helper\Query($this->app);
        $helper->setTablename($tableName);
        return $helper->execute();
    }
}