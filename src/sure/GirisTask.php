<?php

namespace sure;

use sure\Base;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\Player;

class GirisTask extends Task{

    public function __construct(Base $plugin, Player $o){
//    $this->block = $block;
    $this->plugin = $plugin;
    $this->o = $o;
    }

    public function onRun(int $currentTick){
       $cfg = new Config(Base::getInstance()->getDataFolder()."Sure/".$this->o->getName().".yml", Config::YAML);
      if($this->plugin->time[$this->o->getName()] == 1){
     $cfg->set("Dakika", $cfg->get("Dakika") +10);
     $cfg->set("Saniye", $cfg->get("Saniye") +600);
     $cfg->save();
      }else{
       $this->plugin->time[$this->o->getName()] = 1;
       }
    }

}
