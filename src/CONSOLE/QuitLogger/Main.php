<?php
namespace CONSOLE\QuitLogger ; 
use pocketmine\command\{CommandSender,Command};
use pocketmine\{Server,Player};

class Main extends \pocketmine\plugin\PluginBase implements \pocketmine\event\Listener
{
	public function onEnable()
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		date_default_timezone_set("America/New_York");
		$this->onLog();
		$this->onregisterconfig();
	}

	public function QuitEvent(\pocketmine\event\player\PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();
		$this->arraysetConfig($player);
		$this->quitlog($player);
		/*NOTE:
		["X" => $x, "Y" => $y, "Z" => $z]
		*/
	}

	public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args)
	{
		$name = $issuer->getName();
		switch ($cmd->getName()) {
			case 'quitlogger':
			if (empty($args[0]))
			{
				$issuer->sendMessage("§aInfo §1§l> §r§e/quitlogger <name>");
				return;
			}
			$username = $args[0];
			if (!isset($args[1]))
			{
				if ($this->Players->exists($username)) {
					$PlayerName = $this->Players->get($username);
					$x = $PlayerName["X"];
					$y = $PlayerName["Y"];
					$z = $PlayerName["Z"];
					$date = $PlayerName["quitdate"];
					$issuer->sendMessage("§7".$username . "§r is \n{$date} \n  §bX§3:§f".$x."\n  §bY§3:§f".$y."\n  §bZ§3:§f".$z."\n§7Left.§r");
				}else{
					$issuer->sendMessage("§dPlayer not found");
				}
			}
		}
	}






//////////////////////////////////////////////////////////////////////////////////

public function onLog()
{
	$logger = $this->getServer()->getLogger();

	$logger->warning("Plugin Enable. by yoruzora");
}

public function onregisterconfig()
{
	if(!file_exists($this->getDataFolder())){
		mkdir($this->getDataFolder(), 0744, true);
	}
	$this->Players = new \pocketmine\utils\Config($this->getDataFolder() . "Players.yml", \pocketmine\utils\Config::YAML);
}


public function arraysetConfig($player)
{
	$x = $player->getX();
	$y = $player->getY();
	$z = $player->getZ();
	$name = $player->getName();
	//$date = date("Y年/n月/j日/A:h:s");
	$date_en = date("n/j/Y/A:h:s");
	$array1 = array("X" => $x);
	$array2 = array("Y" => $y);
	$array3 = array("Z" => $z);
	$array4 = array("quitdate" => $date);
	$merge = array_merge($array1,$array2,$array3,$array4);
		$this->Players->set($name,$merge);
		$this->saveConfig();
}


public function saveConfig()
{
	$this->Players->save();
}

public function quitlog($player)
{
	$name = $player->getName();
	$x = $player->getX();
	$y = $player->getY();
	$z = $player->getZ();

	$this->getServer()->getLogger()->info($name." is ". $x .", ". $y .", ". $z ."Left");
}
}