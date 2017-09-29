<?php

/*
 * Copyright (C) 2017 allen
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Description of swap
 *
 * @author allen
 */
require_once("functions.php");

class swap {

    //put your code here
    public $conflictTeam;
    public $proposedSwapTeam;
    public $conflictTeamRank;
    public $swapTeamRank;
    
    public function __construct($conflictTeam, $swapTeam, $conflictTeamRank, $swapTeamRank){
        $this->conflictTeam = $conflictTeam;
        $this->swapTeam = $swapTeam;
        $this->conflictTeamRank = $conflictTeamRank;
        $this->swapTeamRank = $swapTeamRank;
    }

    function getRankDifference() {
        return abs($this->conflictTeamRank - $this->swapTeamRank);
    }

    function getRecordDifference() {
        $conflictTeamRecord = getStatisticalWins($this->conflictTeam);
        $swapTeamRecord = getStatisticalWins($this->swapTeam);
        return abs($conflictTeamRecord-$swapTeamRecord);
    }

    function getCSDifference() {
        $conflictTeamCS = getCS($this->conflictTeam);
        $swapTeamCS = getCS($this->swapTeam);
        return abs($conflictTeamCS-$swapTeamCS);
    }

    function getPDDifference() {
        $conflictTeamPD = getPD($this->conflictTeam);
        $swapTeamPD = getPD($this->swapTeam);
        return abs($conflictTeamPD-$swapTeamPD);
    }

    function getRankSum() {
        return $this->conflictTeamRank + $this->swapTeamRank;
    }

}
