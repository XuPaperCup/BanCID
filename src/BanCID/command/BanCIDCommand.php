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
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use BanCID\BanCID;

class BanCIDCommand extends Command{
    /** @var BanCID */
    private $plugin;
  
    /**
     * @param BanCID $plugin
     */
    public function __construct(BanCID $plugin){
        parent::__construct("bancid", "Prevents the specified player from using this server", "/bancid <player> [reason]");
        $this->setPermission("bancid.command.bancid");
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
        if(count($args) < 1){
            $sender->sendMessage("Usage: " . $this->getUsage());
            return true;
        }
        $name = strtolower($args[0]);
        $player = $this->plugin->getServer()->getPlayer($name);
        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Player " . $name . " is not online.");
            return true;
        }
        $banManager = $this->plugin->getBanManager();
        if($banManager->isBanned($player)){
            $sender->sendMessage(TextFormat::RED . "Player " . $name . " is already banned.");
            return true;
        }
        $reason = "";
        if(isset($args[1])){
            unset($args[0]);
            $reason = $args;
        }
        $banManager->ban($player, implode(" ", $reason));
        $sender->sendMessage("Banned player " . $name);
        return true;
    }
    
}
