<?php

namespace xtcy\ArcaneCore\player;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use xtcy\ArcaneCore\Loader;
use xtcy\ArcaneCore\utils\QueryConstants;

final class SessionManager
{
    use SingletonTrait;

    /** @var Session[] */
    private array $sessions; // array to fetch player data

    public function __construct(
        public Loader $plugin
    ){
        self::setInstance($this);

        $this->loadSessions();
    }

    /**
     * Store all player data in $sessions property
     *
     * @return void
     */
    private function loadSessions() : void
    {
        Loader::getDatabase()->executeSelect(QueryConstants::PLAYERS_SELECT, [], function (array $rows) : void {
            foreach ($rows as $row) {
                $this->sessions[$row["uuid"]] = new Session(
                    Uuid::fromString($row["uuid"]),
                    $row["username"],
                    $row["gems"],
                    $row["kills"],
                    $row["deaths"],
                    $row["bounty"],
                    $row["plevel"]
                );
            }
        });
    }

    /**
     * Create a session
     *
     * @param Player $player
     * @return Session
     */
    public function createSession(Player $player) : Session
    {
        $args = [
            "uuid"     => $player->getUniqueId()->toString(),
            "username" => $player->getName(),
            "gems"    => 0,
            "kills"    => 0,
            "deaths"    => 0,
            "bounty"     => 0,
            "plevel"     => 0
        ];

        Loader::getDatabase()->executeInsert(QueryConstants::PLAYERS_CREATE, $args);

        $this->sessions[$player->getUniqueId()->toString()] = new Session(
            $player->getUniqueId(),
            $args["username"],
            $args["gems"],
            $args["kills"],
            $args["deaths"],
            $args["bounty"],
            $args["plevel"]
        );
        return $this->sessions[$player->getUniqueId()->toString()];
    }

    /**
     * Get session by player object
     *
     * @param Player $player
     * @return Session|null
     */
    public function getSession(Player $player) : ?Session
    {
        return $this->getSessionByUuid($player->getUniqueId());
    }

    /**
     * Get session by player name
     *
     * @param string $name
     * @return Session|null
     */
    public function getSessionByName(string $name) : ?Session
    {
        foreach ($this->sessions as $session) {
            if (strtolower($session->getUsername()) === strtolower($name)) {
                return $session;
            }
        }
        return null;
    }

    /**
     * Get session by UuidInterface
     *
     * @param UuidInterface $uuid
     * @return Session|null
     */
    public function getSessionByUuid(UuidInterface $uuid) : ?Session
    {
        return $this->sessions[$uuid->toString()] ?? null;
    }

    public function destroySession(Session $session) : void
    {
        Loader::getDatabase()->executeChange(QueryConstants::PLAYERS_DELETE, ["uuid", $session->getUuid()->toString()]);

        # Remove session from the array
        unset($this->sessions[$session->getUuid()->toString()]);
    }

    public function getSessions() : array
    {
        return $this->sessions;
    }
}