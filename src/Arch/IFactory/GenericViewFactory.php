<?php

namespace Arch\IFactory;

/**
 * Description of GenericViewFactory
 *
 * @author mafonso
 */
class GenericViewFactory extends \Arch\IFactory
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
     * Returns a new view for the given template.
     * 
     * The view adds methods to allow data manipulation on the template.
     * 
     * @param string $tmpl The template path
     * @param array $data The associative array with data
     * @return \Arch\View
     */
    public function createView($tmpl, $data = array())
    {
        return new \Arch\Registry\View($tmpl, $data);
    }
    
    /**
     * Returns a new layout - a view with layout slots
     * 
     * The layout adds methods to allow slot manipulation on the template.
     * 
     * @param string $tmpl The template path
     * @param array $data The associative array with data
     * @return \Arch\Theme\Layout
     */
    public function createLayout($tmpl, $data = array())
    {
        return new \Arch\Theme\Layout($tmpl, $data);
    }
    
    /**
     * Returns a new date picker view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\DatePicker
     */
    public function createDatePicker()
    {
        $view = new \Arch\View\DatePicker();
        // add view resources
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/css/bootstrap-datetimepicker.min.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/js/bootstrap-datetimepicker.min.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new file upload view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\FileUpload
     */
    public function createFileUpload()
    {
        $view = new \Arch\View\FileUpload();
        $view->set(
            'default_img',
            $this->app->getHelperFactory()
                ->url('/arch/asset/img/placehold-thumb.gif')
        );
        // add view resources
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/css/bootstrap-fileupload.min.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/js/bootstrap-fileupload.min.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new pagination view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\Pagination
     */
    public function createPagination()
    {
        $view = new \Arch\View\Pagination($this->app->getInput());
        $view->id = 'p1';
        $view->parseCurrent();
        return $view;
    }
    
    /**
     * Creates a new text editor view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\TextEditor
     */
    public function createTextEditor()
    {
        $view = new \Arch\View\TextEditor();
        // add view resources
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/css/font-awesome.min.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/css/wysiwyg.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/js/jquery.hotkeys.js'),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/js/bootstrap-wysiwyg.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Creates a new shopping cart view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\Cart
     */
    public function createCart()
    {
        $model = new \Arch\Model\Cart ($this->app->getSession());
        return new \Arch\View\Cart($model);
    }
    
    /**
     * Returns an anti-spam view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View
     */
    public function createAntiSpam()
    {
        return new \Arch\View\AntiSpam(
            $this->app->getSession(),
            $this->app->getInput()
        );
    }
    
    /**
     * Returns a new breadcrumb view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @param boolena $parseInput Tells to insert items from input
     * @return \Arch\View\Breadcrumbs
     */
    public function createBreadcrumbs()
    {
        return new \Arch\View\Breadcrumbs();
    }
    
    /**
     * Returns a new carousel view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\Carousel
     */
    public function createCarousel()
    {
        $view = new \Arch\View\Carousel();
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->url('/arch/asset/js/bootstrap-carousel.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new comment form.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @param string $tmpl The template file path
     * @return \Arch\View\CommentForm
     */
    public function createCommentForm()
    {
        return new \Arch\View\CommentForm();
    }
    
    /**
     * Returns a new map view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\Map
     */
    public function createMap()
    {
        $view = new \Arch\View\Map();
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/css/leaflet.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
                'http://maps.google.com/maps/api/js?v=3.2&sensor=false',
                'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/js/leaflet.js'),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/js/leaflet.Google.js'),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/js/map.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new Line Chart view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @param string $tmpl The chart template file path
     * @return \Arch\View\LineChart
     */
    public function createLineChart()
    {
        $view = new \Arch\View\LineChart();
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/css/morris.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/js/raphael-min.js'),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/js/morris.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new Tree view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @param string $tmpl The template for the tree
     * @return \Arch\View\TreeView
     */
    public function createTreeView()
    {
        return new \Arch\View\TreeView();
    }
    
    /**
     * Returns a new File Explorer view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @param string $path The base path to be explored
     * @return \Arch\View\FileExplorer
     */
    public function createFileExplorer($path)
    {
        return new \Arch\View\FileExplorer($path);
    }
    
    /**
     * Returns a new Image Gallery view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @param string $path The base path to be explored
     * @return \Arch\View\ImageGallery
     */
    public function createImageGallery($path)
    {
        return new \Arch\View\ImageGallery($path);
    }
    
    /**
     * Returns a new poll view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\Poll
     */
    public function createPoll()
    {
        $view = new \Arch\View\Poll();
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/css/morris.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/js/raphael-min.js'),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->url('/arch/asset/js/morris.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new automatic table
     * @param array $config The configuration
     * @return \Arch\View\AutoPanel\AutoTable
     */
    public function createAutoTable($config)
    {
        if (!$this->app->getDatabase()) {
            $this->app->initDatabase();
        }
        return new \Arch\View\AutoPanel\AutoTable(
            $config,
            $this->app->getDatabase(),
            $this->createPagination()
        );
    }
    
    /**
     * Returns a new automatic form
     * @param array $config The configuration
     * @return \Arch\View\AutoPanel\AutoForm
     */
    public function createAutoForm($config)
    {
        if (!$this->app->getDatabase()) {
            $this->app->initDatabase();
        }
        return new \Arch\View\AutoPanel\AutoForm(
            $config,
            $this->app->getDatabase()
        );
    }
}
