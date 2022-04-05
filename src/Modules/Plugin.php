<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-05 11:04:18
 * @modify date 2022-04-05 12:44:56
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius\Modules;

use Zein\Console\Output\Output;
use Zein\Storage\Local\Directory;

class Plugin implements Contract
{
    private object $directory;
    private array $defaultAttribute = [
        'plugin_name', 'plugin_uri',
        'description', 'version',
        'author', 'author_uri',
        'module_target', 'hook_target',
        'label', 'type'
    ];

    public function __construct(string $pluginDirectory = '')
    {
        $this->directory =  new Directory;
        
        if (!defined('SENAYAN_VERSION') && empty($pluginDirectory))
        {
            $this->directory->plugins = __DIR__ . '/../../test/plugins';
        }
        else
        {
            $this->directory->plugins = $pluginDirectory;
        }
    }

    public function create(string $pluginName, object $command)
    {
        $createPluginDir = $this->directory->createInPlugins(basename($pluginName));

        if (empty($this->directory->getError()))
        {
            $dotTemplate = file_get_contents(__DIR__ . '/../Template/dot-plugin.template');
            
            foreach ($this->defaultAttribute as $attribute) {
                if ($attribute === 'type')
                {
                    $dotTemplate = str_replace('{type}', $this->type($command), $dotTemplate);
                    continue;
                }
                
                $dotTemplate = str_replace('{'.$attribute.'}', $command->option($attribute), $dotTemplate);
            }

            Output::success($dotTemplate);
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
                $register = '$plugin->register("' . $command->option('hook_target') . '", __DIR__ . function(){});';
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