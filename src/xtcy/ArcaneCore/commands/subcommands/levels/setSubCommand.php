<?php

namespace xtcy\ArcaneCore\commands\subcommands\levels;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\item\StringToItemParser;
use pocketmine\utils\TextFormat;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\Loader;

class setSubCommand extends BaseSubCommand
{
    /**
     * @throws ArgumentOrderException
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane_admin_command");
        $this->registerArgument(0, new RawStringArgument("player", false));
        $this->registerArgument(1, new IntegerArgument("levels", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $config = Utils::getConfiguration($this->plugin, "data/messages.yml");

        if (count($args) < 2) {
            $sender->sendMessage("§cUsage: /$aliasUsed <player> <amount>");
            return;
        }

        $playerName = $args["player"];
        $amount = $args["levels"];

        if (!is_numeric($amount)) {
            $sender->sendMessage(TextFormat::colorize($config->getNested("messages.not-number")));
            return;
        }

        $amount = (int)$amount;

        $player = Utils::getPlayerByPrefix($playerName);

        if ($player === null) {
            $sender->sendMessage(TextFormat::colorize(str_replace("{player}", $playerName, $config->getNested("messages.player-offline"))));
            return;
        }

        $session = Loader::getSessionManager()->getSession($player);
        $currentLevel = $session->getLevel();
        $newLevel = $currentLevel + $amount;

        for ($level = $currentLevel + 1; $level <= $newLevel; $level++) {
            $levelConfig = $config->getNested("levels.$level");

            if ($levelConfig !== null) {
                foreach ($levelConfig["rewards"] as $reward) {
                    $item = StringToItemParser::getInstance()->parse($reward["item"]);

                    if (isset($reward["name"])) {
                        $item->setCustomName(TextFormat::colorize($reward["name"]));
                    }

                    if (isset($reward["lore"])) {
                        $item->setLore(array_map(function ($line) {
                            return TextFormat::colorize($line);
                        }, $reward["lore"]));
                    }

                    if (isset($reward["nbt"])) {
                        $nbt = $item->getNamedTag();
                        foreach ($reward["nbt"] as $tag => $value) {
                            $nbt->setString($tag, $value);
                        }
                    }

                    if (isset($reward["enchantments"])) {
                        foreach ($reward["enchantments"] as $enchantment) {
                            $enchant = StringToEnchantmentParser::getInstance()->parse($enchantment["enchant"]);
                            if ($enchant !== null) {
                                $item->addEnchantment(new EnchantmentInstance($enchant, $enchantment["level"]));
                            }
                        }
                    }

                    $session->getPocketminePlayer()->getInventory()->addItem($item);
                }
            }
            $session->setLevel($amount);
            $sender->sendMessage(TextFormat::colorize(str_replace(["{set_level}", "{player}", "{level}"], [number_format($amount), $player->getName(), number_format($level)], $config->getNested("messages.set-level"))));
        }
    }
}