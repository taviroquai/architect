<?php

/**
 * App alias. The other aliases are:
 * 
 * conf() - Returns a configuration item
 * 
 * r() - Adds a new route
 * 
 * v() - Creates a new view giving a PHP template and a $data as associative array
 * 
 * c() - Adds content to the default theme
 * 
 * u() - Returns an internal URL - USE THIS!
 * 
 * g() - Returns all $_GET parameters or one parameter value
 * 
 * j() - Sets JSON output from associative array
 * 
 * o() - Output. Sets the application Output, ie. a View or plain text
 * 
 * m() - Adds a message to be shown on output theme
 * 
 * p() - Returns all $_POST parameters or one parameter value
 * 
 * f() - Returns a $_FILES entry by index
 * 
 * q() - Query table. Returns a Table instance to start querying
 * 
 * s() - Returns a secured (encrypted) string - use for passwords
 * 
 * e() - Adds a new event
 * 
 * tr() - Triggers the event
 * 
 * @return \Arch\App The application main gate
 */
function app(\Arch\App $app = null)
{ 
    if ($app) {
        $GLOBALS['arch'] = $app;
    }
    if (
        !isset($GLOBALS['arch'])
        || get_class($GLOBALS['arch']) !== 'Arch\App'
    ) {
        throw new Exception('Please define $arch = new \Arch\App() first');
    }
    return $GLOBALS['arch'];
}

/**
 * Returns a configuration item by key
 * 
 * @param string $key
 * @return string
 */
function conf($key)
{
    return app()->config->get($key);
}

/**
 * Add route alias.
 * 
 * Use it as <b>r('/', function() { o('Home!'); });</b>
 * 
 * @param string $uri The uri query passed in the browser
 * @param function $action The callback to be run
 */
function r($uri, $action)
{
    app()->addRoute($uri, $action);
}

/**
 * Alias of addContent.
 * 
 * Use it as <b>c('Hello World!');</b> or <b>c(new My\View());</b>
 * 
 * @param string|View $content The content to be rendered
 * @param string $slotName The name of the theme's slot
 * @param boolean $unique Tells if must be the only content
 * @return \Arch\View The default theme
 */
function c($content, $slotName = 'content', $unique = false)
{
    return app()->theme->addContent($content, $slotName, $unique);
}

/**
 * Add event alias.
 * 
 * Use it as 
 * 
 * <b>
 * e('arch.session.before.load', function() {
 *     session_start();
 * });
 * </b>
 * 
 * @param string $eventName The event name
 * @param function $callback The callback to be run
 * @param mixed $target An optional object to be passed to the callback
 * @return \Arch\App The application singleton
 */
function e($eventName, $callback, $target = null)
{
    return app()->addEvent($eventName, $callback, $target);
}

/**
 * Input $_FILES alias. Supports multiple files.
 * 
 * Use it as <b>f(0)</b> to get the $_FILES['file'][0];
 * 
 * @param integer $index The index of the file, default to 0
 * @return array Returns the $_FILES entry
 */
function f($index)
{
    return app()->input->file($index);
}

/**
 * $_GET alias.
 * 
 * @param string $param The name of the $_GET param
 * @return boolean|mixed Returns all params or the param value or false
 */
function g($param = null)
{
    return app()->input->get($param);
}

/**
 * Send JSON alias.
 * 
 * @param array $data The associative array containing data
 * @param boolean $cache Tells application to send cache headers
 */
function j($data, $cache = false) {
    app()->sendJSON($data, $cache);
}

/**
 * Add message alias.
 * 
 * @param string $text The message to be shown
 * @param string $cssClass The css class to style
 */
function m($text, $cssClass = 'alert alert-success')
{
    app()->addMessage($text, $cssClass);
}

/**
 * $_POST alias.
 * 
 * @param string $param The name of the $_POST param
 * @return boolean|mixed Returns all params or the param value or false
 */
function p($param = null)
{
    return app()->input->post($param);
}

/**
 * Database table query alias.
 * 
 * Use it as:
 * 
 * <b>q('tablename')->select()->fetchAll()</b>
 * 
 * @param string $tableName The name of the relational database table
 * @param \PDO $db A PDO instance if not using application default
 * @return \Arch\Table The table to start querying
 */
function q($tableName, \PDO $db = null)
{
    return app()->createQuery($tableName, $db);
}

/**
 * Output alias
 * 
 * This is a fast way to send text output
 * It will use an Output instance to send
 * 
 * Example:
 * 
 * <b>o('Hello World!');</b>
 * 
 * @param mixed $content The content to be sent
 * @return \Arch\Output The output object
 */
function o($content = null)
{
    return app()->output->setContent($content);
}

/**
 * Encrypt string alias.
 * 
 * @param string $string The string to be encrypted
 * @param string $algo The algorithmn to be used for encryption
 * @param string $salt An optional salt
 * @return string The result encrypted string
 */
function s($string, $algo = 'sha256', $salt = '!Zz$9y#8x%7!')
{
    return app()->encrypt($string, $algo, $salt);
}

/**
 * Trigger event alias.
 * 
 * @param string $eventName The event name
 * @param mixed $target An optional object to be passed
 * @return \Arch\App The application singleton
 */
function tr($eventName, $target = null)
{
    return app()->triggerEvent($eventName, $target);
}

/**
 * Build internal URL alias.
 * 
 * @param string $path The internal route key
 * @param array $params Optional QUERY params
 * @return string The final URL
 */
function u($path, $params = array())
{
    return app()->url($path, $params);
}

/**
 * Create view alias.
 * 
 * @param string $tmpl The template path
 * @param array $data The data to be passed to the template
 * @return \Arch\View The created View
 */
function v($tmpl, $data = array())
{
    return app()->createView($tmpl, $data);
}

/**
 * Returns a new validation rule
 * @param string $name The input param
 * @param string $type The type of rule
 * @param string $error_msg The message if invalid input
 * @return \Arch\Rule
 */
function rule($name, $type, $error_msg)
{
    return app()->input->createRule($name, $type, $error_msg);
}