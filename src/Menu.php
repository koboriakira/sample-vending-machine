<?php

namespace src;

enum Menu: int
{
    case COLA = 120;
    case COFFEE = 150;
    case ENERGY_DRINK = 210;

    public static function fromName($name): static
    {
        switch (strtoupper($name)) {
            case Menu::COLA->name:
                return Menu::COLA;
            // no break
            case Menu::COFFEE->name:
                return Menu::COFFEE;
            case Menu::ENERGY_DRINK->name:
                return Menu::ENERGY_DRINK;
            default:
                throw new \Exception("不正な引数");
        }
    }
}
