<?php

/*
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://opensource.org/licenses/MIT MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\ignorecase\listener;

use kim\present\ignorecase\IgnoreCase;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;

class CommandEventListener implements Listener{
	/** @var IgnoreCase */
	private $plugin;

	/**
	 * CommandEventListener constructor.
	 *
	 * @param IgnoreCase $plugin
	 */
	public function __construct(IgnoreCase $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * @priority LOWEST
	 *
	 * @param CommandEvent $event
	 */
	public function onCommandEvent(CommandEvent $event) : void{
		$event->setCommand($this->plugin->replaceCommand($event->getCommand()));
	}
}
