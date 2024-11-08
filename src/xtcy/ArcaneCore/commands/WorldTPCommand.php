<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\world\World;
use pocketmine\world\WorldCreationOptions;

class WorldTPCommand extends BaseCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane_admin_command");
        $this->registerArgument(0, new RawStringArgument("world", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $world = $sender->getServer()->getWorldManager()->getWorldByName($args["world"]);

        if ($world !== null) {
            $sender->teleport($world->getSafeSpawn());
        } else {
            $worlds = $sender->getServer()->getWorldManager()->getWorlds();
            $worldNames = array_map(static function (World $world) {
                return $world->getFolderName();
            }, $worlds);
            $sender->sendMessage("worlds: " . implode(", ", $worldNames));
        }
    }

}