# BanCID
![](http://isitmaintained.com/badge/resolution/kenygamer/BanCID.svg)
![](https://img.shields.io/github/release/kenygamer/BanCID/all.svg)
![](https://img.shields.io/github/downloads/kenygamer/BanCID/total.svg)

BanCID is a PocketMine-MP plugin that allows you to manage device/client ID bans. Ensures your players don't try to create subaccounts to join your server.
```
/ban-ip + /bancid (/ban + UUID ban) = perfect match
```
## Commands
| Command | Usage | Description |
| ------- | ----- | ----------- |
| `/bancid` | `/bancid <player> [reason]` | Bans a player client ID. |
| `/unbancid` | `/unbancid <player>` | Unbans a player client ID. |
## Permissions
```yml
bancid.command:
 description: Allows access to all BanCID commands.
  default: false
  children:
   bancid.command.bancid:
    description: Allows access to the BanCID bancid command.
    default: op
   bancid.command.unbancid:
    description: Allows access to the BanCID unbancid command.
    default: op
```
## API
See [BanCID\BanManager](https://github.com/kenygamer/BanCID/blob/master/src/BanCID/BanManager.php)
