<?php

namespace App;

use Illuminate\Support\Collection;

class Board
{
    CONST BOARD_COLUMNS_NUMBER = 10;
    CONST BOARD_ROWS_NUMBER = 10;

    CONST RESULT_HIT = 'hit';
    CONST RESULT_MISS = 'miss';
    CONST RESULT_SUNKEN = 'sunken';

    CONST PLACE_FREE = 'free';
    CONST PLACE_MISS = 'miss';
    CONST PLACE_HIT = 'hit';

    CONST ORIENTATION_VERTICAL = 'v';
    CONST ORIENTATION_HORIZONTAL = 'h';

    public $table; //Table to store my shots
    public $ships; //My ships
    public $available_ships;

    public function __construct()
    {
        $this->available_ships = collect([
            new ship('carrier', 5),
            new ship('battleship', 4),
            new ship('cruiser', 3),
            new ship('submarine', 3),
            new ship('patrolboat', 2),
        ]);

        $this->ships = new Collection();

        $table = [];
        for ($i = 0; $i < self::BOARD_ROWS_NUMBER; $i++) {
            $row = [];
            for ($j = 0; $j < self::BOARD_ROWS_NUMBER; $j++) {
                $row[] = self::PLACE_FREE;
            }
            $table[] = $row;
        }
        $this->table = $table;
    }

    public function setShip($ship_name, $row, $col, $orientation)
    {
        $ship_index = $this->available_ships->search(function (Ship $ship) use ($ship_name) {
            return $ship->getName() == $ship_name;
        });

        $ship = $this->available_ships->slice($ship_index, 1)->first();
        $this->available_ships->forget($ship_index);

        //for reindex
        $this->available_ships = collect(array_values($this->available_ships->toArray()));

        $this->ships->push([
            'ship'             => $ship,
            'initial_position' => [
                'row' => $row,
                'col' => $col,
            ],
            'orientation'      => $orientation,
        ]);

        return true;
    }

    public function shot($row, $col)
    {
        if ($ship_in_table = $this->getShipIn($row, $col)) {
            $ship = $ship_in_table['ship'];
            $position = $ship_in_table['position'];

            $this->table[$row][$col] = self::PLACE_HIT;

            return $ship->shot($position);
        }

        $this->table[$row][$col] = self::PLACE_MISS;

        return self::RESULT_MISS;
    }

    /**************************
     * END OF PUBLIC INTERFAZ *
     **************************/

    private function getShipIn($row, $col)
    {
        foreach ($this->ships as $ship_in_table) {
            $ship = $ship_in_table['ship'];
            $initial_position = $ship_in_table['initial_position'];
            $orientation = $ship_in_table['orientation'];

            if ($orientation == self::ORIENTATION_HORIZONTAL) {
                if (
                    ($initial_position['row'] == $row) &&
                    ($initial_position['col'] <= $col && $col <= ($initial_position['col'] + $ship->getLength()))
                ) {
                    return [
                        'ship'     => $ship,
                        'position' => $col - $initial_position['col'] + 1,
                    ];
                }
            }
        }

        return false;
    }
}
