<?php

namespace xtcy\ArcaneCore\commands\subcommands\gems;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\player\SessionManager;

class setGemsSubCommand extends BaseSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane.gems.set");
        $this->registerArgument(0, new IntegerArgument("amount", false));
        $this->registerArgument(1, new RawStringArgument("player", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $p = $args["player"];
        $amount = $args["amount"];


        if (isset($p)) {
            $player = Utils::getPlayerByPrefix($p);
            if ($player !== null) {
                $session = SessionManager::getInstance()->getSession($player);
                if ($session !== null) {
                    if ($amount !== null) {
                        $session->setGems($amount);
                        $sender->sendMessage(setGemsSubCommand . phpC::colorize("&r&l&3SERVER &8» &r&fYou have successfully set '") . "'s' gems to: " . number_format($amount));
                    } else {
                        $sender->sendMessage(C::colorize("&r&l&3SERVER &8» &r&fInvalid amount!"));
                    }
                } else {
                    $sender->sendMessage(C::colorize("&r&l&cError: Unable to retrieve the session for " . $player->getName()));
                }
            } else {
                $sender->sendMessage(C::colorize("&r&l&cError: Player not found."));
            }
        }
    }
}