<?php

namespace MagicalStaff;

//main stuffs
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
//item
use pocketmine\item\Item;
//events
use pocketmine\event\player\PlayerInteractEvent;
//nbts
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
//level
use pocketmine\level\Position\getLevel;
use pocketmine\level\Level;
//sounds
use pocketmine\level\sound\BlazeShootSound;
//Others
use pocketmine\math\Vector3;

class Main extends PluginBase implements Listener {
  
  public function onEnable() {
    $this->getLogger()->info("[ Magical Staff ] The plugin has been successfully enabled with no errors");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onDisable(){
	}
	
  public function onCommand(CommandSender $player, Command $command, $label, array $args) : bool {
  if($command->getName() === "magicstaff") {
    $player->sendMessage("§aYou have received a magical staff!");
    $this->giveItems($player);
    return true;
    
  }
  return false;
  
  }
  
  public function giveItems($player) {
    $player->getInventory()->clearAll();
    $player->getInventory()->setItem(0, Item::get(Item::BLAZE_ROD, 1)->setCustomName("§r§a§lMagical Staff"));
    $player->getInventory()->setItem(8, Item::get(Item::BED, 2)->setCustomName("§cBack"));
  }

  public function onInteract(PlayerInteractEvent $event): void {
    $player = $event->getPlayer();
		$name = $player->getName();
		$item = $player->getInventory()->getItemInHand();
		if ($item->getCustomName() === "§r§a§lMagical Staff"){ //Use the staff
			$nbt = new CompoundTag( "", [ 
				"Pos" => new ListTag( 
				"Pos", [ 
					new DoubleTag("", $player->x),
					new DoubleTag("", $player->y+$player->getEyeHeight()),
					new DoubleTag("", $player->z) 
				]),
				"Motion" => new ListTag("Motion", [ 
						new DoubleTag("", -\sin ($player->yaw / 180 * M_PI) *\cos ($player->pitch / 180 * M_PI)),
						new DoubleTag ("", -\sin ($player->pitch / 180 * M_PI)),
						new DoubleTag("",\cos ($player->yaw / 180 * M_PI) *\cos ( $player->pitch / 180 * M_PI)) 
				] ),
				"Rotation" => new ListTag("Rotation", [ 
						new FloatTag("", $player->yaw),
						new FloatTag("", $player->pitch) 
				] ) 
		] );

			$height = 1.5;
			$snowball = Entity::createEntity("Snowball", $player->getlevel(), $nbt, $player);
			$snowball->setMotion($snowball->getMotion()->multiply($height));
			$snowball->spawnToAll();
                        $level = $player->getLevel();
			$level->addSound(new BlazeShootSound($player));
		} elseif ($item->getCustomName() === "§cBack"){ // Remove the items
		  $player = $event->getPlayer();
      $inventory = $player->getInventory();
      $player->getInventory()->clearAll();
     }
  }
	
}
