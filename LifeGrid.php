<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4.12.2015
 * Time: 17:58
 */
class LifeGrid
{
    /**
     * puvodni a vychozi matice
     * @var array
     */
    protected $initMatrix;

    /**
     * pomocna matice, ktera slouzi pro ulozeni noveho stavu
     * @var array
     */
    protected $newMatrix;

    /**
     * @var int
     */
    protected $maxLoop;

    /**
     * @var array
     */
    protected $initModels = array(
        'toad'      => 0,
        'beacon'    => 1,
        'blinker'   => 2,
    );

    /**
     * @var string
     */
    protected $modelName = NULL;



    /**
     * init
     */
    public function loader()
    {
        $i = 0;

        while ($i < $this->maxLoop) {
            if ($i == 0) {
                // vychozi stav matice
                $this->getInitMatrix();
                $this->show($this->initMatrix);
            } else {
                $this->getNewMatrix();
                $this->show($this->newMatrix);
            }

            $i++;
        }
    }


    /**
     * debug with var_dump
     * @param array $matrix
     */
    protected function show($matrix)
    {
        var_dump('--- BEGIN ---');
        var_dump($matrix);
        var_dump('--- END ---');
    }


    /**
     * inicializace vychozi matice
     * @return array
     */
    protected function getInitMatrix()
    {
        // 1 - alive
        // 0 - inanimate
        if ($this->modelName === NULL) {
            $this->modelName = array_search(mt_rand(0,2), $this->initModels);
        }

        switch ($this->modelName) {
            case 'toad':
                $this->initMatrix = array(
                    1 => array(1 => 0, 0, 0, 0, 0, 0),
                         array(1 => 0, 0, 0, 1, 0, 0),
                         array(1 => 0, 1, 0, 0, 1, 0),
                         array(1 => 0, 1, 0, 0, 1, 0),
                         array(1 => 0, 0, 1, 0, 0, 0),
                         array(1 => 0, 0, 0, 0, 0, 0),
                );
                break;
            case 'beacon':
                $this->initMatrix = array(
                    1 => array(1 => 0, 0, 0, 0, 0, 0),
                         array(1 => 0, 1, 1, 0, 0, 0),
                         array(1 => 0, 1, 1, 0, 0, 0),
                         array(1 => 0, 0, 0, 1, 1, 0),
                         array(1 => 0, 0, 0, 1, 1, 0),
                         array(1 => 0, 0, 0, 0, 0, 0),
                );
                break;
            case 'blinker':
                $this->initMatrix = array(
                    1 => array(1 => 0, 0, 0, 0, 0),
                         array(1 => 0, 0, 1, 0, 0),
                         array(1 => 0, 0, 1, 0, 0),
                         array(1 => 0, 0, 1, 0, 0),
                         array(1 => 0, 0, 0, 0, 0),
                );
                break;
        }

        return $this->initMatrix;
    }


    /**
     * vychozi model
     * @param string $modelName
     */
    public function setInitModel($modelName)
    {
        if (array_key_exists($modelName, $this->initModels)) {
            $this->modelName = $modelName;
        }
    }


    /**
     * meni stav matice
     * @return array
     */
    protected function getNewMatrix()
    {
        // vychozi stav matice ulozim do pomocne matice, nad kterou menim stav
        $this->newMatrix = $this->initMatrix;
        $countRows = count($this->initMatrix);

        for ($i = 1; $i <= $countRows; $i++) { // rows
            for ($j = 1; $j <= count($this->initMatrix[$i]); $j++) { // cols

                if ($this->getElementValue($i, $j) == 1) { // alive cell
                    $sum = $this->getCountAliveCells($i, $j);
                    if ($sum < 2) {
                        $this->newMatrix[$i][$j] = 0;
                    } elseif ($sum == 2 || $sum == 3) {
                        $this->newMatrix[$i][$j] = 1;
                    } elseif ($sum > 3) {
                        $this->newMatrix[$i][$j] = 0;
                    }
                } else {
                    $sum = $this->getCountAliveCells($i, $j);
                    if ($sum == 3) {
                        $this->newMatrix[$i][$j] = 1;
                    }
                }
            }
        }

        // novy stav matice musim ulozit, protoze doslo ke zmene vychozi matice
        $this->initMatrix = $this->newMatrix;

        return $this->newMatrix;
    }


    /**
     * vrati pocet zivych bunek v okoli bunky
     * @param int $i
     * @param int $j
     * @return int
     */
    protected function getCountAliveCells($i, $j)
    {
        $valuesTop = $this->getElementValue($i - 1, $j - 1) + $this->getElementValue($i - 1, $j) + $this->getElementValue($i - 1, $j + 1);
        $valuesRow = $this->getElementValue($i, $j - 1) + $this->getElementValue($i, $j + 1);
        $valuesDown = $this->getElementValue($i + 1, $j - 1) + $this->getElementValue($i + 1, $j) + $this->getElementValue($i + 1, $j + 1);
        return $valuesTop + $valuesRow + $valuesDown;
    }


    /**
     * vrati prvek matice - A_ij
     * @param int $i
     * @param int $j
     * @return int
     */
    protected function getElementValue($i, $j)
    {
        if (!array_key_exists($i, $this->initMatrix)) {
            return 0;
        }

        if (!array_key_exists($j, $this->initMatrix[$i])) {
            return 0;
        }

        return $this->initMatrix[$i][$j];
    }


    public function setMaxLoop($num)
    {
        $this->maxLoop = $num;
    }

}