<?php

namespace Terpz710\CustomDeathMessage;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Monster;
use pocketmine\Player;
use pocketmine\entity\Projectile;

class CustomDeathMessage extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }

    public function onPlayerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();

        if ($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
            if ($killer instanceof Player) {
                
                $deathMessage = str_replace("{victim}", $player->getName(), $this->getConfig()->get("player_killed_by_player_message"));
                $deathMessage = str_replace("{killer}", $killer->getName(), $deathMessage);
            } else if ($killer instanceof Projectile && $killer->getOwningEntity() instanceof Player) {
                
                $owner = $killer->getOwningEntity();
                $deathMessage = str_replace("{victim}", $player->getName(), $this->getConfig()->get("player_shot_by_projectile_message"));
                $deathMessage = str_replace("{shooter}", $owner->getName(), $deathMessage);
            } else if ($killer instanceof Monster) {
                
                $deathMessage = str_replace("{victim}", $player->getName(), $this->getConfig()->get("player_killed_by_mob_message"));
                $deathMessage = str_replace("{mob}", $killer->getName(), $deathMessage);
            }
        }

        if (!isset($deathMessage)) {
            
            $deathMessage = str_replace("{victim}", $player->getName(), $this->getConfig()->get("player_died_message"));
        }

        $event->setDeathMessage($deathMessage);
    }

}
