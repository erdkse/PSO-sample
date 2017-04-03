<?php


class PSO
{
    private $minValue;
    private $maxValue;
    private $swarmMember;
    private $swarmSize;
    ////////////////////////////////////
    private $w;///eylemsizlik katsayısı
    private $c1;//Bilişsel katsayı
    private $c2;//Sosyal katsayı
    private $iteration;//Iterasyon katsayı
    ////////////////////////////////////
    private $swarm;
    private $obj;
    private $particlesBestValues;//obj-parçaçıkların en iyi değerleri swarmSize kadar array
    private $particlesBestPositions;//Parçacıkların bulundukları en iyi nokta-başlangıç için kendi değerleri swarm matrixi
    private $swarmBestValue;//En iyi grubun çözüm değeri
    private $swarmBestPosition;//En iyi grub kümesi
    private $particleVelocities;
    //////////////////////////////
    private $objIT;

    function __construct()
    {
        $this->minValue = 1.01;
        $this->maxValue = 9.99;
        $this->swarmMember = 5;
        $this->swarmSize = 10;
        $this->w = 0.6;
        $this->c1 = 2;
        $this->c2 = 2;
        $this->iteration = 10;
        $this->swarm = array();
        $this->obj = array();
        $this->particlesBestValues = array();
        $this->particlesBestPositions = array();
        $this->swarmBestValue = null;
        $this->swarmBestPosition = null;
        $this->particleVelocities = array();
    }

    public function calculate()
    {
        $this->swarm = $this->createSwarm($this->minValue, $this->maxValue, $this->swarmMember, $this->swarmSize);
        $this->particlesBestPositions = $this->swarm;
        $this->particleVelocities = $this->createParticleVelocities($this->swarmMember, $this->swarmSize);
        $this->obj = $this->createObj($this->swarmSize);
        //$this->echoHTML("KÜME", $this->swarm);
        $this->obj = $this->findParticleBestValues($this->swarm);
        $this->particlesBestValues = $this->obj;
        //$this->echoHTML("KÜME EN İYİ SONUÇ", $this->particlesBestValues);
        $this->findSwarmBestValue($this->obj);
        //echo "<h3>EN İYİ Sonuç</h3><hr><p>Sonuç : " . $this->swarmBestValue . "</p>";
        //$this->echoHTML("EN İYİ SÜRÜ", $this->swarmBestPosition);
        ////////////////////////////////////////////////////////////////////
        $this->objIT[0] = array($this->swarmBestValue);
        $iterationCount = 1;
        while ($iterationCount <= $this->iteration) {
            $this->particleVelocities = $this->updateParticalVelocities($this->particleVelocities, $this->particlesBestPositions
                , $this->swarm, $this->swarmBestPosition);
            //$this->echoHTML("HIZ BELİRLEME", $this->particleVelocities);
            $this->swarm = $this->moveParticals($this->swarm, $this->particleVelocities);
            //$this->echoHTML("HAREKET", $this->swarm);
            $this->obj = $this->findParticleBestValues($this->swarm);
            $this->updateParticleBestValues($this->obj, $this->particlesBestValues);
            $this->updateSwarmBestValues($this->obj);
            $this->objIT[$iterationCount] = $this->swarmBestValue;
            $iterationCount++;
        }
        echo json_encode($this->objIT);
    }

    private function createSwarm($min, $max, $swarmMember, $swarmSize)
    {
        $swarm = array();
        for ($i = 0; $i < $swarmSize; $i++) {
            for ($j = 0; $j < $swarmMember; $j++) {
                $swarm[$i][$j] = mt_rand($min * 100, $max * 100) / 100;
            }
        }
        return $swarm;
    }

    private function createParticleVelocities($swarmMember, $swarmSize)
    {
        $particleVelocities = array();
        for ($i = 0; $i < $swarmSize; $i++) {
            for ($j = 0; $j < $swarmMember; $j++) {
                $particleVelocities[$i][$j] = 0;
            }
        }
        return $particleVelocities;
    }

    private function createObj($swarmSize)
    {
        $obj = array();
        for ($i = 0; $i < $swarmSize; $i++) {
            $obj[$i] = 0;
        }
        return $obj;
    }

    //////////////////////////////

    private function findParticleBestValues($swarm)
    {
        $particleBestValueArray = array();
        for ($i = 0; $i < count($swarm); $i++) {
            for ($j = 0; $j < count($swarm[$i]); $j++) {
                $tempResult = $this->problemFunction($swarm[$i][$j]);
                if (!isset($particleBestValueArray[$i])) {
                    $particleBestValueArray[$i] = $tempResult;
                } else {
                    if ($tempResult > $particleBestValueArray[$i]) {
                        $particleBestValueArray[$i] = $tempResult;
                    }
                }
            }
        }

        return $particleBestValueArray;
    }

    private function findSwarmBestValue($obj)
    {
        $tempSwarmBestValue = null;
        $tempSwarmBestPosition = null;
        for ($i = 0; $i < count($obj); $i++) {
            if (is_null($tempSwarmBestValue)) {
                $tempSwarmBestValue = $obj[$i];
                $tempSwarmBestPosition = $i;
            } else {
                if ($obj[$i] > $tempSwarmBestValue) {
                    $tempSwarmBestValue = $obj[$i];
                    $tempSwarmBestPosition = $i;
                }
            }
        }
        $this->swarmBestValue = $tempSwarmBestValue;
        $this->swarmBestPosition = $this->swarm[$tempSwarmBestPosition];
    }

    private function updateParticalVelocities($particleVelocities, $particleBestPosition, $swarm, $swarmBestPosition)
    {
        $resultArray = array();
        for ($i = 0; $i < count($particleVelocities); $i++) {
            for ($j = 0; $j < count($particleVelocities[$i]); $j++) {
                $resultArray[$i][$j] = ($this->w * $particleVelocities[$i][$j]) +
                    ($this->c1 * (rand(0, 100) / 100) * ($particleBestPosition[$i][$j] - $swarm[$i][$j])) +
                    ($this->c2 * (rand(0, 100) / 100) * ($swarmBestPosition[$j] - $swarm[$i][$j]));
            }
        }

        return $this->fixParticleVelocitiesWithMaxVelocity($resultArray);
    }

    private function fixParticleVelocitiesWithMaxVelocity($particleVelocities)
    {
        $maxVelocity = ($this->maxValue - $this->minValue) / 2;

        for ($i = 0; $i < count($particleVelocities); $i++) {
            for ($j = 0; $j < count($particleVelocities[$i]); $j++) {
                if ($particleVelocities[$i][$j] > $maxVelocity) {
                    $particleVelocities[$i][$j] = $maxVelocity;
                } else if ($particleVelocities[$i][$j] < ($maxVelocity * -1)) {
                    $particleVelocities[$i][$j] = $maxVelocity * -1;
                }
            }
        }
        return $particleVelocities;
    }

    private function moveParticals($swarm, $particleVelocities)
    {
        for ($i = 0; $i < count($particleVelocities); $i++) {
            for ($j = 0; $j < count($particleVelocities[$i]); $j++) {
                $swarm[$i][$j] = $swarm[$i][$j] + $particleVelocities[$i][$j];
            }
        }
        return $this->fixMoveParticals($swarm);
    }

    private function fixMoveParticals($swarm)
    {
        for ($i = 0; $i < count($swarm); $i++) {
            for ($j = 0; $j < count($swarm[$i]); $j++) {
                if ($swarm[$i][$j] > $this->maxValue) {
                    $swarm[$i][$j] = $this->maxValue;
                } else if ($swarm[$i][$j] < $this->minValue) {
                    $swarm[$i][$j] = $this->minValue;
                }
            }
        }
        return $swarm;
    }

    private function updateParticleBestValues($obj, $particalBestValues)
    {
        for ($i = 0; $i < count($obj); $i++) {
            if ($obj[$i] > $particalBestValues[$i]) {
                $this->particlesBestValues[$i] = $obj[$i];
                for ($j = 0; $j < count($this->swarm[$i]); $j++) {
                    $this->particlesBestPositions[$i][$j] = $this->swarm[$i][$j];
                }
            }
        }
    }

    private function updateSwarmBestValues($obj)
    {
        $tempSwarmBestValue = null;
        $tempSwarmBestPosition = null;
        for ($i = 0; $i < count($obj); $i++) {
            if (is_null($tempSwarmBestValue)) {
                $tempSwarmBestValue = $obj[$i];
                $tempSwarmBestPosition = $i;
            } else {
                if ($obj[$i] > $tempSwarmBestValue) {
                    $tempSwarmBestValue = $obj[$i];
                    $tempSwarmBestPosition = $i;
                }
            }
        }

        if ($tempSwarmBestValue > $this->swarmBestValue) {
            $this->swarmBestValue = $tempSwarmBestValue;
            $this->swarmBestPosition = $this->swarm[$tempSwarmBestPosition];
        }
    }

    private function problemFunction($x)
    {
        return cos(0.5 * $x) * sin(5.5 * $x);
    }

    private function echoHTML($title, $array)
    {
        echo "<h3>" . $title . "</h3><hr>";
        echo "<table>";
        for ($i = 0; $i < count($array); $i++) {
            echo "<tr>";
            if (is_array($array[$i])) {
                for ($j = 0; $j < count($array[$i]); $j++) {
                    echo "<td style='padding: 5px 10px;border-style: solid;border-width: 2px;'>" . $array[$i][$j] . "</td>";
                }
            } else {
                echo "<td style='padding: 5px 10px;border-style: solid;border-width: 2px;'>" . $array[$i] . "</td>";
            }

            echo "</tr>";
        }
        echo "</table>";
    }

}