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
 * @author       PresentKim (debe3721@gmail.com)
 * @link         https://github.com/PresentKim
 * @license      https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 *
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace kim\present\ignorecase;

use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;

use function array_shift;
use function count;
use function explode;
use function implode;
use function is_dir;
use function rmdir;
use function rtrim;
use function scandir;

final class Main extends PluginBase implements Listener{

    protected function onEnable() : void{
        /**
         * This is a plugin that does not use data folders.
         * Delete the unnecessary data folder of this plugin for users.
         */
        $dataFolder = $this->getDataFolder();
        if(is_dir($dataFolder)){
            $entries = scandir($dataFolder);
            if($entries !== false && count($entries) <= 2){
                @rmdir($dataFolder);
            }
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

        $replacement = CommandLabelResolver::resolve($label, $this->getServer()->getCommandMap()->getCommands());
        if($replacement !== null){
            $event->setCommand(implode(" ", [$replacement, ...$args]));
        }
    }
}
