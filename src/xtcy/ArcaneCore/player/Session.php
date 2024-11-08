<?php

namespace xtcy\ArcaneCore\player;

use pocketmine\player\Player;
use pocketmine\Server;
use Ramsey\Uuid\UuidInterface;
use xtcy\ArcaneCore\Loader;
use xtcy\ArcaneCore\utils\QueryConstants;

final class Session
{
    private bool $isConnected = false;

    public function __construct(
        private UuidInterface $uuid,
        private string        $username,
        private int           $gems,
        private int           $kills,
        private int           $deaths,
        private int           $bounty,
        private int           $level,
    )
    {
    }

    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    public function setConnected(bool $connected): void
    {
        $this->isConnected = $connected;
    }

    /**
     * Get UUID of the player
     *
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * This function gets the PocketMine player
     *
     * @return Player|null
     */
    public function getPocketminePlayer(): ?Player
    {
        return Server::getInstance()->getPlayerByUUID($this->uuid);
    }

    /**
     * Get username of the session
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set username of the session
     *
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
        $this->updateDb(); // Make sure to call updateDb function when you're making changes to the player data
    }

    /**
     * Get money of the session
     *
     * @return int
     */
    public function getGems(): int
    {
        return $this->gems;
    }

    /**
     * Add money to the session
     *
     * @param int $amount
     * @return void
     */
    public function addGems(int $amount): void
    {
        $this->gems += $amount;
        $this->updateDb();
    }

    /**
     * Subtract money from the session
     *
     * @param int $amount
     * @return void
     */
    public function subtractGems(int $amount): void
    {
        $this->gems -= $amount;
        $this->updateDb();
    }

    /**
     * Set money of the session
     *
     * @param int $amount
     * @return void
     */
    public function setGems(int $amount): void
    {
        $this->gems = $amount;
        $this->updateDb();
    }

    /**
     * Get kills of the session
     *
     * @return int
     */
    public function getKills(): int
    {
        return $this->kills;
    }

    /**
     * Add kills to the session
     *
     * @param int $amount
     * @return void
     */
    public function addKills(int $amount = 1): void
    {
        $this->kills += $amount;
        $this->updateDb();
    }

    /**
     * @return int
     */
    public function getDeaths(): int {
        return $this->deaths;
    }

    /**
     * @param int $amount
     * @return void
     */
    public function addDeaths(int $amount = 1): void {
        $this->kills += $amount;
        $this->updateDb();
    }

    /**
     * @return int
     */
    public function getBounty(): int {
        return $this->bounty;
    }

    /**
     * @param int $amount
     * @return void
     */
    public function addBounty(int $amount = 1): void {
        $this->bounty += $amount;
        $this->updateDb();
    }

    /**
     * @return void
     */
    public function removeBounty(): void {
        $this->bounty -= 0;
        $this->updateDb();
    }

    /**
     * @return int
     */
    public function getLevel(): int {
        return $this->level;
    }

    /**
     * @param int $amount
     * @return void
     */
    public function addLevel(int $amount = 1): void {
        $this->level += $amount;
        $this->updateDb();
    }

    public function resetLevel(): void {
        $this->level = 0;
        $this->updateDb();
    }

    public function setLevel(int $amount = 0): void {
        $this->level = $amount;
        $this->updateDb();
    }

    public function subtractLevel(int $amount = 1): void {
        $this->level -= $amount;
        $this->updateDb();
    }

    /**
     * Update player information in the database
     *
     * @return void
     */
    private function updateDb(): void
    {
        Loader::getDatabase()->executeChange(QueryConstants::PLAYERS_UPDATE, [
            "uuid" => $this->uuid->toString(),
            "username" => $this->username,
            "gems" => $this->gems,
            "kills" => $this->kills,
            "deaths" => $this->deaths,
            "bounty" => $this->bounty,
            "plevel" => $this->level,
        ]);
    }
}