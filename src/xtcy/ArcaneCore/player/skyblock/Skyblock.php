<?php

declare(strict_types=1);

namespace xtcy\ArcaneCore\player\skyblock;

use pocketmine\player\Player;
use pocketmine\Server;
use Ramsey\Uuid\UuidInterface;

final class Skyblock
{

    public function __construct(
        public UuidInterface $leader_uuid,
        public int           $id,
        public string        $name,
        public               $creationDate,
        public int           $bank_balance,
        public int           $island_size,
        public int           $member_limit,
        public int           $block_limit,
        public bool          $block_stacking,
        public string        $biome,

    )
    {
    }

    /**
     * Get UUID of the owner
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
    public function getOwnerPlayer(): ?Player
    {
        return Server::getInstance()->getPlayerByUUID($this->uuid);
    }

    /**
     * Get home's name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->home_name;
    }

    /**
     * Get the world of the home
     *
     * @return World|null
     */
    public function getWorld(): ?World
    {
        return Server::getInstance()->getWorldManager()->getWorldByName($this->world_name);
    }

    /**
     * Get the position of the home
     *
     * @return Position|null
     */
    public function getPosition(): ?Position
    {
        return ($world = $this->getWorld()) === null ? null : (new Position($this->x, $this->y, $this->z, $world));
    }

    /**
     * Utility function to teleport player directly from the home call
     *
     * @param Player $player
     * @return void
     * @throws \RuntimeException
     */
    public function teleport(Player $player): void
    {
        if (($pos = $this->getPosition()) === null) {
            throw new \RuntimeException("The target world is not available for teleport. Perhaps the world isn't loaded?");
        }
        $player->teleport($pos);
    }
}