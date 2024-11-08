<?php

namespace xtcy\ArcaneCore\addons;

use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat as C;
use xtcy\ArcaneCore\Loader;

class CustomItems
{

    public static function getCustomItem(string $type, int $amount = 1): ?Item {
        $item = VanillaItems::AIR()->setCount($amount);

        switch (strtolower($type)) {
            case "small_gemstone":
                $item = VanillaItems::EMERALD()->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&aSmall Gemstone &r&7[Common Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Forged in a lost mine."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "small_gemstone");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
            case "clay_pot":
                $item = VanillaBlocks::FLOWER_POT()->asItem()->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&cClay Pot &r&7[Common Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Made from mud."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "clay_pot");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
            case "ash_brick":
                $item = VanillaBlocks::FLOWER_POT()->asItem()->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&dAsh Brick &r&7[Common Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Recovered from a burning home."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "ash_brick");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
            case "flame_flower":
                $item = VanillaBlocks::SUNFLOWER()->asItem()->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&6Flame Flower &r&d[Uncommon Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Be careful, it's hot."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "flame_flower");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
            case "elixir_flask":
                $item = VanillaItems::GLASS_BOTTLE()->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&3Elixir Flask &r&b[Uncommon Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Once used to contain special elixir."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "elixir_flask");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
            case "jewelry_shard":
                $item = VanillaItems::GOLD_NUGGET()->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&bJewelry Shard &r&d[Rare Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Oooh shiny."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "jewelry_shard");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
            case "fossilized_egg":
                $item = StringToItemParser::getInstance()->parse("turtle_egg")->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&6Fossilized Egg &r&d[Rare Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Millions of years old."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "fossilized_egg");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
            case "sacred_tome":
                $item = VanillaItems::BOOK()->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&eSacred Tome &r&6[Very Rare Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Written by an ancient wizard."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "sacred_tome");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
            case "death_arrow":
                $item = VanillaItems::ARROW()->setCount($amount);

                $item->setCustomName(C::colorize("&r&l&cDeath Arrow &r&6[Very Rare Artifact]"));
                $item->setLore([
                    C::colorize("&r&7Infused with snake venom."),
                    "",
                    C::colorize("&r&7&oFound at &e/digsite"),
                    "",
                    C::colorize("&r&7&oCan be sold to the &aArcheologist"),
                    C::colorize("&r&7&ofor &d⛁ &7or used in &b/recipes")
                ]);

                $item->getNamedTag()->setString("artifacts", "death_arrow");
                $item->addEnchantment(new EnchantmentInstance(Loader::$ench));
                break;
        }
        return $item;
    }

}