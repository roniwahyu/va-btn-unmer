<?php
namespace Roniwahyu\VaBtnUnmer;


use Traversable;

class DataStructure implements \JsonSerializable, \ArrayAccess, \IteratorAggregate, \Countable
{
    protected $columns = [];

    /**
     * Request constructor.
     * @param array $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getColumn($key) {
        return $this->getColumns()[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setColumn($key, $value) {
        $this->columns[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasColumn($key) {
        return array_key_exists($key, $this->getColumns());
    }
    /**
     * @param string $key
     * @return void
     */
    public function removeColumn($key) {
        $columns = $this->getColumns();
        if ($this->hasColumn($key)) unset($columns[$key]);
        $this->setColumns($columns);
    }

    /**
     * @param array $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function __get($name)
    {
        if ($this->hasColumn($name)) return $this->getColumn($name);
    }

    public function __set($name, $value)
    {
        $this->setColumn($name, $value);
    }

    /**
     * @return array
     */
    public function getAll() {
        return $this->getColumns();
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->getColumns();
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->hasColumn($offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->getColumn($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->setColumn($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->removeColumn($offset);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->getColumns());
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getColumns());
    }

    /**
     * @param array $keys
     */
    public function filter(array $keys) {
        $column_names = array_keys($this->getColumns());
        foreach ($column_names as $column_name) {
            if (!in_array($column_name, $keys) && $this->hasColumn($column_name))
                $this->removeColumn($column_name);
        }
    }

    /**
     * @param array $keys
     */
    public function except(array $keys) {
        $column_names = array_keys($this->getColumns());
        foreach ($column_names as $column_name) {
            if (in_array($column_name, $keys) && $this->hasColumn($column_name))
                $this->removeColumn($column_name);
        }
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function mustExists(array $keys) {
        $valid = true;
        foreach ($keys as $key) {
            if (!$this->hasColumn($key)) {
                $valid = false;
                break;
            }
        }
        return $valid;
    }

    public function errorMustExists(array $keys) {
        $valid = true;
        foreach ($keys as $key) {
            if (!$this->hasColumn($key)) {
                throw new \Exception("Kolom $key harus di set");
                break;
            }
        }
        return $valid;
    }

}