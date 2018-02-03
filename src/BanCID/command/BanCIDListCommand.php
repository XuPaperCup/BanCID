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

namespace BanCID\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use BanCID\BanCID;

class BanCIDListCommand extends Command{
    /** @var BanCID */
    private $plugin;
  
    /**
     * @param BanCID $plugin
     */
    public function __construct(BanCID $plugin){
        parent::__construct("bancidlist", "View all players banned from this server", "/bancidlist");
        $this->setPermission("bancid.command.bancidlist");
        $this->plugin = $plugin;
    }
    
    /**
     * @param CommandSender $sender
     * @param string $label
     * @param array $args
     *
     * @return bool
     */
    public function execute(CommandSender $sender, string $label, array $args) : bool{
        if(!$this->testPermission($sender)) return false;
        if(!empty($args)){
            $sender->sendMessage("Usage: " . $this->getUsage());
            return true;
        }
        $bannedList = $this->plugin->getBanManager()->getAllBans();
        $bannedPlayers = [];
        foreach($bannedList as $bannedPlayer => $banInfo){
            $bannedPlayers[] = $bannedPlayer;
        }
        $sender->sendMessage("There are " . count($bannedPlayers) . " total banned players:");
        $sender->sendMessage(implode(", ", $bannedPlayers));
        return true;
    }
    
}
