<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xtcy\ArcaneCore\Loader;

class DelHomeCommand extends BaseCommand
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

        $homeName = $args["name"] ?? "home";
        $home = Loader::getHomeManager()->getPlayerHome($sender->getUniqueId(), $homeName);

        if ($home === null) {
            $sender->sendMessage(DelHomeCommand . phpTextFormat::colorize("&r&l&3SERVER &8» &r&cError: &r&4Home &c") . TextFormat::colorize(" &r&4doesn't exist."));
            return;
        }

        Loader::getHomeManager()->deleteHome($home);

        $sender->sendMessage(DelHomeCommand . phpTextFormat::colorize("&r&l&3SERVER &8» &r&7Home &c") . TextFormat::colorize(" &r&7has been removed."));
    }


}