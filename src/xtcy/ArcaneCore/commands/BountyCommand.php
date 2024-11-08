<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\player\Player;
use xtcy\ArcaneCore\commands\subcommands\bounty\addBountySubCommand;
use xtcy\ArcaneCore\commands\subcommands\bounty\helpBountySubCommand;
use xtcy\ArcaneCore\commands\subcommands\bounty\removeBountySubCommand;
use xtcy\ArcaneCore\Loader;
use xtcy\ArcaneCore\utils\ArcaneInventories;

class BountyCommand extends BaseCommand {

    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
        $this->registerSubCommand(new addBountySubCommand(Loader::getInstance(), "add", "Add a bounty to a player", ["set", "give"]));
        $this->registerSubCommand(new helpBountySubCommand(Loader::getInstance(), "help", "Help command for bounties"));
        $this->registerSubCommand(new removeBountySubCommand(Loader::getInstance(), "remove", "Remove a bounty from a player"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        ArcaneInventories::getBountyInventory($sender)->send($sender);
    }
}
