<?php

declare(strict_types=1);

namespace xtcy\ArcaneCore;

use IvanCraft623\RankSystem\RankSystem;
use IvanCraft623\RankSystem\session\Session;
use IvanCraft623\RankSystem\tag\Tag;
use main\src\poggit\libasynql\DataConnector;
use main\src\poggit\libasynql\libasynql;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Rarity;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use wockkinmycup\utilitycore\addons\warps\WarpAPI;
use xtcy\ArcaneCore\commands\ArcheologistsCommand;
use xtcy\ArcaneCore\commands\BountyCommand;
use xtcy\ArcaneCore\commands\DailyCommand;
use xtcy\ArcaneCore\commands\DelHomeCommand;
use xtcy\ArcaneCore\commands\EmojiCommand;
use xtcy\ArcaneCore\commands\GemsCommand;
use xtcy\ArcaneCore\commands\HomeCommand;
use xtcy\ArcaneCore\commands\LevelCommand;
use xtcy\ArcaneCore\commands\LevelsCommand;
use xtcy\ArcaneCore\commands\ListHomesCommand;
use xtcy\ArcaneCore\commands\SetHomeCommand;
use xtcy\ArcaneCore\commands\SpawnCommand;
use xtcy\ArcaneCore\commands\WarpCommand;
use xtcy\ArcaneCore\commands\WorldTPCommand;
use xtcy\ArcaneCore\listeners\ArcaneListener;
use xtcy\ArcaneCore\player\home\HomeManager;
use xtcy\ArcaneCore\player\SessionManager;
use xtcy\ArcaneCore\utils\QueryConstants;

class Loader extends PluginBase {

    public static DataConnector $connector;

    public static SessionManager $manager;

    public static HomeManager $homeManager;

    /** @var Enchantment */
    public static $ench;

    use SingletonTrait;

    public function onLoad(): void
    {
        self::setInstance($this);
    }

    public function onEnable(): void
    {
        $settings = [
            "type" => "sqlite",
            "sqlite" => ["file" => "sqlite.sql"],
            "worker-limit" => 2
        ];

        self::$connector = libasynql::create($this, $settings, ["sqlite" => "sqlite.sql"]);
        self::$connector->executeGeneric(QueryConstants::PLAYERS_INIT);
        self::$connector->executeGeneric(QueryConstants::HOMES_INIT);

        self::$connector->waitAll();

        self::$manager = new SessionManager($this);
        self::$homeManager = new HomeManager($this, 3);
        $this->saveDefaultConfig();
        $this->saveResource('warpsData.json');
        new WarpAPI($this);
        $this->registerCommands();
        $this->registerListeners();
        self::$ench = new Enchantment("", Rarity::COMMON, ItemFlags::NONE, ItemFlags::NONE, 1);
        EnchantmentIdMap::getInstance()->register(999, self::$ench);

        $this->getServer()->getWorldManager()->loadWorld("island");
        $ranksystem = RankSystem::getInstance();
        $tagmanager = $ranksystem->getTagManager();
        $tagmanager->registerTag(new Tag("level", static function(Session $session) : string {
            $level = Loader::getSessionManager()->getSession($session->getPlayer());
            return (string) $level->getLevel();
        }));
        $files = ["data/levels.yml", "data/messages.yml"];
        foreach ($files as $file)
        $this->saveResource($file);
    }

    /**
     * @throws \JsonException
     */
    public function onDisable(): void
    {
        WarpAPI::$data->save();
        if (isset($this->connector)) {
            $this->connector->close();
        }
    }

    public function registerCommands() {
        $this->getServer()->getCommandMap()->registerAll("arcane_core", [
            new EmojiCommand($this, "emoji", "Send a list of available emojis", ["emojis"]),
            new WarpCommand($this, "warp", "Open the warp menu", ["warps"]),
            new BountyCommand($this, "bounty", "Open the bounties menu", ["bounties"]),
            new DailyCommand($this, "daily", "Open the daily rewards menu", ["dailyreward", "dailyrewards"]),
            new GemsCommand($this, "gems", "View your gems or another players gems"),
            new HomeCommand($this, "home", "Teleport to your homes", ["home"]),
            new SetHomeCommand($this, "sethome", "Set a home"),
            new ListHomesCommand($this, "listhomes", "View all your homes", ["homes"]),
            new DelHomeCommand($this, "delhome", "Delete a home"),
            new SpawnCommand($this, "spawn", "Teleport to spawn"),
            new ArcheologistsCommand($this, "archeologys", "Sell your artifacts", ["archeologysell"]),
            new WorldTPCommand($this, "worldtp", "Teleport to another world."),
            new LevelCommand($this, "level", "Increase your current level"),
            new LevelsCommand($this, "levels", "View your or another players level")
        ]);
    }

    public function registerListeners() {
        $pm = $this->getServer()->getPluginManager();
        $pm->registerEvents(new ArcaneListener(), $this);
    }

    public static function getDatabase() : DataConnector
    {
        return self::$connector;
    }

    public static function getSessionManager() : SessionManager
    {
        return self::$manager;
    }

    public static function getHomeManager() : HomeManager {
        return self::$homeManager;
    }
}