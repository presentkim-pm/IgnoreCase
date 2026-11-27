<?php

declare(strict_types=1);

namespace kim\present\ignorecase\tests;

use kim\present\ignorecase\CommandLabelResolver;
use PHPUnit\Framework\TestCase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

final class CommandLabelResolverTest extends TestCase{

    /**
     * @param string[] $labels
     * @return array<string, Command>
     */
    private static function commands(array $labels) : array{
        $commands = [];
        foreach($labels as $key => $label){
            $commands[(string) $key] = new class($label) extends Command{
                public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
                    // NOOP
                }
            };
        }
        return $commands;
    }

    public function testAlreadyCorrectLabelIsLeftAlone() : void{
        $commands = self::commands(["tp" => "tp", "say" => "say"]);
        self::assertNull(CommandLabelResolver::resolve("tp", $commands));
    }

    public function testCorrectsCase() : void{
        $commands = self::commands(["tp" => "tp", "say" => "say"]);
        self::assertSame("tp", CommandLabelResolver::resolve("Tp", $commands));
        self::assertSame("say", CommandLabelResolver::resolve("SAY", $commands));
    }

    public function testCorrectsFallbackPrefixedLabel() : void{
        $commands = self::commands(["minecraft:tp" => "tp", "tp" => "tp"]);
        self::assertSame("minecraft:tp", CommandLabelResolver::resolve("Minecraft:TP", $commands));
    }

    public function testCorrectsToCommandLabelWhenKeyDiffers() : void{
        // A command registered under a key that differs in case from the command's own label
        $commands = self::commands(["gm" => "GameMode"]);
        self::assertSame("GameMode", CommandLabelResolver::resolve("gamemode", $commands));
    }

    public function testUnknownLabelIsLeftAlone() : void{
        $commands = self::commands(["tp" => "tp"]);
        self::assertNull(CommandLabelResolver::resolve("unknown", $commands));
        self::assertNull(CommandLabelResolver::resolve("", $commands));
    }
}
