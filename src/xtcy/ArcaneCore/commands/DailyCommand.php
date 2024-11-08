<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xtcy\ArcaneCore\utils\ArcaneInventories;

class DailyCommand extends BaseCommand
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

        ArcaneInventories::getDailyRewardsInventory()->send($sender);
    }
}