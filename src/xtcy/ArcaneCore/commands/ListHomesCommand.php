<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xtcy\ArcaneCore\player\home\Home;
use xtcy\ArcaneCore\player\home\HomeManager;

class ListHomesCommand extends BaseCommand
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

        $homes = HomeManager::getInstance()->getHomeList($sender->getUniqueId());

        if ($homes === null) {
            $sender->teleport($sender->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
            return;
        }

        $homesNames = array_map(static function (Home $home) {
            return $home->getName();
        }, $homes);

        $sender->sendMessage(ListHomesCommand . phpTextFormat::colorize("&r&3&lSERVER &8» &r&7Homes: &r&f"));
    }

}