<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xtcy\ArcaneCore\Loader;

class ArcheologistsCommand extends BaseCommand
{

    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $session = Loader::getSessionManager()->getSession($sender);
        $itemInHand = $sender->getInventory()->getItemInHand();
        $tag = $itemInHand->getNamedTag();

        if ($tag->getTag("artifacts")) {
            $artifactTag = $tag->getString("artifacts");
            if ($artifactTag === "small_gemstone" || $artifactTag === "clay_pot" || $artifactTag === "ash_brick") {
                $amount = $itemInHand->getCount();
                $sender->getInventory()->removeItem($itemInHand);
                $session->addGems($amount);
                $sender->sendMessage(ArcheologistsCommand . phpTextFormat::colorize("&r&l&6Ethereal &fArcheology &8» &r&fYou have sold &a") . TextFormat::colorize("&r&f of ") . TextFormat::colorize($itemInHand->getCustomName()));
                $sender->sendMessage(ArcheologistsCommand . phpTextFormat::colorize("&r&eYou have received &d") . "⛁");
                return;
            } elseif ($artifactTag === "flame_flower" || $artifactTag === "elixir_flask") {
                $amount = $itemInHand->getCount();
                $gemsEarned = 2 * $amount;
                $sender->getInventory()->removeItem($itemInHand);
                $session->addGems($gemsEarned);
                $sender->sendMessage(ArcheologistsCommand . phpTextFormat::colorize("&r&l&6Ethereal &fArcheology &8» &r&fYou have sold &a") . TextFormat::colorize("&r&f of ") . TextFormat::colorize($itemInHand->getCustomName()));
                $sender->sendMessage(ArcheologistsCommand . phpTextFormat::colorize("&r&eYou have received &d") . "⛁");
                return;
            } elseif ($artifactTag === "jewelry_shard" || $artifactTag === "fossilized_egg") {
                $amount = $itemInHand->getCount();
                $gemsEarned = 3 * $amount;
                $sender->getInventory()->removeItem($itemInHand);
                $session->addGems($gemsEarned);
                $sender->sendMessage(ArcheologistsCommand . phpTextFormat::colorize("&r&l&6Ethereal &fArcheology &8» &r&fYou have sold &a") . TextFormat::colorize("&r&f of ") . TextFormat::colorize($itemInHand->getCustomName()));
                $sender->sendMessage(ArcheologistsCommand . phpTextFormat::colorize("&r&eYou have received &d") . "⛁");
                return;
            } elseif ($artifactTag === "sacred_tome" || $artifactTag === "death_arrow") {
                $amount = $itemInHand->getCount();
                $gemsEarned = 4 * $amount;
                $sender->getInventory()->removeItem($itemInHand);
                $session->addGems($gemsEarned);
                $sender->sendMessage(ArcheologistsCommand . phpTextFormat::colorize("&r&l&6Ethereal &fArcheology &8» &r&fYou have sold &a") . TextFormat::colorize("&r&f of ") . TextFormat::colorize($itemInHand->getCustomName()));
                $sender->sendMessage(ArcheologistsCommand . phpTextFormat::colorize("&r&eYou have received &d") . "⛁");
                return;
            }

        }

        $sender->sendMessage(TextFormat::colorize("&r&l&aArcheologists: &r&7Find &oArtifacts &r&7by mining &agrass &7or &6soul soil &7in"));
        $sender->sendMessage(TextFormat::colorize("&r&7 the digsite! Sell &oArtifacts &r&7back to for me &d⛁&r&7!"));
        return;
    }
}