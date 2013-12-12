<?php

/**
 * App alias. The other aliases are:
 * 
 * conf() - Returns a configuration item
 * 
 * view() - Returns the generic view factory
 * 
 * help() - Returns the helper factory
 * 
 * theme() - Loads a theme or returns current theme
 * 
 * session() - Sets or gets a session item
 * 
 * redirect() - Sends location HTTP header and save session before exit
 * 
 * filter() - calls Input to **sanitize** an input param
 * 
 * r() - Adds a new route
 * 
 * v() - Creates a new view giving a PHP template and a $data as associative array
 * 
 * c() - Adds content to the default theme
 * 
 * u() - Returns an internal URL - USE THIS!
 * 
 * i() - Returns all $_REQUEST parameters or one parameter value
 * 
 * j() - Sets JSON output from associative array
 * 
 * o() - Output. Sets the application Output, ie. a View or plain text
 * 
 * m() - Adds a message to be shown on output theme
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
    return app()->getConfig()->get($key);
}


/**
 * Returns the views factory
 * @return \Arch\IFactory\GenericViewFactory The view factory
 */
function view()
{
    return app()->getViewFactory();
}

/**
 * Returns the helper factory
 * @return \Arch\IFactory\HelperFactory The helper factory
 */
function help()
{
    return app()->getHelperFactory();
}

/**
 * Loads a theme directory
 * 
 * Use it as <b>theme('/mytheme/')</b>
 * 
 * This will load <b>/theme/mytheme/config.php</b> and 
 * <b>/theme/mytheme/slots.xml</b>.
 * 
 * Theme slots can be configured with <b>slots.xml</b> without programming
 * skills.
 * 
 * Remember that modules that are not enable will not be displayed.
 * 
 * To add content do <b>c('Hello World')</b>
 * 
 * @param string $path The theme path or empty to get current theme
 * 
 * @return \Arch\App The main application
 */
function theme($path = null)
{
    if (is_string($path)) {
        app()->getTheme()->load($path);
        app()->getTheme()->set('idiom', help()->createIdiom());
    }
    return app()->getTheme();
}

/**
 * Session alias - Sets or gets a parameter
 * @param string $key The item key to get or set
 * @param mixed $value The item value to set or mpty to get
 * @return mixed
 */
function session($key, $value = null)
{
    if (!is_null($value)) {
        app()->getSession()->set($key, $value);
    }
    return app()->getSession()->get($key);
}

/**
 * Executes a redirect
 * @param string $url
 */
function redirect($url = '')
{
    help()->createRedirect($url)->execute();
}

/**
 * Calls input to sanitize an input param
 * @param string $key The input key param
 * @param int $filter The sanitize filter
 */
function filter($key, $filter = FILTER_SANITIZE_STRING)
{
    app()->getInput()->sanitize($key, $filter);
}

/**
 * Add route alias.
 * 
 * Use it as <b>r('/', function() { o('Home!'); });</b>
 * 
 * @param string $uri The uri query passed in the browser
 * @param callable $action The callback to be run
 */
function r($uri, callable $action)
{
    $uri = (string) $uri;
    app()->getRouter()->addRoute($uri, $action);
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
    return theme()->addContent($content, $slotName, $unique);
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
    return app()->getEvents()->addEvent($eventName, $callback, $target);
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
    return app()->getInput()->getFileByIndex($index);
}

/**
 * Returns a HTTP param (from $_GET or $_POST)
 * 
 * @param string $param The name of the $_GET param
 * @return boolean|mixed Returns all params or the param value or false
 */
function i($param = null)
{
    return app()->getInput()->get($param);
}

/**
 * Alias to create layout - a view with layout slots
 * @param string $tmpl The template file
 * @param array $data The associative data array
 * @return \Arch\View
 */
function l($tmpl, $data = array())
{
    return new \Arch\Theme\Layout($tmpl, $data);
}

/**
 * Send JSON alias.
 * 
 * @param array $data The associative array containing data
 */
function j($data) {
    help()->createJSON($data)->send();
}

/**
 * Add message alias.
 * 
 * @param string $text The message to be shown
 * @param string $cssClass The css class to style
 */
function m($text, $cssClass = 'alert alert-success')
{
    app()->getSession()->createMessage($text, $cssClass);
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
    return help()->createQuery($tableName, $db)->execute();
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
    return app()->getOutput()->setBuffer($content);
}

/**
 * Encrypt string alias.
 * 
 * @param string $string The string to be encrypted
 * @param string $algo The algorithmn to be used for encryption
 * @param string $salt An optional salt
 * @return string The result encrypted string
 */
function s($string, $salt = '!Zz$9y#8x%7!')
{
    return crypt($string, $salt);
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
    return app()->getEvents()->triggerEvent($eventName, $target);
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
    return help()->createURL($path, $params)->execute();
}

/**
 * Alias to create a view
 * @param string $tmpl The template file
 * @param array $data The associative data array
 * @return \Arch\View
 */
function v($tmpl, $data = array())
{
    return new \Arch\Registry\View($tmpl, $data);
}