<?php

/*
 * BanCID - A PocketMine-MP plugin to manage device/client ID bans
 * Copyright (C) 2017 Kevin Andrews <https://github.com/kenygamer/BanCID>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

declare(strict_types=1);

namespace BanCID;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\plugin\PluginBase;

class BanCID extends PluginBase implements Listener{
    /** @var BanManager */
    private $banManager;
    
    public function onEnable() : void{
        if(!is_dir($this->getDataFolder())){
            @mkdir($this->getDataFolder());
        }
        $this->saveDefaultConfig();
        if(!$this->banManager instanceof BanManager){
            $this->banManager = new BanManager($this);
        }
        $this->registerCommands();
        if($this->getConfig()->get("unregister-ban-command", true)){
            $this->unregisterBanCommand();
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    private function registerCommands() : void{
        $map = $this->getServer()->getCommandMap();
        $commands = [
            "bancid" => "\\BanCID\\command\\BanCIDCommand",
            "unbancid" => "\\BanCID\\command\\UnbanCIDCommand",
            "bancidlist" => "\\BanCID\\command\\BanCIDListCommand"
            ];
        foreach($commands as $cmd => $class){
            $map->register("bancid", new $class($this));
        }
    }
    
    private function unregisterBanCommand() : void{
        $map = $this->getServer()->getCommandMap();
        foreach(["ban", "banlist"] as $cmd){
            $map->unregister($map->getCommand($cmd));
        }
    }
    
    /**
     * @param PlayerPreLoginEvent $event
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event) : void{
        $player = $event->getPlayer();
        if($this->getBanManager()->isBanned($player)){
            $banReason = $this->getBanManager()->getBanReason($player);
            if(empty($banReason)){
                $player->kick("You are banned.", false);
            }else{
                $player->kick("You are banned. Reason: " . $banReason, false);
            }
        }
    }
    
    /**
     * @return BanManager
     */
    public function getBanManager() : BanManager{
        return $this->banManager;
    }
    
}
