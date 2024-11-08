<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\commands\subcommands\levels\helpSubCommand;
use xtcy\ArcaneCore\commands\subcommands\levels\reloadSubCommand;
use xtcy\ArcaneCore\commands\subcommands\levels\removeSubCommand;
use xtcy\ArcaneCore\commands\subcommands\levels\setSubCommand;
use xtcy\ArcaneCore\Loader;

class LevelsCommand extends BaseCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {

        $this->setPermission("arcane_default_command");
        $this->registerArgument(0, new RawStringArgument("player", true));
        $this->registerSubCommand(new reloadSubCommand($this->plugin, "reload", "Reload the plugins configuration."));
        $this->registerSubCommand(new helpSubCommand($this->plugin, "help", "Shows the leveling system commands."));
        //$this->registerSubCommand(new addSubCommand($this->plugin, "add", "Add levels to a player"));
        $this->registerSubCommand(new removeSubCommand($this->plugin, "remove", "Remove levels from a player", ["subtract", "rem", "del"]));
        $this->registerSubCommand(new setSubCommand($this->plugin, "set", "Set levels to a player"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $p = $args["player"] ?? null;
        $config = Utils::getConfiguration(Loader::getInstance(), "data/messages.yml");
        if ($p !== null) {
            $player = Utils::getPlayerByPrefix($p);

            if ($player === null) {
                $sender->sendMessage(TextFormat::RED . "Player not found!");
                return;
            }

            $session = Loader::getSessionManager()->getSession($player);
            $level = $session->getLevel();

            foreach ($config->getNested("messages.level-info") as $message) {
                $sender->sendMessage(TextFormat::colorize(str_replace(["{player}", "{level}"], [$player->getName(), number_format($level)], $message)));
            }
        } else {
            if (!$sender instanceof Player) {
                return;
            }

            $session = Loader::getSessionManager()->getSession($sender);
            $level = $session->getLevel();

            foreach ($config->getNested("messages.level-info") as $message) {
                $sender->sendMessage(TextFormat::colorize(str_replace(["{player}", "{level}"], [$sender->getName(), number_format($level)], $message)));
            }
        }
    }
}