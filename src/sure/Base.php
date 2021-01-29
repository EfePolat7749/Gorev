<?php

namespace sure;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;
use FormAPI\{SimpleForm, ModalForm, CustomForm};
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use sure\GirisTask;
use pocketmine\utils\Config;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

class Base extends PluginBase implements Listener{

     public $cfgg;
     public $cfg;

   public $f;
   public $cfggg;

    public $time;

    private static $instance;

    public function onLoad(){
        self::$instance = $this;
    }

    public static function getInstance(): Base{
        return self::$instance;
    }
     public function onEnable(){

     $this->getServer()->getPluginManager()->registerEvents($this, $this);
     @mkdir($this->getDataFolder()."Sure/");
     @mkdir($this->getDataFolder()."Missions/");
     }
    public function onJoin(PlayerJoinEvent $e){
    $o = $e->getPlayer();
 //   $cfggg = new Config($this->getDataFolder()."sureler.yml", Config::YAML);

  $this->time[$o->getName()] = 0;
//   if($cfggg->get($o->getName()) == null){ 
//   $cfggg->set($o->getName(), 0);
 // $cfggg->save();
//   }
     $this->getScheduler()->scheduleRepeatingTask(new GirisTask($this, $o), 20*600);
    if(!(file_exists($this->getDataFolder()."Missions/".$o->getName().".yml"))){
    $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);    
         $cfgg->set("Yeni Başlayan", false);
     $cfgg->set("Yeni Başlayan Sayı", 1);
     $cfgg->set("Kırıktaş", 1);
     $cfgg->set("Yeni Başlayan Durum", "§cTamamlanmadı");
      $cfgg->set("Çaylak Oduncu", false);
     $cfgg->set("Çaylak Oduncu Sayı", 1);
     $cfgg->set("Çaylak Oduncu Durum", "§cTamamlanmadı");          
     $cfgg->set("Çaylak Madenci", false);
     $cfgg->set("Çaylak Madenci Sayı", 1);
     $cfgg->set("Çaylak Madenci Durum", "§cTamamlanmadı");
      $cfgg->set("Usta Oduncu", false);
     $cfgg->set("Usta Oduncu Sayı", 1);
     $cfgg->set("Usta Oduncu Durum", "§cTamamlanmadı");          
     $cfgg->set("Usta Madenci", false);
     $cfgg->set("Usta Madenci Sayı", 1);
     $cfgg->set("Usta Madenci Durum", "§cTamamlanmadı");
     $cfgg->set("Odun", 1);
     $cfgg->set("Aktiflik", false);
     $cfgg->set("Aktiflik Durum", "§cTamamlanmadı");
     $cfgg->set("Seviye", 0);
     $cfgg->set("Usta Aktiflik Durum", "§cTamamlanmadı");
      $cfgg->set("Usta Aktiflik", false);
   
     $cfgg->save();

    $this->cfgg = $cfgg;
    $this->cfg = $cfg;
//    $this->cfggg = $cfggg;
}
}
    public function onQuit(PlayerQuitEvent $e){
    $o = $e->getPlayer();
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
    $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
    $cfggg = new Config($this->getDataFolder()."sureler.yml", Config::YAML);
  $cfg->save();
$cfgg->save();
$cfggg->save();
}
    
   public function onCommand(CommandSender $o, Command $kmt, string $label, array $args): bool{
          if($kmt->getName() == "gorev"){          
         $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
         if($cfgg->get("Seviye") == 0){
         	$this->seviyeYok($o);
      }
         if($cfgg->get("Seviye") == 1){
         	$this->seviyeBir($o);
      }
         if($cfgg->get("Seviye") == 2){
         	$this->seviyeIki($o);
      }
         if($cfgg->get("Seviye") == 3){
         	$this->seviyeUc($o);
      }
 }
return true;
}
     public function seviyeBir(Player $o){
     	$f = new SimpleForm(function(Player $o, $args){
     if($args === null){
     	return true;
     }
     switch($args){
     	case 0:

        $this->ustaMadenci($o);
     	break;
     	case 1:
     	$this->ustaOduncu($o);
     	break;
     	case 2: 
     	$this->ustaAktiflik($o);
     	break;
     }
     });
     $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
     $madenci = $cfgg->get("Usta Madenci Durum");
     $oduncu = $cfgg->get("Usta Oduncu Durum");
     $aktiflik = $cfgg->get("Durum"); 
     $f->setTitle("§bRota§fCraft - §eMission System");
     $f->setContent("§6Görev Yaparak Para Kazanbilirsin\n§aGörev Seviyesi: 1");
     $f->addButton("§5Usta Madenci\n$madenci");
     $f->addButton("§5Usta Oduncu\n$oduncu");
     $f->addButton("§5Aktiflik\n$aktiflik");
     $f->sendToPlayer($o);
     }

   public function seviyeYok(Player $o){
     	$f = new SimpleForm(function(Player $o, $args){
     if($args === null){
     	return true;
     }
     switch($args){
     	case 0:
     	$this->yenibaslayan($o);
     	break;
     	case 1:
     	$this->caylakOduncu($o);
     	break;
     	case 2: 
     	$this->caylakMadenci($o);
     	break;
     	case 3:
     	$this->aktiflik($o);
     	break;
     }
     });
     $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
     $yeni = $cfgg->get("Yeni Başlayan Durum");
     $oduncu = $cfgg->get("Çaylak Oduncu Durum");
     $madenci = $cfgg->get("Çaylak Madenci Durum");
     $aktiflik = $cfgg->get("Aktiflik Durum"); 
     $f->setTitle("§bRota§fCraft - §eMission System");
     $f->setContent("§6Görev Yaparak Para Kazanbilirsin\n§aGörev Seviyesi: 0");
     $f->addButton("§5Yeni Başlayan\n$yeni");
     $f->addButton("§5Çaylak Oduncu\n$oduncu");
     $f->addButton("§5Çaylak Madenci\n$madenci");
     $f->addButton("§5Aktiflik\n$aktiflik");
     $f->sendToPlayer($o);
     }

public function yenibaslayan(Player $o){
     $f = new SimpleForm(function(Player $o, $args){
    if($args === null){
    return true;
     }
        $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
     if($cfgg->get("Yeni Başlayan") == false){
      	if($cfgg->get("Yeni Başlayan Sayı") >= 2){
      		$o->sendMessage("§aÖdül Envanterine/hesabına Aktarıldı!");
      		EconomyAPI::getInstance()->addMoney($o, 100000);
      		$cfgg->set("Yeni Başlayan", true);
      				   $cfgg->set("Yeni Başlayan Durum", "Tamamlandı");
      		$cfgg->save();
		
if($cfgg->get("Aktiflik") == true){
  		
if($cfgg->get("Yeni Başlayan") == true){
  		
if($cfgg->get("Çaylak Oduncu") == true){
  		
if($cfgg->get("Çaylak Madenci") == true){
          $cfgg->set("Seviye", 1);
         $cfgg->save();
           }
            }
            }
            }
      	}else{
      		$o->sendMessage("§cGörev Gereksinimleri Karşılanamadı!");
      	}
      }else{
      	$o->sendMessage("§cGörevi Zaten Yapmışsın!");
      }
     }); 
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
     $hedef = 60;
    $sure = $cfg->get("Yeni Başlayan Sayı");
    $para = EconomyAPI::getInstance()->myMoney($o);
      $sil = $hedef - (int) $cfg->get("Dakika");
    $f->setTitle("Görev");
    $f->setContent("§5Adana Toplam 2 tane su koy\n\n\n§9Ödül: §6100.000TL\n\n Görev Durumu: $sure/3");
    $f->addButton("§eÖdülü al");
    $f->sendToPlayer($o);
}
public function ustaMadenci(Player $o){
     $f = new SimpleForm(function(Player $o, $args){
    if($args === null){
    return true;
     }
        $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
     if($cfgg->get("Usta Madenci") == false){
      	if($cfgg->get("Kırıktaş") >= 1500){
      		$o->sendMessage("§aÖdül Envanterine/hesabına Aktarıldı!");
      		EconomyAPI::getInstance()->addMoney($o, 1000000);
      		$cfgg->set("Usta Madenci", true);
      				   $cfgg->set("Usta Madenci Durum", "Tamamlandı");
      		$cfgg->save();
		
      	}else{
      		$o->sendMessage("§cGörev Gereksinimleri Karşılanamadı!");
      	}
      }else{
      	$o->sendMessage("§cGörevi Zaten Yapmışsın!");
      }
     }); 
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
     $hedef = 60;
    $sure = $cfg->get("Kırıktaş");
    $para = EconomyAPI::getInstance()->myMoney($o);
      $sil = $hedef - (int) $cfg->get("Dakika");
    $f->setTitle("Görev");
    $f->setContent("§5Adanda Toplam 1500 Kırıktaş Kır!\n\n\n§9Ödül: §61.000.000TL\n\n Görev Durumu: $sure/1500");
    $f->addButton("§eÖdülü al");
    $f->sendToPlayer($o);
}
public function ustaOduncu(Player $o){
     $f = new SimpleForm(function(Player $o, $args){
    if($args === null){
    return true;
     }
        $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
     if($cfgg->get("Usta Oduncu") == false){
      	if($cfgg->get("Kırıktaş") >= 1500){
      		$o->sendMessage("§aÖdül Envanterine/hesabına Aktarıldı!");
      		EconomyAPI::getInstance()->addMoney($o, 10000000);
      		$cfgg->set("Usta Oduncu", true);
      				   $cfgg->set("Usta Oduncu Durum", "Tamamlandı");
      		$cfgg->save();
		
      	}else{
      		$o->sendMessage("§cGörev Gereksinimleri Karşılanamadı!");
      	}
      }else{
      	$o->sendMessage("§cGörevi Zaten Yapmışsın!");
      }
     }); 
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
     $hedef = 60;
    $sure = $cfg->get("Kırıktaş");
    $para = EconomyAPI::getInstance()->myMoney($o);
      $sil = $hedef - (int) $cfg->get("Dakika");
    $f->setTitle("Görev");
    $f->setContent("§5Adanda Toplam 1500 Meşe Ağacı Kır!\n\n\n§9Ödül: §61.000.000TL\n\n Görev Durumu: $sure/1500");
    $f->addButton("§eÖdülü al");
    $f->sendToPlayer($o);
}
public function caylakMadenci(Player $o){
     $f = new SimpleForm(function(Player $o, $args){
    if($args === null){
    return true;
     }
        $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
           if($cfgg->get("Çaylak Madenci") == false){
      	if($cfgg->get("Çaylak Madenci Sayı") >= 150){
      		$o->sendMessage("§aÖdül Envanterine/hesabına Aktarıldı!");
                EconomyAPI::getInstance()->addMoney($o, 150000);
             		   $cfgg->set("Çaylak MadenciDurum", "Tamamlandı");
             
             	$cfgg->set("Çaylak Madenci", true);
      		$cfgg->save();
      		
 		
if($cfgg->get("Aktiflik") == true){
  		
if($cfgg->get("Yeni Başlayan") == true){
  		
if($cfgg->get("Çaylak Oduncu") == true){
  		
if($cfgg->get("Çaylak Madenci") == true){
          $cfgg->set("Seviye", 1);
         $cfgg->save();
           }
            }
            }
            }
      	}else{
      		$o->sendMessage("§cGörev Gereksinimleri Karşılanamadı!");
      	}
      }else{
      	$o->sendMessage("§cGörevi Zaten Yapmışsın!");
      }
     }); 
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
     $hedef = 60;
    $sure = $cfg->get("Çaylak Madenci Sayı");
    $para = EconomyAPI::getInstance()->myMoney($o);
      $sil = $hedef - (int) $cfg->get("Dakika");
    $f->setTitle("Görev");
    $f->setContent("§5Adanda Toplam 150 Kırıktaş Kır\n\n\n§9Ödül: §6150.000TL\n\nGörev Durumu 150/$sure");
    $f->addButton("§eÖdülü al");
    $f->sendToPlayer($o);
}
public function caylakOduncu(Player $o){
     $f = new SimpleForm(function(Player $o, $args){
    if($args === null){
    return true;
     }
        $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
           if($cfgg->get("Çaylak Oduncu") == false){
      	if($cfgg->get("Çaylak Oduncu Sayı") >= 150){
      		$o->sendMessage("§aÖdül Envanterine/hesabına Aktarıldı!");
      		EconomyAPI::getInstance()->addMoney($o, 150000);
      		   $cfgg->set("Çaylak Oduncu Durum", "Tamamlandı");
      			$cfgg->set("Çaylak Oduncu", true);
      		$cfgg->save();
      		
 		
if($cfgg->get("Aktiflik") == true){
  		
if($cfgg->get("Yeni Başlayan") == true){
  		
if($cfgg->get("Çaylak Oduncu") == true){
  		
if($cfgg->get("Çaylak Madenci") == true){
          $cfgg->set("Seviye", 1);
         $cfgg->save();
           }
            }
            }
            }
      	}else{
      		$o->sendMessage("§cGörev Gereksinimleri Karşılanamadı!");
      	}
      }else{
      	$o->sendMessage("§cGörevi Zaten Yapmışsın!");
      }
     }); 
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
     $hedef = 60;
    $sure = $cfg->get("Yeni Başlayan Sayı");
    $para = EconomyAPI::getInstance()->myMoney($o);
      $sil = $hedef - (int) $cfg->get("Dakika");
    $f->setTitle("Görev");
    $f->setContent("§5Adanda 150 Meşe Ağacı Kır\n\n\n§9Ödül: §6150.000TL\n\nGörev Durumu 150/$sure");
    $f->addButton("§eÖdülü al");
    $f->sendToPlayer($o);
}
public function aktiflik(Player $o){
     $f = new SimpleForm(function(Player $o, $args){
    if($args === null){
    return true;
     }

                $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
                        $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
        
        
        
                  if($cfgg->get("Aktiflik") == false){
      	if($cfg->get("Dakika") >= 30){
      		$o->sendMessage("§aÖdül Envanterine/hesabına Aktarıldı!");
      		EconomyAPI::getInstance()->addMoney($o, 50000);
      		   $cfgg->set("Aktiflik Durum", "Tamamlandı");
      			$cfgg->set("Aktiflik", true);
 
               
      		$cfgg->save();
      		
if($cfgg->get("Aktiflik") == true){
  		
if($cfgg->get("Yeni Başlayan") == true){
  		
if($cfgg->get("Çaylak Oduncu") == true){
  		
if($cfgg->get("Çaylak Madenci") == true){
          $cfgg->set("Seviye", 1);
         $cfgg->save();
           }
            }
            }
            }
      	}else{
      		$o->sendMessage("§cGörev Gereksinimleri Karşılanamadı!");
      	}
      }else{
      	$o->sendMessage("§cGörevi Zaten Yapmışsın!");
      }
        
      
      }); 
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
     $hedef = 60;
    $sure = $cfg->get("Dakika");
    $para = EconomyAPI::getInstance()->myMoney($o);
      $sil = $hedef - (int) $cfg->get("Dakika");
    $f->setTitle("Görev");
    $f->setContent("§5Sunucuda Toplam 30 Dakika Aktif Kal\n\n\n§9Ödül: §650.000TL\n\n 30/ $sure");
    $f->addButton("§eÖdülü al");
    $f->sendToPlayer($o);
}
public function ustaAktiflik(Player $o){
     $f = new SimpleForm(function(Player $o, $args){
    if($args === null){
    return true;
     }

                $cfgg = new Config($this->getDataFolder()."Missions/".$o->getName().".yml", Config::YAML);
                        $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
        
        
        
                  if($cfgg->get("Usta Aktiflik") == false){
      	if($cfg->get("Dakika") >= 350){
      		$o->sendMessage("§aÖdül Envanterine/hesabına Aktarıldı!");
      		EconomyAPI::getInstance()->addMoney($o, 5000000);
      		   $cfgg->set("Usta Aktiflik Durum", "Tamamlandı");
      			$cfgg->set("Usta Aktiflik", true);
 
               
      		$cfgg->save();
      	}else{
      		$o->sendMessage("§cGörev Gereksinimleri Karşılanamadı!");
      	}
      }else{
      	$o->sendMessage("§cGörevi Zaten Yapmışsın!");
      }
        
      
      }); 
    $cfg = new Config($this->getDataFolder()."Sure/".$o->getName().".yml", Config::YAML);
     $hedef = 60;
    $sure = $cfg->get("Dakika");
    $para = EconomyAPI::getInstance()->myMoney($o);
      $sil = $hedef - (int) $cfg->get("Dakika");
    $f->setTitle("Görev");
    $f->setContent("§5Sunucuda Toplam 350 Dakika Aktif Kal\n\n\n§9Ödül: §65.000.000TL\n\n 350/ $sure");
    $f->addButton("§eÖdülü al");
    $f->sendToPlayer($o);
}
  public function blokKoy(BlockPlaceEvent $e){
  	if($e->getItem()->getId() == 9){
  		$cfgg = new Config($this->getDataFolder()."Missions/".$e->getPlayer()->getName().".yml", Config::YAML);
  		if($cfgg->get("Yeni Başlayan") == false){
  			if($cfgg->get("Yeni Başlayan Sayı") == 3){
  				$e->getPlayer()->sendMessage("§a> Yeni Başlayan Görevi Tamamlandı /gorev den ödüllerini alabilirsin!");
   // $cfgg->
    $cfgg->set("Yeni Başlayan Durum", "§aTamamlandı");
$cfgg->save();
  			}else{
$cfgg->set("Yeni Başlayan Sayı", $cfgg->get("Yeni Başlayan Sayı") +1);
  			$cfgg->save();
  			}
  		 }
  	}
  }
  public function blockkir(BlockBreakEvent $e){
        $cfgg = new Config($this->getDataFolder()."Missions/".$e->getPlayer()->getName().".yml", Config::YAML);
      if($e->getBlock()->getId() == 4){
  if($cfgg->get("Çaylak Madenci") == false){
  if($cfgg->get("Kırıktaş") == 150){
  $e->getPlayer()->sendMessage("§a> Çaylak Madenci Görevin Tamamlandı /gorevden ödüllerini alabilirsin!");
    $cfgg->set("Çaylak Madenci Durum", "§aTamamlandı");
  $cfgg->save();
   }
}
   $cfgg->set("Çaylak Madenci Sayı", $cfgg->get("Çaylak Madenci Sayı") +1);
   $cfgg->set("Kırıktaş", $cfgg->get("Kırıktaş") +1);
  $cfgg->save();
}
  if($e->getBlock()->getId() == 17){
  if($cfgg->get("Çaylak Oduncu") == false){
  if($cfgg->get("Çaylak Oduncu Sayı") == 150){
   $e->getPlayer()->sendMessage("§a Çaylak Oduncu Görevin Tamamlandı /gorevden ödüllerini alabilirsin!");
    $cfgg->set("Çaylak Oduncu Durum", "§aTamamlandı");
  $cfgg->save(); 
   }
}
   $cfgg->set("Çaylak Oduncu Sayı", $cfgg->get("Çaylak Oduncu Sayı") +1);
   $cfgg->set("Odun", $cfgg->get("Odun") +1);
  $cfgg->save();
  }
  }
}
