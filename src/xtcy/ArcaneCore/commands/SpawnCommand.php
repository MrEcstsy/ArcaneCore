<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use wockkinmycup\utilitycore\tasks\HomeTeleportationTask;

class SpawnCommand extends BaseCommand
{

    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        new HomeTeleportationTask($this->plugin, $sender, $sender->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation(), 3);
    }

}