<?php


namespace skmax\inflect;

class YandexInflectParser {
    use InflectorTrait;

    protected $url = 'https://export.yandex.ru/inflect.xml';
    protected $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    protected $inflections;
    protected $word;

    public function __construct($word, array $config = array()) {
        $this->configure($config);
        $this->word = $word;
    }

    public function getInflections($refresh = false) {
        if ($this->inflections === null || $refresh) {
            $this->inflections = $this->loadInflectData();
        }

        return $this->inflections;
    }

    public function setInflections(array $inflections) {
        throw new \Exception('Cannot manual set inflections in this class');
    }

    protected function configure(array $config) {
        foreach ($config as $name => $value) {
            $this->$name = $value;
        }

    }

    protected function loadInflectData() {
        return $this->parseXml($this->loadXml());
    }

    protected function parseXml($xmlString) {
        $result = [];

        $lastValue = libxml_use_internal_errors(true);

        if (!empty($xmlString) && ($xml = simplexml_load_string($xmlString))) {
            if (isset($xml->original)) {
                $result[] = (string)$xml->original;
            }

            if (isset($xml->inflection)) {
                foreach ($xml->inflection as $inflectionEl) {
                    $attributes = $inflectionEl->attributes();
                    if (!isset($attributes['case']) || ($case = intval($attributes['case'])) <= 0) {
                        continue;
                    }

                    $result[$case] = (string)$inflectionEl;
                }
            }


            ksort($result);
        }

        libxml_use_internal_errors($lastValue);


        return $result;
    }

    protected function loadXml() {
        $ch = curl_init();
        $timeout = 15;
        curl_setopt($ch, CURLOPT_URL, $this->createInflectUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
        $data = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        unset($ch);
        if ($httpCode != 200) {
            return null;
        }

        return $data;
    }

    protected function createInflectUrl() {
        $url = $this->url . '?name=' . urlencode($this->word);

        return $url;
    }
}