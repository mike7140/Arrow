<?php
namespace Arrow;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\entity\Entity;
use pocketmine\event\player\PlayerInteractEvent;
class Main extends PluginBase implements Listener {
	public function onEnable() {
        	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
//今回は番外編ということで、矢を飛ばしたり雪を飛ばしたり...というコードを使ったプラグインです。
	public function onTouch(PlayerInteractEvent $event){//地面を触った時のeventです
		$player = $event->getPlayer();//player取得
		$nbt = new CompoundTag("", [
			"Pos" => new ListTag("Pos", [//Entityをスポーンさせる場所
				new DoubleTag("", $player->x),
				new DoubleTag("", $player->y + $player->getEyeHeight()),//$player->yだと足元の座標なので、目の高さ分を足します。
				new DoubleTag("", $player->z)
			]),
			"Motion" => new ListTag("Motion", [//entityの動き
				new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
				new DoubleTag("", 0),
				new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
			]),
			"Rotation" => new ListTag("Rotation", [//entityの方向
				new FloatTag("", $player->yaw),
				new FloatTag("", $player->pitch)
			]),
		]);

		$f = 1;//速度です。速いとダメージも上昇します。
		$arrow = Entity::createEntity("Arrow", $player->chunk, $nbt, $player);//ArrowをSnowballやPrimedTNTなどに変更できます。
		$arrow->setMotion($arrow->getMotion()->multiply($f));//entityの動く速さをセットします。
	}
}
