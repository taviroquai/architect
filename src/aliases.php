<?php

/**
 * App alias. The other aliases are:
 * 
 * r() Adds a new route
 * 
 * c() Adds content to the default theme
 * 
 * u() Returns an internal URL - USE THIS!
 * 
 * g() Returns all $_GET parameters or one parameter value
 * 
 * m() Adds a message to be shown on output theme
 * 
 * p() Returns all $_POST parameters or one parameter value
 * 
 * f() Returns a $_FILES entry by index
 * 
 * s() Returns a secured (encrypted) string - use for passwords
 * 
 * t() Returns the translation given by key
 * 
 * e() Adds a new event
 * 
 * tr() Triggers the event
 * 
 * @return App
 */
function app()
{ 
    return \Arch\App::Instance();
}

/**
 * Add route alias
 * @param string $uri The uri query passed in the browser
 * @param function $action The callback to be run
 */
function r($uri, $action)
{
    app()->addRoute($uri, $action);
}

/**
 * Alias of addContent
 * @param string|View $content The content to be rendered
 * @param string $slotName The name of the theme's slot
 * @param boolean $unique Tells if must be the only content
 * @return View The default theme
 */
function c($content, $slotName = 'content', $unique = false)
{
    return app()->theme->addContent($content, $slotName, $unique);
}

/**
 * Add event alias
 * @param string $eventName The event name
 * @param function $callback The callback to be run
 * @param mixed $target An optional object to be passed to the callback
 * @return App The application singleton
 */
function e($eventName, $callback, $target = null)
{
    return app()->addEvent($eventName, $callback, $target);
}

/**
 * Input $_FILES alias
 * @param integer $index The index of the file, default to 0
 * @return array Returns the $_FILES entry
 */
function f($index)
{
    return app()->input->file($index);
}

/**
 * $_GET alias
 * @param string $param The name of the $_GET param
 * @return boolean|mixed|array Returns all params or the param value or false
 */
function g($param = null)
{
    return app()->input->get($param);
}

/**
 * Add message alias
 * @param string $text The message to be shown
 * @param string $cssClass The css class to style
 */
function m($text, $cssClass = 'alert alert-success')
{
    app()->addMessage($text, $cssClass);
}

/**
 * $_POST alias
 * @param string $param The name of the $_POST param
 * @return boolean|mixed|array Returns all params or the param value or false
 */
function p($param = null)
{
    return app()->input->post($param);
}

/**
 * Database table query alias
 * @param string $tableName
 * @param \PDO $db
 * @return \Table
 */
function q($tableName, \PDO $db = null)
{
    return \Arch\App::Instance()->query($tableName, $db);
}

/**
 * Encrypt string alias
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
 * Trigger event alias
 * @param string $eventName The event name
 * @param mixed $target An optional object to be passed
 * @return App The application singleton
 */
function tr($eventName, $target = null)
{
    return app()->triggerEvent($eventName, $target);
}

/**
 * Build internal URL alias
 * @param string $path The internal route key
 * @param array $params Optional QUERY params
 * @return string The final URL
 */
function u($path, $params = array())
{
    return app()->url($path, $params);
}

/**
 * Translate alias
 * @param string key Idiom key
 * @param array $data Data to be included in idiom string
 * @return string Complete idiom string
 */
function t($key, $data = array())
{ 
    return app()->translate($key, $data);
}
