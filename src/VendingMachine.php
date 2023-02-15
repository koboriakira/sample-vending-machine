<?php

namespace src;

use src\Coins;

class VendingMachine
{
  private Coins $inputedCoins;

  public function __construct()
  {
    $this->inputedCoins = Coins::empty();
  }

  public function inputCoins(Coins $coins)
  {
    $this->inputedCoins = $this->inputedCoins->add($coins);
  }

  public function inputMenu(Menu $menu): Coins
  {
    // 金額計算
    $changeValue = $this->inputedCoins->amount() - $menu->value;
    $change = Coins::fromValue($changeValue);
    return $change;
  }
}