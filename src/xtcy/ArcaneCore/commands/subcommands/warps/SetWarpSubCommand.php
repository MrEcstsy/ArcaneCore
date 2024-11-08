<?php

namespace xtcy\ArcaneCore\commands\subcommands\warps;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use wockkinmycup\utilitycore\addons\warps\WarpAPI;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\Loader;
use pocketmine\utils\TextFormat as C;

class SetWarpSubCommand extends BaseSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane.command.setwarp");
        $this->registerArgument(0, new RawStringArgument("warpName", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        if (isset($args["warpName"])) {
            if (!WarpAPI::existWarp($args["warpName"])) {
                WarpAPI::addWarp($sender, $args["warpName"]);
                $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&7Warp set."));
            } else $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&7This warp already exists."));
        } else $sender->sendMessage(C::colorize("&r&l&eSERVER &8» &r&7Warp must have a name!"));
    }
}