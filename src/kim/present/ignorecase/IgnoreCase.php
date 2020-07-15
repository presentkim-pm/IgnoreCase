<?php

/*
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
 * @author      PresentKim (debe3721@gmail.com)
 * @link        https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\ignorecase;

use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;

class IgnoreCase extends PluginBase implements Listener{
    /** @var IgnoreCase */
    private static $instance;

    /** @return IgnoreCase */
    public static function getInstance() : IgnoreCase{
        return self::$instance;
    }

    /**
     * Called when the plugin is loaded, before calling onEnable()
     */
    public function onLoad() : void{
        self::$instance = $this;
    }

    /**
     * Called when the plugin is enabled
     */
    public function onEnable() : void{
        //Register event listeners
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @priority LOWEST
     *
     * @param CommandEvent $event
     */
    public function onCommandEvent(CommandEvent $event) : void{
        $event->setCommand($this->replaceCommand($event->getCommand()));
    }

    /**
     * Replace command to exact command with ignore case
     *
     * @param string $command
     *
     * @return string
     */
    public function replaceCommand(string $command) : string{
        $explode = explode(" ", $command);
        $commands = $this->getServer()->getCommandMap()->getCommands();
        if(isset($commands[$explode[0]])){
            return $command;
        }else{
            foreach($this->getServer()->getCommandMap()->getCommands() as $key => $value){
                if(strcasecmp($explode[0], $key) === 0){
                    $explode[0] = $key;
                    break;
                }
            }
        }
        return implode(" ", $explode);
    }
}