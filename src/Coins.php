<?php

namespace src;

use src\Coin;

class Coins
{
    public readonly array $values;

    private function __construct($values) {
        $this->values = $values;
    }

    public static function fromArray($coinArray)
    {
        /** @var Coin[] */
        $coins = [];
        foreach ($coinArray as $key => $value) {
            for ($i = 0; $i < $value; $i++) {
                $coins[] = Coin::fromAmount($key);
            }
        }
        return new Coins($coins);
    }

    private static function fromNumbers($fiveHundredNum, $hundredNum, $fiftyNum, $tenNum)
    {
        /** @var Coin[] */
        $coins = [];
        for ($i = 0; $i < $fiveHundredNum; $i++) {
            $coins[] = Coin::FIVE_HUNDRED;
        }
        for ($i = 0; $i < $hundredNum; $i++) {
            $coins[] = Coin::HUNDRED;
        }
        for ($i = 0; $i < $fiftyNum; $i++) {
            $coins[] = Coin::FIFTY;
        }
        for ($i = 0; $i < $tenNum; $i++) {
            $coins[] = Coin::TEN;
        }
        return new Coins($coins);
    }

    public static function empty()
    {
        return new Coins([]);
    }

    public function add(Coins $coins): Coins
    {
        $result = [];
        foreach ($this->values as $coin) {
            $result[] = $coin;
        }
        foreach ($coins->values as $coin) {
            $result[] = $coin;
        }
        return new Coins($result);
    }

    public function subtract(Coins $coins): Coins
    {
        $fiveHundredNum = $this->filter(Coin::FIVE_HUNDRED->name)->length() - $coins->filter(Coin::FIVE_HUNDRED->name)->length();
        $hundredNum = $this->filter(Coin::HUNDRED->name)->length() - $coins->filter(Coin::HUNDRED->name)->length();
        $fiftyNum = $this->filter(Coin::FIFTY->name)->length() - $coins->filter(Coin::FIFTY->name)->length();
        $tenNum = $this->filter(Coin::TEN->name)->length() - $coins->filter(Coin::TEN->name)->length();
        return self::fromNumbers($fiveHundredNum, $hundredNum, $fiftyNum, $tenNum);
    }

    public function calculateNoChargeCombination(int $amount): Coins|false
    {
        foreach ($this->getAllCombinations() as $coins) {
            if ($coins->amount() === $amount) {
                return $coins;
            }
        }
        return false;
    }

    public function amount(): int
    {
        return array_reduce($this->values, function(int $carry, Coin $coin) {
            return $carry + $coin->value;
        }, 0);
    }

    public static function fromValue(int $_value): Coins
    {
        $value = $_value;
        $coinArray = [];
        $count = 100;
        while ($value !== 0 && $count-- > 0) {
            if ($value >= Coin::FIVE_HUNDRED->value) {
                $coinArray[] = Coin::FIVE_HUNDRED;
                $value -= Coin::FIVE_HUNDRED->value;
                continue;
            }
            if ($value >= Coin::HUNDRED->value) {
                $coinArray[] = Coin::HUNDRED;
                $value -= Coin::HUNDRED->value;
                continue;
            }
            if ($value >= Coin::FIFTY->value) {
                $coinArray[] = Coin::FIFTY;
                $value -= Coin::FIFTY->value;
                continue;
            }
            if ($value >= Coin::TEN->value) {
                $coinArray[] = Coin::TEN;
                $value -= Coin::TEN->value;
                continue;
            }
        }
        return new Coins($coinArray);
    }

    public function toString(): string
    {
        if (empty($this->values)){
            return "nochange";
        }

        $result = [];
        foreach ($this->values as $coin) {
            $key = strVal($coin->value);
            if (!array_key_exists($key, $result)){
                $result[strVal($key)] = 0;
            }
            $result[$key]++;
        }

        krsort($result);
        return implode(" ", array_map(function($key, $value) {
            return $key . " " . $value;
        }, array_keys($result), $result));
    }

    private function filter(string $name): Coins
    {
        $result = [];
        /** @var Coin */
        foreach ($this->values as $coin) {
            if ($coin->name == $name) {
                $result[] = $coin;
            }
        }
        return new Coins($result);
    }

    private function length(): int
    {
        return count($this->values);
    }

    private function getAllCombinations(): array
    {
        /** @var Coins[] */
        $results = [Coins::empty()];
    
        /** @var Coin */
        foreach ($this->values as $coin){
            foreach ($results as $result){ 
                $coins = [$coin, ...$result->values];
                $results[] = new Coins($coins);
            }
        }
        array_shift($results);
        return array_values($results);
    }
}
