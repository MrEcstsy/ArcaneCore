<?php

namespace xtcy\ArcaneCore\commands\subcommands\bounty;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use wockkinmycup\utilitycore\addons\bounty\BountyManager;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\Loader;

class removeBountySubCommand extends BaseSubCommand {

    public BountyManager $bounty;

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane.bounty.admin");
        $this->registerArgument(0, new RawStringArgument("player", false));
        $this->bounty = new BountyManager(Utils::getConfiguration(Loader::getInstance(), "bounty.json"));

    }

    /**
     * @throws \JsonException
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $name = $args["player"];
        $player = Utils::getPlayerByPrefix($name);

        if (!$player instanceof Player) {
            $sender->sendMessage(C::colorize("&r&l&c[!] &r&cPlayer '" . $name . "' not found."));
        } else {
            $playerBounty = $this->bounty::getBounty($player);

            if ($playerBounty <= 0) {
                $sender->sendMessage(C::colorize("&r&l&c[!] &r&c'" . $name . "' does not have a bounty"));
            } else {
                $this->bounty::removeBounty($player->getName());
            }
        }
    }

}