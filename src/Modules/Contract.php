<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-05 11:11:26
 * @modify date 2022-04-05 11:55:58
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Tarsius\Modules;

interface Contract
{
    public function create(string $name, object $command);
    public function list();
    public function info(string $name);
    public function delete(string $name);
}