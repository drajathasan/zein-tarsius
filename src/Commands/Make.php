<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-04 21:49:25
 * @modify date 2022-04-04 22:17:47
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius\Commands;
use Zein\Console\Command\Contract;

class Make extends Contract
{
    protected array $signatures = [
        'make:plugin' => ['description' => 'Make a custom slims plugin', 'input' => '{pluginname}'],
        'make:module' => ['description' => 'Make a custom module', 'input' => '{modulename}'] 
    ];
    
    protected array $options = [
        '--tipe' => 'Set plugin type'
    ];

    public function handle()
    {
        var_dump($this->option('type'));
    }
}