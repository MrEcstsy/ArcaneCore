<?php

namespace xtcy\ArcaneCore\commands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;

class EmojiCommand extends BaseCommand
{

    public array $allEmojis = [
        "&7:wave: ➤ &d(•◡•)/",
        "&7:shrug: ➤ &d¯\_(ツ)_/¯",
        "&7:wow: ➤ &d(°o°)",
        "&7:facepalm: ➤ &d(－‸ლ)",
        "&7:hearts: ➤ &d(♥‿♥)",
        "&7:love: ➤ &d(✿♥‿♥)",
        "&7:star: ➤ &d★",
        "&7:tick: ➤ &a✓",
        "&7:dead: ➤ &dx__x",
        "&7:magicflip ➤ &d(ﾉ◕ヮ◕)ﾉ*:･ﾟ✧",
        "&7:gimme: ➤ &d༼ つ ◕_◕ ༽つ",
        "&7:flip: ➤ &d(╯°□°）╯︵ ┻━┻",
        "&7:unflip: ➤ &d┬─┬ノ( º _ ºノ)",
        "&7:skull: ➤ &d💀",
        "&7:heart: ➤ &d❤️",
        "&7:up: ➤ &d⬆",
        "&7:derp: ➤ &d(•‿•)"
    ];

    public int $emojisPerPage = 5;

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("arcane_default_command");
        $this->registerArgument(0, new IntegerArgument("page", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $page = isset($args["page"]) ? (int)$args["page"] : 1;
        $emojis = $this->getEmojiList($page);

        if ($emojis === null) {
            $sender->sendMessage(C::RED . "Invalid page number!");
            return;
        }

        $totalPages = ceil(count($this->allEmojis) / $this->emojisPerPage);

        $sender->sendMessage(C::GRAY . "-------------------------");
        $sender->sendMessage(C::BOLD . C::YELLOW . "Emotes ({$page}/{$totalPages}):");
        foreach ($emojis as $emoji) {
            $sender->sendMessage(C::GRAY . "» " . C::WHITE . C::colorize($emoji));
        }
    }

    private function getEmojiList(int $page): ?array
    {

        $totalPages = ceil(count($this->allEmojis) / $this->emojisPerPage);

        if ($page < 1 || $page > $totalPages) {
            return null;
        }

        $startIndex = ($page - 1) * $this->emojisPerPage;
        $endIndex = min($startIndex + $this->emojisPerPage - 1, count($this->allEmojis) - 1);

        return array_slice($this->allEmojis, $startIndex, $endIndex - $startIndex + 1);
    }
}
