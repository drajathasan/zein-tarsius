<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-05 11:04:18
 * @modify date 2022-04-05 21:11:11
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius\Modules;

use Zein\Console\Output\Output;
use Zein\Storage\Local\Directory;
use Zein\Tarsius\Utils;

class Plugin implements Contract
{

    use Utils;

    /**
     * Zein Directory Instance
     *
     * @var object
     */
    private object $directory;

    /**
     * Plugin directory path
     */
    private string $pluginDirectoryPath;

    /**
     * Attribute which use to make
     * plugin information etc
     *
     * @var array
     */
    private array $defaultAttribute = [
        'plugin_name', 'plugin_uri',
        'description', 'version',
        'author', 'author_uri',
        'module_target', 'hook_target',
        'label', 'type'
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function __construct()
    {
        $this->directory =  new Directory;
        
        if (!defined('SENAYAN_VERSION'))
        {
            $this->directory->plugins = __DIR__ . '/../../test/plugins';
            $this->pluginDirectoryPath = __DIR__ . '/../../test/plugins';
        }
        else
        {
            $this->directory->plugins = SB . 'plugins';
            $this->pluginDirectoryPath = SB . 'plugins';
        }
    }

    /**
     * Create based slims plugin
     *
     * @param string $pluginName
     * @param object $command
     * @return void
     */
    public function create(string $pluginName, object $command)
    {
        // Create plugin directory
        $this->directory->createInPlugins(basename($this->lowerSnakeCase($pluginName)));
        $pluginDirPath = $this->pluginDirectoryPath . '/' . $this->lowerSnakeCase($pluginName) . '/';

        if (empty($this->directory->getError()))
        {
            $dotTemplate = $this->loadTemplate('dot-plugin');
            $indexTemplate = $this->loadTemplate('index-' . $command->option('type') . '-plugin');
            
            // Create .plugin.php
            foreach ($this->defaultAttribute as $attribute) {
                if ($attribute === 'type')
                {
                    $dotTemplate = str_replace('{type}', $this->type($command), $dotTemplate);
                    continue;
                }
                
                $dotTemplate = str_replace('{'.$attribute.'}', $command->option($attribute), $dotTemplate);
                
                if ($command->option('type') !== 'hook')
                {
                    $indexTemplate = str_replace('{'.$attribute.'}', $command->option($attribute), $indexTemplate);
                }
            }

            // Finish
            $this->writeFile($pluginDirPath . $this->lowerSnakeCase($pluginName) . '.plugin.php', $dotTemplate);

            if ($command->option('type') !== 'hook')
            {
                $this->writeFile($pluginDirPath . 'index.php', str_replace('{date_created}', date('Y-m-d H:i:s'), $indexTemplate));
            }

            Output::success(PHP_EOL . 'Successfully created plugin ' . $pluginName . ' :)' . PHP_EOL);
        }

        Output::danger($this->directory->getError());
    }

    private function type(object $command)
    {
        switch ($command->option('type')) {
            case 'datalist':
            case 'report':
            case 'print':
                $register = '$plugin->registerMenu("' . $command->option('module_target') . '", "' . $command->option('label') . '", __DIR__ . "/index.php");';
                break;
            
            case 'hook':
                $register = '$plugin->register("' . $command->option('hook_target') . '", function(){});';
                break;

            default:
                $register = null;
                break;
        }

        return $register;
    }

    public function list()
    {
        
    }

    public function info(string $pluginName)
    {
        
    }

    public function delete(string $pluginName)
    {
        
    }
}