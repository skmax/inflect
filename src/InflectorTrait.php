<?php
namespace skmax\inflect;


/**
 * Class InflectorTrait
 * @package skmax\inflect
 */
trait InflectorTrait {
    /**
     * @var string
     */
    protected $originInflectAttribute = 'title';

    /**
     * Return array of all inflections
     * @param bool|false $refresh
     * @return mixed
     */
    abstract public function getInflections($refresh = false);

    /**
     * @param array $inflections
     * @return mixed
     */
    abstract public function setInflections(array $inflections);


    /**
     * @return int
     */
    public function getNominativeId() {
        return 1;
    }

    /**
     * @return int
     */
    public function getGenitiveId() {
        return 2;
    }


    /**
     * @return int
     */
    public function getDativeId() {
        return 3;
    }

    /**
     * @return int
     */
    public function getAccusativeId() {
        return 4;
    }

    /**
     * @return int
     */
    public function getInstrumentalId() {
        return 5;
    }

    /**
     * @return int
     */
    public function getPrepositionalId() {
        return 6;
    }

    /**
     * @return null
     */
    public function getOrigin() {
        return $this->getCaseById(0, true);
    }

    /**
     * @param bool|true $defaultIfEmpty
     * @return null
     */
    public function getNominative($defaultIfEmpty = true) {
        return $this->getCaseById($this->getNominativeId(), $defaultIfEmpty);
    }

    /**
     * @param bool|true $defaultIfEmpty
     * @return null
     */
    public function getDative($defaultIfEmpty = true) {
        return $this->getCaseById($this->getDativeId(), $defaultIfEmpty);
    }

    /**
     * @param bool|true $defaultIfEmpty
     * @return null
     */
    public function getAccusative($defaultIfEmpty = true) {
        return $this->getCaseById($this->getAccusative(), $defaultIfEmpty);
    }

    /**
     * @param bool|true $defaultIfEmpty
     * @return null
     */
    public function getInstrumental($defaultIfEmpty = true) {
        return $this->getCaseById($this->getInstrumentalId(), $defaultIfEmpty);
    }

    /**
     * @param bool|true $defaultIfEmpty
     * @return null
     */
    public function getPrepositional($defaultIfEmpty = true) {
        return $this->getCaseById($this->getPrepositionalId(), $defaultIfEmpty);
    }

    /**
     * @param $id
     * @param bool|true $defaultIfEmpty
     * @return null
     */
    public function getCaseById($id, $defaultIfEmpty = true) {
        $inflections = $this->getInflections();

        $case = isset($inflections[$id]) ? $inflections[$id] : null;
        if ($defaultIfEmpty && empty($case)) {
            $case = $this->getDefaultCaseValue();
        }

        return $case;
    }

    /**
     * @return mixed
     */
    protected function getDefaultCaseValue() {
        $inflections = $this->getInflections();
        $nominativeId = $this->getNominativeId();
        if (!empty($inflections[$nominativeId])) {
            return $inflections[$nominativeId];
        } elseif (!empty($inflections[0])) {
            return $inflections[0];
        } else {
            return $this->{$this->originInflectAttribute};
        }
    }
}