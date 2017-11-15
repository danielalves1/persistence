<?php

/**
 * User: Daniel Alves
 * Date: 02/11/2017
 * Time: 16:50
 */
class Persistence
{
    //TODO: monsta script SQL de acordo com objeto passado, se tiver o ID, então ele faz UPDATE, senão, INSERT
    public function mountSQL($object)
    {
        if ($this->getID($object)) {
            return $this->update($object);
        } else {
            return $this->insert($object);
        }
    }

    //TODO: Monta o SQL do INSERT para o objeto passado
    private function insert($object)
    {
        if ($this->isValidObject($object)) {
            $sql = "INSERT INTO " . $object->CLASS_NAME . " (";
            foreach ($object as $key => $value) {
                if ($key != 'CLASS_PREFIX' && $key != 'CLASS_NAME' && $key != 'id') {
                    $sql .= $object->CLASS_PREFIX . $key . ", ";
                }
            }
            $sql = substr($sql, 0, strlen(trim($sql)) - 1) . ") VALUES (";
            foreach ($object as $key => $value) {
                if ($key != 'CLASS_PREFIX' && $key != 'CLASS_NAME' && $key != 'id') {
                    $sql .= "'" . $value . "', ";
                }
            }
            $sql = substr($sql, 0, strlen(trim($sql)) - 1) . ")";
            return $sql;
        } else {
            return "Objeto inválido!";
        }
    }

    //TODO: Monta o SQL do UPDATE para o objeto passado
    private function update($object)
    {
        if ($this->isValidObject($object)) {
            $sql = "UPDATE " . $object->CLASS_NAME . " SET ";
            foreach ($object as $key => $value) {
                if ($key != "CLASS_PREFIX" && $key != "CLASS_NAME" && $key != 'id' && $value != '') {
                    $sql .= $object->CLASS_PREFIX . $key . (is_numeric($value) ? (" = " . $value . ", ") : (" = '" . $value . "', "));
                }
            }
            $sql = substr($sql, 0, strlen(trim($sql)) - 1) . " WHERE ";
            $sql .= $object->CLASS_PREFIX . "ID = " . $object->id;
            return $sql;
        } else {
            return "Objeto inválido!";
        }
    }

    //TODO: Verifica se o usuário possui o padrão de atributos
    private function isValidObject($object)
    {
        return (isset($object->CLASS_PREFIX) && isset($object->CLASS_NAME) &&
            $object->CLASS_PREFIX != "" && $object->CLASS_NAME != "");
    }

    // TODO: Verifica se existe um ID informado no objeto;
    private function getID($object)
    {
        foreach ($object as $key => $value) {
            if (strtoupper($key) == 'ID') {
                if ($value != '') {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    //TODO: monta script SQL para selecionar os registros
    public function mountSelect($object)
    {
        if ($this->isValidObject($object)) {
            $sql = "SELECT ";
            foreach ($object as $key => $value) {
                if ($key != 'CLASS_PREFIX' && $key != 'CLASS_NAME') {
                    $sql .= (is_object($value) ? ($this->leftJoin($value, 'x')->attr) : ('x.' . $object->CLASS_PREFIX . $key . ", "));
                }
            }
            $sql = substr($sql, 0, strlen(trim($sql)) - 1) . ' FROM ' . $object->CLASS_NAME . ' x ';
            foreach ($object as $key => $value) {
                if (is_object($value)) {
                    $sql .= $this->leftJoin($value, 'x')->join;
                }
            }
            $sql .= ' WHERE 1=1 ';
            foreach ($object as $key => $value) {
                if ($key != 'CLASS_PREFIX' && $key != 'CLASS_NAME') {
                    $value = (is_object($value) ? '' : $value);
                    if ($value) {
                        $sql .= ' AND x.' . $object->CLASS_PREFIX . $key . ' like \'%' . $value . '%\'';
                    }
                }
            }
            return $sql;
        } else {
            return "Objeto inválido!";
        }
    }

    //TODO: Monta o LEFT JOIN com retorno [join, attr]
    public function leftJoin($object, $alias1)
    {
        $return = (object)['join' => '', 'attr' => ''];
        if ($this->isValidObject($object)) {
            $alias = substr($object->CLASS_NAME, 0, 2);
            $return->join = ' LEFT JOIN ' . $object->CLASS_NAME .
                ' ' . $alias .
                ' ON ' . $alias . '.' . $object->CLASS_PREFIX . 'id = ' . $alias1 . '.' .
                $object->CLASS_PREFIX . 'id ';

            foreach ($object as $key => $value) {
                if ($key != 'CLASS_PREFIX' && $key != 'CLASS_NAME') {
                    $return->attr .= (is_object($value) ? ($this->leftJoin($value, $alias)->attr) : ($alias . '.' . $object->CLASS_PREFIX . $key . ", "));
                    if (is_object($value)) {
                        $return->join .= $this->leftJoin($value, $alias)->join;
                    }
                }
            }
        }
        return $return;
    }
}