<?php


namespace skmax\inflect;


/**
 * Class InflectorSerializeTrait
 *
 * @package skmax\inflect
 */
trait InflectorSerializeTrait {
    use InflectorTrait;
    /**
     *
     * @var
     */
    protected $_inflections;
    /**
     * name of the property where stored serialized inflection information.
     * @var string
     */
    protected $_inflectorSerializeDataField = 'inflection_data';

    /**
     * @param bool|false $refresh
     * @return mixed
     */
    public function getInflections($refresh = false) {
        if ($this->_inflections === null || $refresh) {
            $this->_inflections = @unserialize($this->{$this->_inflectorSerializeDataField});
        }

        return $this->_inflections;
    }

    /**
     * @param array $inflections
     */
    public function setInflections(array $inflections) {
        if (empty($inflections)) {
            $this->{$this->_inflectorSerializeDataField} = null;
        } else {
            $this->{$this->_inflectorSerializeDataField} = serialize($inflections);
        }
        $this->_inflections = $inflections;
    }
}