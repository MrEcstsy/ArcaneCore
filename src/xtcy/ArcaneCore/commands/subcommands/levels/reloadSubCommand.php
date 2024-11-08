<?php

namespace xtcy\ArcaneCore\commands\subcommands\levels;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use wockkinmycup\utilitycore\utils\Utils;

class reloadSubCommand extends BaseSubCommand
{

    public function prepare(): void
    {
        $this->setPermission("arcane_admin_command");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sender->sendMessage(TextFormat::colorize("&r&l&aReloading all configurations"));
        Utils::getConfiguration($this->plugin, "data/messages.yml")->reload();
        Utils::getConfiguration($this->plugin, "data/levels.yml")->reload();
    }
}