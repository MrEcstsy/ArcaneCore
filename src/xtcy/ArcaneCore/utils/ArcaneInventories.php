<?php

namespace xtcy\ArcaneCore\utils;

use cooldogedev\libSQL\ConnectionPool;
use cooldogedev\libSQL\exception\SQLException;
use muqsit\customsizedinvmenu\CustomSizedInvMenu;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\StringToItemParser;
use pocketmine\utils\TextFormat as C;
use pocketmine\block\utils\MobHeadType;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use wockkinmycup\utilitycore\addons\warps\WarpAPI;
use wockkinmycup\utilitycore\tasks\TeleportationTask;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\Loader;

class ArcaneInventories
{

    public static function getWarpInventory(): InvMenu
    {
        $menu = CustomSizedInvMenu::create(27);
        $menu->setName(C::colorize('&r&l&9Warps'));
        $inventory = $menu->getInventory();
        $tpLine = "&r&8‣ &r&fClick to teleport!";
        $inventory->setItem(1, VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::WITHER_SKELETON)->asItem()->setCustomName(C::colorize("&r&l&eDungeons"))->setLore([" ", C::colorize("&r&c⚠ &r&fDangerous Monsters!"), " ", C::colorize('&r&fGet loot by mining and killing mobs!'), ' ', C::colorize($tpLine)]));
        $inventory->setItem(3, VanillaBlocks::CHEST()->asItem()->setCustomName(C::colorize("&r&l&eCrates"))->setLore([" ", C::colorize("&r&fSpend keys to get amazing items!"), " ", C::colorize($tpLine)]));
        $inventory->setItem(5, VanillaBlocks::ENCHANTING_TABLE()->asItem()->setCustomName(C::colorize("&r&l&eEnchant"))->setLore([" ", C::colorize("&r&fUpgrade your items!"), " ", C::colorize($tpLine)]));
        $inventory->setItem(7, VanillaItems::DIAMOND_SWORD()->setCustomName(C::colorize("&r&l&eWarzone"))->setLore([" ", C::colorize("&r&c⚠ &r&fPvP is enabled!"), " ", C::colorize("&r&fFight and loot supply crates!"), " ", C::colorize("&r&8‣ &r&fCurrent Drops Left: &b0"), C::colorize("&r&8‣ &r&fNext Event in: &b59m, 59s"), " ", C::colorize($tpLine)]));
        $inventory->setItem(11, VanillaItems::RAW_SALMON()->setCustomName(C::colorize("&r&l&eFishing Pool"))->setLore([" ", C::colorize("&r&fFish with friends!"), " " . C::colorize($tpLine)]));
        $inventory->setItem(13, VanillaItems::ENDER_PEARL()->setCustomName(C::colorize("&r&l&ePlayer Warps"))->setLore([" ", C::colorize("&r&fPublic Player Warps!"), " ", C::colorize($tpLine)]));
        $inventory->setItem(15, VanillaItems::DIAMOND_SHOVEL()->setCustomName(C::colorize("&r&l&eDig Site"))->setLore([" ", C::colorize("&r&fDig for artifacts!"), " ", C::colorize($tpLine)]));
        $inventory->setItem(19, VanillaBlocks::MONSTER_SPAWNER()->asItem()->setCustomName(C::colorize("&r&l&eSpawner Area"))->setLore([" ", C::colorize("&r&fHarvest mob drops!"), " ", C::colorize($tpLine)]));
        $inventory->setItem(21, VanillaBlocks::OAK_SAPLING()->asItem()->setCustomName(C::colorize("&r&l&eYour Island"))->setLore([" ", C::colorize("&r&fGo to your skyblock island!"), " ", C::colorize($tpLine)]));
        $inventory->setItem(23, VanillaBlocks::END_PORTAL_FRAME()->asItem()->setCustomName(C::colorize("&r&l&eSpawn"))->setLore([" ", C::colorize("&r&fThe main skyblock hub!"), " ", C::colorize($tpLine)]));
        $inventory->setItem(25, VanillaBlocks::BEACON()->asItem()->setCustomName(C::colorize("&r&l&eKOTH"))->setLore([" ", C::colorize("&r&c⚠ &r&fPvP is enabled!"), " ", C::colorize("&r&fCapture the King of the Hill"), C::colorize("&r&fto get rewards!"), " ", C::colorize("&r&fCurrent Status: &cInactive"), C::colorize("&r&fNext KOTH: &r&aStarting in 59:59"), "", C::colorize($tpLine)]));

        $menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) {
            $player = $transaction->getPlayer();
            $slot = $transaction->getAction()->getSlot();

            if ($slot === 23) {
                if ($player->hasPermission("arcane.no_warp_delay")) {
                    $player->removeCurrentWindow();
                    $player->teleport(WarpAPI::getWarp("spawn"));
                    $player->sendMessage(C::colorize("&r&l&3Server &8» &r&7Warping to &cSpawn&7."));
                } elseif (!$player->hasPermission("arcane.no_warp_delay")) {
                    $player->removeCurrentWindow();
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::NAUSEA(), 20 * (Utils::getConfiguration(Loader::getInstance(), "config.yml")->get("warp-delay") + 2), 10));
                    new TeleportationTask(Loader::getInstance(), $player, "spawn");
                }
            }
        }));

        return $menu;
    }

    public static function getDailyRewardsInventory(): InvMenu
    {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $menu->setName(C::colorize("&r&l&9Login Rewards"));

        $inventory = $menu->getInventory();
        $inventory->setItem(35, VanillaItems::ARROW()->setCustomName(C::colorize("&r&l&aNext Page"))->setLore([" ", C::colorize("&r&fClick to go to the next page")]));
        $inventory->setItem(11, StringToItemParser::getInstance()->parse("chest_minecart")->setCustomName(C::colorize("&r&l&bDaily Reward"))->setLore([" ", C::colorize("&r&l&eRewards: &r&71x &aComon Crate &7Key"), " ", C::colorize("&r&l&a[!] &r&aCLICK TO CLAIM!")]));
        $menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) use ($inventory) {
            $slot = $transaction->getAction()->getSlot();
            $itemClicked = $transaction->getItemClicked();
            $player = $transaction->getPlayer();
            $dailyCooldown = 86400;

            if ($slot === 11 && $itemClicked->getTypeId() === StringToItemParser::getInstance()->parse("chest_minecart")->getTypeId()) {
                    $inventory->clear(11);
                    $inventory->setItem(11, VanillaItems::MINECART()->setCustomName(C::colorize("&r&l&bDaily Reward"))->setLore([" ", C::colorize("&r&l&eRewards: &r&71x &aCommon Crate &7Key"), " ", C::colorize("&r&cYou must wait &e" . Utils::translateTime($dailyCooldown) . "&c to"), C::colorize("&r&cclaim this reward!")]));
                    $player->sendMessage(C::colorize("&r&l&2SUCCESS! &r&aYou just received 1 > &lCommon Crate &r&a < key(s)!"));
                    $player->sendMessage(C::colorize("&r&aYou claimed the login reward!"));
                } else {
                    $remainingTime = $dailyCooldown - time();
                    $player->sendMessage(C::colorize("&r&cYou must wait &e" . Utils::translateTime($remainingTime) . "&c to claim this reward!"));
                }
        }));

        $excludedSlots = [11, 13, 15, 28, 29, 30, 31, 32, 33, 34, 35, 46, 47, 48, 49, 50, 51, 52];
        Utils::fillInventory($inventory, "black_stained_glass_pane", $excludedSlots);

        return $menu;
    }

    public static function getBountyInventory(Player $player, int $page = 0): InvMenu
    {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        self::updateBountyInventory($menu, $player, $page);

        $menu->setName(ArcaneInventories . phpC::colorize("&r&l&6Ethereal &8Bounties &r&7Page "));

        return $menu;
    }

    public static function updateBountyInventory(InvMenu $menu, Player $player, int $page): void
    {
        $inventory = $menu->getInventory();
        $inventory->clearAll();

        $config = Utils::getConfiguration(Loader::getInstance(), "bounty.json");
        $perPage = 45;
        $startIndex = $page * $perPage;
        $bountyData = $config->getAll();

        // Custom sorting function
        uasort($bountyData, function ($a, $b) {
            // Sort in descending order based on bounty amount
            return $b - $a;
        });

        $i = 0;

        foreach ($bountyData as $playerName => $bountyAmount) {
            if ($i >= $startIndex) {
                $item = VanillaItems::PAPER();
                $item->setCustomName(C::colorize("&r&l&c" . $playerName));
                $item->setLore([" ", C::colorize("&r&l&eBounty: &r&a$" . number_format($bountyAmount)), " ", C::colorize("&r&7&oKill this player to"), C::colorize("&r&7&oreceive this bounty!")]);
                $inventory->setItem($i - $startIndex, $item);
            }
            $i++;

            if ($i >= $startIndex + $perPage) {
                break;
            }
        }

        $inventory->setItem(45, VanillaItems::ARROW()->setCustomName(C::colorize("&r&7Previous Page")));
        $inventory->setItem(53, VanillaItems::ARROW()->setCustomName(C::colorize("&r&7Next Page")));

        $menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) use ($menu, $player, $page, $config) {
            $slot = $transaction->getAction()->getSlot();

            if ($slot === 50) {
                $player->removeCurrentWindow();
            }

            if ($slot === 45) {
                $newPage = max(0, $page - 1);
                self::updateBountyInventory($menu, $player, $newPage);
            }

            if ($slot === 53) {
                $maxPage = ceil(count($config->getAll()) / 45) - 1;
                $newPage = min($maxPage, $page + 1);
                self::updateBountyInventory($menu, $player, $newPage);
            }
        }));

        $excludedSlots = [45, 48, 50, 53];
        Utils::fillSide($inventory, "black_stained_glass_pane", "bottom", $excludedSlots);

        $inventory->setItem(48, VanillaBlocks::EMERALD()->asItem()->setCustomName(C::colorize("&r&l&aSet Bounty")));
        $inventory->setItem(50, VanillaBlocks::BARRIER()->asItem()->setCustomName(C::colorize("&r&l&cClose")));
    }
}