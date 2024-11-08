<?php

namespace xtcy\ArcaneCore\commands\subcommands\bounty;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\CommandSender;

class helpBountySubCommand extends BaseSubCommand
{

    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $helpMessage = [
            "&r&l&6[!] &r&6Ethereal &fBounties:",
            "&r&eBounty tax is currently: &f5%",
            " ",
            "&r&e/bounty add <player> <amount> &f- &7Adds a bounty to a player",
            "§r§e/bounty leaderboard §f- §7View the 10 highest bounties",
            "§r§e/bounty view [player] §f- §7View your or another players bounty statistics",
        ];

        foreach ($helpMessage as $message) {
            $sender->sendMessage(C::colorize($message));
            if ($sender->hasPermission("arcane.bounty.admin")) {
                $sender->sendMessage("§r§e/bounty remove §f- §7Remove a bounty from a player.");
                $sender->sendMessage("§r§e/bounty reload §f- §7Reloads the configurations.");
            }
        }
    }
}