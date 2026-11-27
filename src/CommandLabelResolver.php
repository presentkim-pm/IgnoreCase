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

use pocketmine\command\Command;

use function strcasecmp;
use function strtolower;

final class CommandLabelResolver{

    private function __construct(){
        // NOOP: static helper
    }

    /**
     * Finds the exactly-cased label of the command that the given label refers to, ignoring case.
     *
     * Returns null if the label is already correct or if no command matches it.
     *
     * @param Command[] $knownCommands registered commands by their registered label
     * @phpstan-param array<string, Command> $knownCommands
     */
    public static function resolve(string $label, array $knownCommands) : ?string{
        if(isset($knownCommands[$label])){
            // The label is already correct
            return null;
        }

        // Command map keys are registered in lower case, so most labels can be resolved without iterating
        $lowerLabel = strtolower($label);
        if(isset($knownCommands[$lowerLabel])){
            return $lowerLabel;
        }

        foreach($knownCommands as $key => $command){
            if(strcasecmp($label, (string) $key) === 0){
                return (string) $key;
            }
            $commandLabel = $command->getLabel();
            if(strcasecmp($label, $commandLabel) === 0){
                return $commandLabel;
            }
        }
        return null;
    }
}
