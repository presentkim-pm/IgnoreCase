<?php

/**
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 *
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace kim\present\ignorecase;

use kim\present\traits\removeplugindatadir\RemovePluginDataDirTrait;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;

use function array_shift;
use function explode;
use function implode;
use function rtrim;
use function strcasecmp;

final class Main extends PluginBase implements Listener{
    use RemovePluginDataDirTrait;

    /**
     * @var string[]
     * @phpstan-var array<string, string>
     */
    private array $replaceMap = ["" => ""];

    protected function onEnable() : void{
        /**
         * This is a plugin that does not use data folders.
         * Delete the unnecessary data folder of this plugin for users.
         */
        $dataFolder = $this->getDataFolder();
        if(is_dir($dataFolder) && count(scandir($dataFolder)) <= 2){
            rmdir($dataFolder);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * Replace command to exact command with ignore case
     *
     * @priority LOWEST
     */
    public function onCommandEvent(CommandEvent $event) : void{
        $args = explode(" ", rtrim($event->getCommand(), "\r\n"));
        $label = array_shift($args);

        /** Replace if label exists in replace map */
        if(isset($this->replaceMap[$label])){
            $replacement = $this->replaceMap[$label];

            // Skip if replacement is empty. It means label is already correctly
            if($replacement === ""){
                return;
            }

            $event->setCommand(implode(" ", [$replacement, ...$args]));
            return;
        }

        $knownCommands = $this->getServer()->getCommandMap()->getCommands();
        if(isset($knownCommands[$label])){
            /** If the label is already correct, put the label on the skip list to avoid retrying the navigation. */
            $this->replaceMap[$label] = "";
            return;
        }

        /** Find commands with case insensitivity */
        foreach($knownCommands as $key => $value){
            if(
                strcasecmp($label, $find = $key) === 0 ||
                strcasecmp($label, $find = $value->getLabel()) === 0
            ){
                $this->replaceMap[$label] = $find;
                $event->setCommand(implode(" ", [$find, ...$args]));
                return;
            }
        }
    }
}