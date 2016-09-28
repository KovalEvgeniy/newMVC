<?php

namespace core\models;

use application\exceptions\Exception;

trait RequestHelper
{
    protected $request;
    protected $arguments;
    protected $validationParams = [
        '>=' => '>=',
        '=<' => '=<',
        '=' => '=',
        '>' => '>',
        '<' => '<',
        '!=' => '!=',
    ];

    public function select($select)//@todo обработать пустую стороку
    {
        if (!is_array($select) && !empty($select)) {
            $select = explode(',', str_replace(" ", "", $select));
        }
        foreach ($select as $key => $value) {
            $this->request['select'][] = $value;
        }
        //@todo$this->request['select'] = $select;

        return $this;
    }

    public function where(...$where)
    {
        if (is_array($where[0])) {
            foreach ($where as $key => $value) {
                $this->setArray($value, 'and');
            }
        } else {
            $this->setArray($where, 'and');
        }

        return $this;
    }

    public function setArray($where, $argument)
    {
        if (!isset($this->request['where'][$argument])) {
            $this->request['where'][$argument] = $this->parseWhere($where);
        } else {
            $this->request['where'][$argument] = array_merge($this->request['where']['and'], $this->parseWhere($where));
        }
    }

    public function parseWhere($param)
    {
        $result = [];
        $countWhere = count($param);
        if ($countWhere === 1) {
            $key = key($param);
            $result[] = "$key=:" . $this->getNewArgumentName($key, $param[$key]);
        } elseif ($countWhere === 2) {
            $result[] = "$param[0]=:" . $this->getNewArgumentName($param[0], $param[1]);
        } elseif ($countWhere === 3) {
            $result[] = "$param[0]" . $this->inspectionValues($param[1]) . ":"
                . $this->getNewArgumentName($param[0], $param[2]);
        } elseif ($countWhere === 4 && strtolower($param[1]) === 'between') {
            $result[] = $param[0] . " " . strtoupper($param[1]) . " "
                . ":" . $this->getNewArgumentName($param[0], $param[2])
                . " AND " . ":" . $this->getNewArgumentName($param[0], $param[3]);
        }
        return $result;
    }

    public function __call($name, $arguments)
    {
        $deleteStrAnd = 5;
        if (stristr(strtolower($name), 'and')) {//@todo stripos() для происка вхождения
            $parseParam = explode('and', strtolower(substr_replace($name, '', 0, $deleteStrAnd)));
            $result = [];
            foreach ($parseParam as $key => $value) {
                $result[] = [$value => $arguments[$key]];//@todo проверка на isset
            }
            //@todo зачем 2 цикла, можно сделать без result
            foreach ($result as $key => $value) {//@todo $key не используется
                if (!isset($this->request['where']['and'])) {
                    $this->request['where']['and'] = $this->parseWhere($value);
                } else {
                    $this->request['where']['and'] = array_merge($this->request['where']['and'], $this->parseWhere($value));
                }
            }
        }

        return $this;

    }

    public function whereBetween(...$param)//@todo проверка параметров
    {
        $this->where($param[0], 'between', $param[1], $param[2]);//@todo parseWhere($param[0], 'between', $param[1], $param[2])
        return $this;
    }

    public function orWhereBetween(...$param)//@todo проверка параметров
    {
        //@todo зачем $or
        $this->where($or = 'or', $param[0], 'between', $param[1], $param[2]);//@todo parseWhere()
        return $this;
    }

    public function orWhere(...$orWhere)//@todo эта логика есть в setArray(), orWhere() должен быть как и where()
    {
        foreach ($orWhere as $key => $value) {
            if (!isset($this->request['where']['or'])) {
                $this->request['where']['or'] = $this->parseWhere($value);
            } else {
                $this->request['where']['or'] = array_merge($this->request['where']['or'], $this->parseWhere($value));
            }
        }

        return $this;
    }

    public function orderBy($orderBy, $reverse = 'ASC')//@todo url=https://translate.google.com/#en/ru/reverse
    {
//        [@todo обработать
//            'id' => 'asc',
//            'name',
//            'age' => 'desc',
//        ]
        if (!is_array($orderBy)) {
            $orderBy = explode(',', str_replace(" ", "", $orderBy));
        }
        foreach ($orderBy as $key => $value) {
            $this->request['order'][] = $value . " " . strtoupper($reverse);
        }
        return $this;
    }

    protected function getNewArgumentName($key, $value)
    {
        $count = 5;//@todo вынетси в свойстово класса и сеттер
        if (!isset($this->arguments[$key])) {//@todo array_key_exists или isset
            $this->arguments[$key] = $value;
            return $key;
        }
        for ($i = 2; $i <= $count; $i++) {
            $element = $key . $i;
            if (array_key_exists($element, $this->arguments) === false) {//@todo array_key_exists или isset
                $this->arguments[$element] = $value;
                return $element;
            }
        }
        new Exception("Too many conditions for the same column!");
    }

    protected function inspectionValues($param)
    {
        if (array_key_exists($param, $this->validationParams)) {
            return $param;
        }
        new Exception("Incorrect comparison! Need >= || =< || > || <");
    }
}