<?php

namespace core\models;


class ModelDatabase
{
    use RequestHelper;

    protected $connect;
    protected $defaultSelect = ['*'];
    protected $sql = '';

    public $result;

    public function __construct()
    {
        $this->connect = ConnectDatabase::getConnection();
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function buildSql()
    {
        $this->sql = 'SELECT ' . implode(',', ($this->request['select'] ?? $this->defaultSelect));
        $this->sql .= ' FROM ' . $this->getTableName();

        if (array_key_exists('where', $this->request)) {
            foreach ($this->request['where'] as $key => $value) {
                if ($key === 'and') {
                    foreach ($value as $index => $element) {
                        if (stristr($this->sql, 'WHERE')) {
                            $this->sql .= ' AND ' . $element;
                        } else {
                            $this->sql .= ' WHERE ' . $element;
                        }
                    }
                }

                if ($key === 'or') {
                    foreach ($value as $index => $element) {
                        $this->sql .= ' OR ' . $element;
                    }
                }
            }
        }
        if (array_key_exists('order', $this->request)) {
            $order = implode(',', $this->request['order']);
            $this->sql .= ' ORDER BY ' . $order;
        }
    }

    public function all()
    {
        $result = [];
        $this->buildSql();
        $buildRequest = $this->connect->prepare($this->sql);
        if (!empty($this->arguments)) {
            foreach ($this->arguments as $element => $value) {
                $buildRequest->bindValue(':' . $element, $value);
            }
        }
        $buildRequest->execute();
        $rows = $buildRequest->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $this->result = $row;
            $result[] = clone $this;
        }

        return $result;
    }

    protected function getTableName()
    {
        return strtolower(substr(strrchr(get_class($this), "\\"), 1));
    }
}