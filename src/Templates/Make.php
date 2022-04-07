<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-04 21:53:24
 * @modify date 2022-04-07 08:17:56
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius\Templates;

use Zein\Console\Output\Template\Help as ZeinHelp;
use Zein\Console\Output\Output;

class Make extends ZeinHelp
{
    protected static string $header = 'Make Command';

    public static function render($lists)
    {
        $Help = new static;

        ob_start();

        echo $Help->setNewLine();

        if (isset(static::$header))
        {
            echo static::$header;
            echo $Help->setNewLine(2);
        }

        echo 'Usage:' . $Help->setNewLine(2);
        echo $Help->withSpace('php tarsius make:<signature> <arguments?> <option=value>', 'left', 1) . $Help->setNewLine(2);

        // Option
        echo 'Signature:' . $Help->setNewLine(2);

        foreach ($lists['signature'] as $signature => $attribute) {
            echo ' ' . str_replace('make:', '', $signature);
        }
        
        echo $Help->setNewLine(2);;

        // Option
        echo 'Option:' . $Help->setNewLine(2);

        foreach ($lists['option'] as $option => $info) {
            echo ' ' . $Help->withSpace($option, 'right', (20 - strlen($option))) . $info . $Help->setNewLine();
        }

        return ob_get_clean();
    }
}