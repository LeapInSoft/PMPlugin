<?php

declare(strict_types=1);

namespace ExamplePlugin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\block\BlockFactory;
use pocketmine\level\sound\FizzSound;
use pocketmine\block\Block;

use function lcg_value;

class MainClass extends PluginBase implements Listener{

	public function onLoad() : void{
		$this->getLogger()->info(TextFormat::WHITE . "I've been loaded!");
	}

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		//$this->getScheduler()->scheduleRepeatingTask(new BroadcastTask($this->getServer()), 120);
		$this->getLogger()->info(TextFormat::DARK_GREEN . "I've been enabled!");
	}

	public function onDisable() : void{
		$this->getLogger()->info(TextFormat::DARK_RED . "I've been disabled!");
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
			case "example":
				$sender->sendMessage("Hello " . $sender->getName() . "!");

				return true;
			default:
				return false;
		}
	}

	/**
	 * @param PlayerRespawnEvent $event
	 *
	 * @priority        NORMAL
	 * @ignoreCancelled false
	 */
	public function onSpawn(PlayerRespawnEvent $event) : void{
		$this->getServer()->broadcastMessage($event->getPlayer()->getDisplayName() . " has just spawned!");
	}

	public function onBlockFormEvent(BlockFormEvent $event) : void {
		$this->getServer()->broadcastMessage("Try Generating : " . $event->getNewState()->getName());
		if ($event->getNewState()->getId() == Block::COBBLESTONE) {
			$event->setCancelled(true);

			$currentBlock = $event->getBlock();
			$level = $currentBlock->getLevel();
			$level->setBlock($currentBlock, BlockFactory::get(Block::DIAMOND_ORE));
			//$level->addSound($currentBlock->add(0.5, 0.5, 0.5), new FizzSound(2.6 + (lcg_value() - lcg_value()) * 0.8));
			$level->addSound(new FizzSound($currentBlock->add(0.5, 0.5, 0.5), 2.6 + (lcg_value() - lcg_value()) * 0.8));
		}
	}
}
