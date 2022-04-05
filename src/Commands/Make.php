<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-04 21:49:25
 * @modify date 2022-04-05 14:04:27
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius\Commands;
use Zein\Console\Command\Contract;
use Zein\Console\Output\Interactive;

class Make extends Contract
{
    use Interactive;

    protected array $signatures = [
        'make:plugin' => [
            'description' => 'Make a custom slims plugin', 
            'input' => '{pluginname}',
            'module' => \Zein\Tarsius\Modules\Plugin::class,
        ]
    ];
    
    protected array $commandOptions = [
        '--interactive' => 'Make an interactive question',
        '--type' => 'Set plugin type',
        '--plugin_name' => 'Plugin name',
        '--plugin_uri' => 'Plugin uri',
        '--description' => 'Plugin description',
        '--version' => 'Plugin version',
        '--author' => 'Plugin Author',
        '--author_uri' => 'Plugin Author social media',
        '--module_target' => 'Plugin will show in inputed module name',
        '--hook_target' => 'Plugin will show in inputed hook name',
        '--label' => 'Plugin label in inputed module name'
    ];

    public function handle()
    {
        // Check if interactive mode is on or not
        if (!is_null($this->option('interactive'))) $this->interactiveOptions();

        $currentSignature = $this->arguments[0]??false;
        
        if ($currentSignature && isset($this->signatures[$currentSignature]))
        {
            $modules = new $this->signatures[$currentSignature]['module'];
            $modules->create($this->argument('pluginname'), $this);
        }
    }

    private function interactiveOptions()
    {
        $this->setQuestion($this->commandOptions);
        $this->getAnswer(function($Make){
            
            if ($Make->option('type') !== 'hook') 
            {
                unset($Make->interactiveOptions['--hook_target']);
            }
            else
            {
                unset($Make->interactiveOptions['--module_target']);
            }

            foreach ($Make->interactiveOptions as $option => $question) {
                echo "\e[1m$question?\033[0m [tuliskan] ";
                
                $inputedValue = trim(fgets(STDIN));
                $Make->options[] = $option . '=' . $inputedValue;
            } 
        });
    }
}