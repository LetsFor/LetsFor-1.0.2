<?php
class PluginLoader
{
    private $plugins = [];

    public function register($plugin)
    {
        $this->plugins[] = $plugin;
    }

    public function loadPluginsFromDirectory($directory)
    {
        $pluginDirectories = glob($directory . '/*', GLOB_ONLYDIR);
        foreach ($pluginDirectories as $pluginDirectory) {
            $pluginFiles = glob($pluginDirectory . '/*.php');
            foreach ($pluginFiles as $pluginFile) {
                require_once $pluginFile;
                $pluginClass = basename($pluginFile, '.php');
                if (class_exists($pluginClass)) {
                    $this->register($pluginClass);
                }
            }
        }
    }

    public function load()
    {
        foreach ($this->plugins as $plugin) {
            if (class_exists($plugin)) {
                $pluginInstance = new $plugin();
                if (method_exists($pluginInstance, 'init')) {
                    $pluginInstance->init();
                }
            }
        }
    }

    public function getPlugins()
    {
        return $this->plugins;
    }
}
