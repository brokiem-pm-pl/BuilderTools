<?php

/**
 * Copyright (C) 2018-2021  CzechPMDevs
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace czechpmdevs\buildertools\commands;

use czechpmdevs\buildertools\BuilderTools;
use czechpmdevs\buildertools\editors\Copier;
use czechpmdevs\buildertools\Selectors;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class CutCommand extends BuilderToolsCommand {

    public function __construct() {
        parent::__construct("/cut", "Cut selected area", null, []);
    }

    /** @noinspection PhpUnused */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$this->testPermission($sender)) {
            return;
        }
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can be used only in game!");
            return;
        }
        if (!Selectors::isSelected(1, $sender)) {
            $sender->sendMessage(BuilderTools::getPrefix() . "§cFirst you need to select the first position.");
            return;
        }
        if (!Selectors::isSelected(2, $sender)) {
            $sender->sendMessage(BuilderTools::getPrefix() . "§cFirst you need to select the second position.");
            return;
        }

        /** @var Vector3 $pos1 */
        $pos1 = Selectors::getPosition($sender, 1);
        /** @var Vector3 $pos2 */
        $pos2 = Selectors::getPosition($sender, 2);

        $result = Copier::getInstance()->cut($pos1, $pos2, $sender);
        if(!$result->successful()) {
            $sender->sendMessage(BuilderTools::getPrefix() . "§cError while processing the command: {$result->getErrorMessage()}");
            return;
        }

        $sender->sendMessage(BuilderTools::getPrefix()."§a{$result->getBlocksChanged()} blocks were cut out (Took {$result->getProcessTime()} seconds)!");
    }
}