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
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0.0
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\ignorecase;

use kim\present\ignorecase\listener\CommandEventListener;
use kim\present\ignorecase\task\CheckUpdateAsyncTask;
use pocketmine\plugin\PluginBase;

class IgnoreCase extends PluginBase{
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
		//Load config file
		$this->saveDefaultConfig();
		$this->reloadConfig();
		$config = $this->getConfig();

		//Check latest version
		if($config->getNested("settings.update-check", false)){
			$this->getServer()->getAsyncPool()->submitTask(new CheckUpdateAsyncTask());
		}

		//Register event listeners
		$this->getServer()->getPluginManager()->registerEvents(new CommandEventListener($this), $this);
	}

	/**
	 * @Override for multilingual support of the config file
	 *
	 * @return bool
	 */
	public function saveDefaultConfig() : bool{
		$resource = $this->getResource("lang/{$this->getServer()->getLanguage()->getLang()}/config.yml");
		if($resource === null){
			$resource = $this->getResource("lang/eng/config.yml");
		}

		$dataFolder = $this->getDataFolder();
		if(!file_exists($configFile = "{$dataFolder}config.yml")){
			if(!file_exists($dataFolder)){
				mkdir($dataFolder, 0755, true);
			}
			$ret = stream_copy_to_stream($resource, $fp = fopen($configFile, "wb")) > 0;
			fclose($fp);
			fclose($resource);
			return $ret;
		}
		return false;
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