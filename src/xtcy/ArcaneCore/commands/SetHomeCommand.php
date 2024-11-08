<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;
use xtcy\ArcaneCore\Loader;
use xtcy\ArcaneCore\player\home\HomeManager;

class SetHomeCommand extends Basecommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
        $this->registerArgument(0, new RawStringArgument("name", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $uuid = $sender->getUniqueId();
        $maxHomes = Loader::getHomeManager()->getMaxHomes();
        $playerHomes = Loader::getHomeManager()->getHomeList($uuid);
        if ($playerHomes !== null && count($playerHomes) >= $maxHomes) {
            $sender->sendMessage(SetHomeCommand . phpC::colorize("&r&l&3SERVER &8» &r&cError: &4You cannot set more &c") . C::colorize(" &4homes."));
            return;
        }

        foreach ($playerHomes as $home) {
            if ($home->getName() === $args["name"]) {
                $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&cError: &4You already have a home with that name."));
                return;
            }
        }

        HomeManager::getInstance()->createHome($sender, $args["name"]);
        $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&7Home set."));
    }

}