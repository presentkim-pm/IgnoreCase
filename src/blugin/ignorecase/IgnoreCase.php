<?php

/*
 *
 *  ____  _             _         _____
 * | __ )| |_   _  __ _(_)_ __   |_   _|__  __ _ _ __ ___
 * |  _ \| | | | |/ _` | | '_ \    | |/ _ \/ _` | '_ ` _ \
 * | |_) | | |_| | (_| | | | | |   | |  __/ (_| | | | | | |
 * |____/|_|\__,_|\__, |_|_| |_|   |_|\___|\__,_|_| |_| |_|
 *                |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  Blugin team
 * @link    https://github.com/Blugin
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace blugin\ignorecase;

use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;

class IgnoreCase extends PluginBase implements Listener{
    /**
     * Called when the plugin is enabled
     */
    public function onEnable() : void{
        //Register event listeners
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * Replace command to exact command with ignore case
     *
     * @priority LOWEST
     *
     * @param CommandEvent $event
     */
    public function onCommandEvent(CommandEvent $event) : void{
        $explode = explode(" ", $event->getCommand());
        $commands = $this->getServer()->getCommandMap()->getCommands();
        if(isset($commands[$explode[0]]))
            return;

        foreach($commands as $key => $value){
            if(strcasecmp($explode[0], $key) === 0){
                $explode[0] = $key;
                break;
            }
        }
        $event->setCommand(implode(" ", $explode));
    }
}