<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-04 21:43:40
 * @modify date 2022-04-04 22:07:21
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius;
use Zein\Console\{Console as ZeinConsole,Argument,Output\Output};

class Console extends ZeinConsole
{
    private object $argument;

    public function __construct()
    {
        $this->argument = new Argument;
        $this->argument->strict = false;
        $this->argument->fetch();    
    }

    public function run()
    {
        // Register default tarsius command
        $this->register([
            'make' => \Zein\Tarsius\Commands\Make::class
        ]);

        if (!$this->argument->get())
        {
            Output::help($this->commandClass, '\Zein\Tarsius\Template\Help');
        }

        $Parameter = $this->argument->getParameter();
        $Option = $this->argument->getOption();

        // Run command
        $Command = $this->{$this->seperateCommand($Parameter[0])};
        $CommandInstance = new $Command($Option, $Parameter);

        $CommandInstance->handle();
    }
}
