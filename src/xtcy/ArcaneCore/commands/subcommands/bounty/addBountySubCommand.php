<?php

namespace xtcy\ArcaneCore\commands\subcommands\bounty;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\player\Player;
use wockkinmycup\utilitycore\addons\bounty\BountyManager;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\Loader;

class addBountySubCommand extends BaseSubCommand
{
    public BountyManager $bounty;

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
        $this->registerArgument(0, new RawStringArgument("player", false));
        $this->registerArgument(1, new IntegerArgument("amount", false));
        $this->bounty = new BountyManager(Utils::getConfiguration(Loader::getInstance(), "bounty.json"));
    }

    /**
     * @throws \JsonException
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $amount = Utils::parseShorthandAmount($args["amount"]);
        $player = Utils::getPlayerByPrefix($args["player"]);

        if ($player !== null) {
            $totalBounty = $this->bounty->getBounty($player->getName()) + $amount;
            $this->bounty->setBounty($sender, $player, $amount);

            $sender->getServer()->broadcastMessage(C::colorize("&r&l&f<&6Bounty&r&f> &r&f&l&r" . $sender->getName() . " &7has set a bounty of &f$" . number_format($amount) . " &7on &f" . $player->getName() . "&7."));
            $sender->getServer()->broadcastMessage(C::colorize("&r&7&oTotal Bounty: " . $totalBounty . " (-5%)"));
        } else {
            $sender->sendMessage(C::colorize("&r&l&c[!] &r&cPlayer '" . $args["player"] . "' not found or is not online."));
        }
    }
}