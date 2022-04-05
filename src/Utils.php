<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-05 16:50:28
 * @modify date 2022-04-05 21:04:47
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius;

trait Utils
{
    public function loadTemplate(string $templateName)
    {
        $templateDir = __DIR__ . '/Templates/';

        if (file_exists($templatePath = $templateDir . basename($templateName) . '.template'))
        {
            return file_get_contents($templatePath);
        }
    }

    public function writeFile(string $filePath, $contents, bool $debug = false)
    {
        try {
            return file_put_contents($filePath, $contents);
        } catch (\Exception $e) {
            return $debug ? $e->getMessage() : false;
        }
    }

    public function lowerSnakeCase(string $char)
    {
        return strtolower(str_replace(' ', '_', $char));
    }
}