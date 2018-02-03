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

use pocketmine\Player;
use pocketmine\utils\Config;

use BanCID\BanCID;

class BanManager{
    /** @var Config */
    private $bans;
    /** @var BanCID */
    private $plugin;
  
    /**
     * @param BanCID $plugin
     */
    public function __construct(BanCID $plugin){
       $this->bans = new Config($plugin->getServer()->getDataPath() . "banned-cids.yml", Config::YAML);
       $this->plugin = $plugin;
    }
    
    /**
     * @param string|Player $player 
     *
     * @return bool
     */
    public function isBanned($player) : bool{
        // Prefer to pass a Player parameter for players UUID check.
        if($player instanceof Player){
            return
                $this->bans->exists(strtolower($player->getName())) ||
                $this->getBanClientId($player) === (string)$player->getUniqueId();
        }
        return $this->bans->exists(strtolower($player));
    }
  
    /**
     * @param Player $player
     * @param string $banReason
     *
     * @return bool
     */
    public function ban(Player $player, string $banReason = "") : bool{
        if($this->isBanned($player)){
            return false;
        }
        $this->bans->setNested(strtolower($player->getName()) . ".cid", (string)$player->getClientId());
        $this->bans->setNested(strtolower($player->getName()) . ".reason", (string)$banReason);
        $this->bans->save();
        if(empty($banReason)){
            $player->kick("You are banned.", false);
        }else{
            $player->kick("You are banned. Reason: " . $banReason, false);
        }
        return true;
    }
    
    /**
     * @param string|Player $player
     *
     * @return bool
     */
    public function unban($player) : bool{
        if($player instanceof Player){
            $player = $player->getName();
        }
        if(!$this->isBanned($player)){
            return false;
        }
        $this->bans->remove(strtolower($player));
        $this->bans->save();
        return true;
    }
    /**
     * @param string|Player $player
     *
     * @return null|string
     */
    public function getBanClientId($player) : ?string{
        if($player instanceof Player){
            $player = $player->getName();
        }
        //BanManager->isBanned() check removed due to loop
        return $this->bans->getNested(strtolower($player) . ".cid");
    }
    
    /**
     * @param string|Player $player
     *
     * @return null|string
     */
    public function getBanReason($player) : ?string{
        if($player instanceof Player){
            $player = $player->getName();
        }
        if(!$this->isBanned($player)){
            return null;
        }
        return $this->bans->getNested(strtolower($player) . ".reason");
    }
    
    /**
     * @return array
     */
    public function getAllBans() : array{
        return $this->bans->getAll();
    }
}
