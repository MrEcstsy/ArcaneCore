<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\tasks\HomeTeleportationTask;
use wockkinmycup\utilitycore\tasks\TeleportationTask;
use xtcy\ArcaneCore\Loader;
use xtcy\ArcaneCore\player\home\Home;
use xtcy\ArcaneCore\player\home\HomeManager;

class HomeCommand extends Basecommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
        $this->registerArgument(0, new RawStringArgument("name", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $homes = HomeManager::getInstance()->getHomeList($sender->getUniqueId());

        if ($homes === null || $args["name"] === null) {
            $sender->teleport($sender->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
            return;
        }

        foreach ($homes as $home) {
            if ($home->getName() === $args["name"]) {
                //Loader::getHomeManager()->getPlayerHome($sender->getUniqueId(), $args["name"])->teleport($sender);

                // make it so if player is in the spawn area there is no tp timer.
                new HomeTeleportationTask(Loader::getInstance(), $sender, $home->getPosition(), 3);
                return;
            }
        }

        $homesNames = array_map(static function (Home $home) {
            return $home->getName();
        }, $homes);

        $sender->sendMessage(HomeCommand . phpC::colorize("&r&3&lSERVER &8» &r&7Homes: &r&f"));
    }
}