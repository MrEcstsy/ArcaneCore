<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\commands\subcommands\gems\addGemsSubCommand;
use xtcy\ArcaneCore\commands\subcommands\gems\removeGemsSubCommand;
use xtcy\ArcaneCore\commands\subcommands\gems\setGemsSubCommand;
use xtcy\ArcaneCore\Loader;
use xtcy\ArcaneCore\player\SessionManager;
use pocketmine\utils\TextFormat as C;

class GemsCommand extends BaseCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
        $this->registerArgument(0, new RawStringArgument("player", true));
        $this->registerSubCommand(new addGemsSubCommand(Loader::getInstance(), "add", "Add gems to a player", ["give"]));
        $this->registerSubCommand(new removeGemsSubCommand(Loader::getInstance(), "remove", "Remove gems from a player", ["subtract", "take", "rm", "deduct"]));
        $this->registerSubCommand(new setGemsSubCommand(Loader::getInstance(), "set", "Set a players gems"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $session = SessionManager::getInstance()->getSession($sender);

        if ($session === null) {
            $sender->sendMessage(C::colorize("&r&l&cError: Unable to retrieve your session."));
            return;
        }

        if (isset($args["player"])) {
            $p = $args["player"];
            $player = Utils::getPlayerByPrefix($p);

            if ($player !== null) {
                $PSession = SessionManager::getInstance()->getSession($player);

                if ($PSession !== null) {
                    if ($player->getName() === $sender->getName()) {
                        $gems = $session->getGems();
                        $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&fYour Gems: " . number_format($gems)));
                    } else {
                        $pGems = $PSession->getGems();
                        $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&f" . $player->getName() . "'s Gems: " . number_format($pGems)));
                    }
                } else {
                    $sender->sendMessage(C::colorize("&r&l&cError: Unable to retrieve the session for " . $player->getName()));
                }
            } else {
                $sender->sendMessage(C::colorize("&r&l&cError: Player not found."));
            }
        } else {
            $gems = $session->getGems();
            $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&fYour Gems: " . number_format($gems)));
        }
    }
}