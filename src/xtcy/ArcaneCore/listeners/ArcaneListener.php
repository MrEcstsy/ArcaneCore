<?php

namespace xtcy\ArcaneCore\listeners;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use xtcy\ArcaneCore\addons\CustomItems;
use xtcy\ArcaneCore\player\SessionManager;

class ArcaneListener implements Listener
{

    public function onLogin(PlayerLoginEvent $event) {
        $player = $event->getPlayer();
        if (SessionManager::getInstance()->getSession($player) === null) {
            SessionManager::getInstance()->createSession($player);
        }
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $players = Server::getInstance()->getOnlinePlayers();
        $message = [
            "&r&l&3===&b===&3===&b===&3===&b===&3===&b===&3===&b===&3===&b===&3===&b===&3===&b===&3===",
            " ",
            "&r&l&e                 Welcome to &6Ethereal &fHub",
            "&r&7&o                             (Arcane Realm)",
            "&r&3              » &l&a/help &r&edetailed command information &r&3«",
            "&r&3                     » &l&d/rankup &r&eto see how to rank up &r&3«",
            "&r&l&e                   Discord: &r&fdiscord.etherealhub.tk",
            "&r&l&e                     Shop: &r&fshop.etherealhub.tk",
            "&r&e                           Total unique players: &a" . count($players),
            " ",
            "&r&l&3===&b===&3===&b===&3===&b===&3===&b===&3===&b===&3===&b===&3===&b===&3===&b===&3===",
        ];
        foreach ($message as $line) {
            $player->sendMessage(C::colorize($line));
        }
        $player->sendMessage(C::colorize("&r&b» &l&e/help &r&7for more detailed command information &3«"));
        $event->setJoinMessage("");
        SessionManager::getInstance()->getSession($player)->setConnected(true);

        $player->getInventory()->addItem(CustomItems::getCustomItem("elixir_flask", 64));

        if (!$player->hasPlayedBefore()) {
            $player->sendMessage(C::colorize("&r&eWelcome to Skyblock! &aEnjoy some free crate keys as a gift!"));
            foreach ($players as $onlinePlayer) {
                $onlinePlayer->sendMessage(C::colorize("&r&dWelcome " . $player->getName() . " to Skyblock!"));
            }
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event) : void
    {
        $player = $event->getPlayer();
        $session = SessionManager::getInstance()->getSession($player);

        $session->setConnected(false);
    }

    public function onUseEmoji(PlayerChatEvent $event): void {
        $message = $event->getMessage();

        $emojiReplacements = [
            ":wave:" => "(•◡•)/",
            ":shrug:" => "¯\_(ツ)_/¯",
            ":wow:" => "(°o°)",
            ":facepalm:" => "(－‸ლ)",
            ":hearts:" => "(♥‿♥)",
            ":love:" => "(✿♥‿♥)",
            ":star:" => "★",
            ":tick:" => C::colorize("&a✔&r"),
            ":dead:" => "x__x",
            ":magicflip:" => "(ﾉ◕ヮ◕)ﾉ*:･ﾟ✧",
            ":gimme:" => "༼ つ ◕_◕ ༽つ",
            ":flip:" => "(╯°□°）╯︵ ┻━┻",
            ":unflip:" => "┬─┬ノ(º_ºノ)",
            ":skull:" => "☠",
            ":heart:" => "♥",
            ":up:" => "⬆",
            ":derp:" => "(•‿•)",
            // Add more replacements as needed
        ];

        $event->setMessage(str_replace(array_keys($emojiReplacements), array_values($emojiReplacements), $message));
    }

    public function onEntitySpawn(EntitySpawnEvent $event) {
        $entity = $event->getEntity();
        if (!$entity instanceof ItemEntity) {
            return;
        }
        $position = $entity->getPosition();
        $world = $position->getWorld();
        $entities = $world->getNearbyEntities($entity->getBoundingBox()->expandedCopy(5, 5, 5));
        if (empty($entities)) {
            return;
        }
        $originalItem = $entity->getItem();
        $totalItemCount = $originalItem->getCount();
        foreach ($entities as $e) {
            if ($e instanceof ItemEntity && $entity !== $e) {
                $itemE = $e->getItem();
                if ($itemE->equals($originalItem)) {
                    $e->flagForDespawn();
                    $originalItem->setCount($originalItem->getCount() + $itemE->getCount());
                    $totalItemCount += $itemE->getCount();
                }
            }
        }
        if ($totalItemCount > 1) {
            $itemName = $originalItem->getName();
            $tag = ArcaneListener . phpC::colorize("&r&6x") . C::colorize(" &r&f") . C::colorize($itemName);
            $entity->setNameTag($tag);
            $entity->setNameTagAlwaysVisible();
        }
    }
}