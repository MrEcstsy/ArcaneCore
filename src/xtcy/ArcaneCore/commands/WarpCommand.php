<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\BaseCommand;
use muqsit\customsizedinvmenu\CustomSizedInvMenu;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use pocketmine\block\utils\MobHeadType;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat as C;
use pocketmine\player\Player;
use wockkinmycup\utilitycore\addons\warps\WarpAPI;
use wockkinmycup\utilitycore\tasks\TeleportationTask;
use wockkinmycup\utilitycore\utils\Utils;
use xtcy\ArcaneCore\commands\subcommands\warps\DelWarpCommand;
use xtcy\ArcaneCore\commands\subcommands\warps\SetWarpSubCommand;
use xtcy\ArcaneCore\Loader;
use xtcy\ArcaneCore\utils\ArcaneInventories;

class WarpCommand extends BaseCommand
{

    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
        $this->registerSubCommand(new SetWarpSubCommand(Loader::getInstance(), "set", "Set a warp"));
        $this->registerSubCommand(new DelWarpCommand(Loader::getInstance(), "del", "Remove a warp"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        ArcaneInventories::getWarpInventory()->send($sender);
    }

}