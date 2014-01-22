<?php

namespace Arch\Factory;

/**
 * GenericView factory
 * 
 * Use this to create a new generic view
 *
 * @author mafonso
 */
class GenericView extends \Arch\IFactory
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
        $pattern = implode(
            DIRECTORY_SEPARATOR,
            array(ARCH_PATH, 'src', 'Arch', 'View', '*.php')
        );
        $available = glob($pattern);
        array_walk($available, function(&$item) {
            $item = str_replace('.php', '', basename($item));
        });
        if (!in_array($type, $available)) {
            throw new \Exception(
                'Invalid generic view type. '
                .'Use one of the following strings: '.implode(', ', $available)
            );
        }
        $method_name = 'create'.$type;
        return $this->{$method_name}();
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
                ->createURL('/arch/asset/css/bootstrap-datetimepicker.min.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->createURL('/arch/asset/js/bootstrap-datetimepicker.min.js'),
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
                ->createURL('/arch/asset/img/placehold-thumb.gif')
        );
        // add view resources
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->createURL('/arch/asset/css/bootstrap-fileupload.min.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->createURL('/arch/asset/js/bootstrap-fileupload.min.js'),
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
        $view = new \Arch\View\Pagination();
        $view->setInput($this->app->getInput());
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
                ->createURL('/arch/asset/css/font-awesome.min.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->createURL('/arch/asset/css/wysiwyg.css'),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->createURL('/arch/asset/js/jquery.hotkeys.js'),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()
                ->createURL('/arch/asset/js/bootstrap-wysiwyg.js'),
            'js'
        );
        return $view;
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
        $view = new \Arch\View\AntiSpam();
        $view->setSession($this->app->getSession());
        $view->setInput($this->app->getInput());
        return $view;
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
        $view = new \Arch\View\Breadcrumbs();
        $view->parseAction($this->app);
        return $view;
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
                ->createURL('/arch/asset/js/bootstrap-carousel.js')->run(),
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
            $this->app->getHelperFactory()->createURL('/arch/asset/css/leaflet.css')
                ->run(),
            'css'
        );
        $this->app->getTheme()->addContent(
                'http://maps.google.com/maps/api/js?v=3.2&sensor=false',
                'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->createURL('/arch/asset/js/leaflet.js')
                ->run(),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->createURL('/arch/asset/js/leaflet.Google.js')
                ->run(),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->createURL('/arch/asset/js/map.js')
                ->run(),
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
            $this->app->getHelperFactory()->createURL('/arch/asset/css/morris.css')
                ->run(),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->createURL('/arch/asset/js/raphael-min.js')
                ->run(),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->createURL('/arch/asset/js/morris.js')
                ->run(),
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
    public function createFileExplorer()
    {
        return new \Arch\View\FileExplorer();
    }
    
    /**
     * Returns a new Image Gallery view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\ImageGallery
     */
    public function createImageGallery()
    {
        return new \Arch\View\ImageGallery();
    }
    
    /**
     * Returns a new Menu view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory
     * 
     * @return \Arch\View\Menu
     */
    public function createMenu()
    {
        return new \Arch\View\Menu();
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
            $this->app->getHelperFactory()->createURL('/arch/asset/css/morris.css')
                ->run(),
            'css'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->createURL('/arch/asset/js/raphael-min.js')
                ->run(),
            'js'
        );
        $this->app->getTheme()->addContent(
            $this->app->getHelperFactory()->createURL('/arch/asset/js/morris.js')
                ->run(),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new automatic table
     * @return \Arch\View\AutoPanel\AutoTable
     */
    public function createAutoTable()
    {
        return new \Arch\View\AutoTable();
    }
    
    /**
     * Returns a new automatic form
     * @return \Arch\View\AutoPanel\AutoForm
     */
    public function createAutoForm()
    {
        return new \Arch\View\AutoForm();
    }
}
