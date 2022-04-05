<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-04 21:49:25
 * @modify date 2022-04-05 21:08:14
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius\Commands;
use Zein\Console\Command\Contract;
use Zein\Console\Output\Interactive;
use Zein\Tarsius\Utils;

class Make extends Contract
{
    use Interactive,Utils;

    /**
     * Default signature
     *
     * @var array
     */
    protected array $signatures = [
        'make:plugin' => [
            'description' => 'Make a custom slims plugin', 
            'input' => '{pluginname}',
            'module' => \Zein\Tarsius\Modules\Plugin::class,
        ]
    ];
    
    /**
     * Command options
     *
     * @var array
     */
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

    /**
     * Handle command process
     *
     * @return void
     */
    public function handle()
    {
        // Check if interactive mode is on or not
        if (!is_null($this->option('interactive'))) $this->interactiveOptions();

        $currentSignature = $this->arguments[0]??false;
        
        if ($currentSignature && isset($this->signatures[$currentSignature]))
        {
            $modules = new $this->signatures[$currentSignature]['module'];
            $modules->create($this->argument('pluginname')??$this->option('plugin_name'), $this);
        }
    }

    /**
     * Provide command options based on 
     * interactive mode
     *
     * @return void
     */
    private function interactiveOptions()
    {
        // Question based on command option
        $this->setQuestion($this->commandOptions);

        // manage question answer and set up to option
        $this->getAnswer(function($Make){
            $bypass = [];
            foreach ($Make->interactiveOptions as $option => $question) {

                if (in_array($option,$bypass)) 
                {
                    unset($Make->interactiveOptions[$option]);
                    continue;
                }

                echo "\e[1m$question?\033[0m [tuliskan] ";
                $inputedValue = trim(fgets(STDIN));
                
                if ($option == '--type')
                {
                    $Make->options[] = $option . '=' . $inputedValue;
                    
                    if ($inputedValue == 'hook')
                    {
                        $bypass = ['--module_target', '--label'];
                    }
                    else
                    {
                        $bypass = ['--hook_target'];
                    }
                    
                    continue;
                }

                $Make->options[] = $option . '=' . $inputedValue;
            } 
        });
    }
}