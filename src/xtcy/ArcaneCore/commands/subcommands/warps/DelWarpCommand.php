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

class DelWarpCommand extends BaseSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane.command.delwarp");
        $this->registerArgument(0, new RawStringArgument("warpName", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        if (isset($args["warpName"])) {
            if (!WarpAPI::existWarp($args["warpName"])) {
                WarpAPI::delWarp($args["warpName"]);
                $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&7Warp deleted."));
            } else $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&7This warp doesn't exists."));
        } else $sender->sendMessage(C::colorize("&r&l&eSERVER &8» &r&7Enter a warp name!"));
    }
}