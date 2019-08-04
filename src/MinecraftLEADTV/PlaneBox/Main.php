<?php

declare(strict_types=1);

namespace MinecraftLEADTV\PlaneBox;

use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\utils;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemFactory;
use pocketmine\Server;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\scheduler\Task;

class Main extends PluginBase implements Listener {

	public function onEnable() : void{
		@mkdir ( $this->getDataFolder () );
		$this->settingdb = new Config ( $this->getDataFolder () . "setting.yml", Config::YAML, [
		"box" => [],
		"lang" => "kor",
		"lang-kor" => [],
		"lang-eng" => []
		]);
		$this->setting = $this->settingdb->getAll ();
		$this->settingdb->setAll ( $this->setting );
		$this->settingdb->save ();
		
		$this->setting["lang-kor"]["enable-msg"] = "PlaneBox 플러그인 활성화";
		$this->setting["lang-kor"]["disable-msg"] = "PlaneBox 플러그인 비활성화";
		$this->setting["lang-kor"]["command-help"] = array(
														"§a/pb 박스추가 <박스 이름> §f| §aPlaneBox 박스 추가",
														"§a/pb 박스삭제 <박스 이름> §f| §aPlaneBox 박스 삭제",
														"§a/pb 박스목록 <박스 이름> §f| §aPlaneBox 박스 목록",
														"§a/pb 박스타임 <박스 이름> <시간(초)> §f| §aPlaneBox 박스 드롭 타임 설정",
														"§a/pb 박스설정 <박스 이름> §f| §a손에 든 아이템을 해당 박스에 드롭 아이템 으로 설정 합니다."
													);
		$this->save();
		$this->playerdb = new Config ( $this->getDataFolder () . "players.yml", Config::YAML );
		$this->pldb = $this->playerdb->getAll ();
		$this->playerdb->setAll ( $this->pldb );
		$this->playerdb->save ();
		
		$this->getLogger()->info("PlaneBox 플러그인 활성화");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function save()
	{
		$this->settingdb->setAll ( $this->setting );
		$this->settingdb->save ();
		$this->playerdb->setAll ( $this->pldb );
		$this->playerdb->save ();
	}
	
	public function onLoad() {
		date_default_timezone_set("Asia/Seoul");
		$this->getLogger()->notice("콘솔의 시간대를 UTC +9(서울)로 지정하였습니다.");
	}
	public function onDisable() : void{
		$this->save();
		$this->getLogger()->info("비활성화");
	}
}
