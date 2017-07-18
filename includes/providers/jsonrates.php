<?php
//##copyright##

class iaRatesProviderJsonrates extends abstractCore
{
    const ENDPOINT_URL = 'http://apilayer.net/api/live';


    protected function _composeUrl($source, $currencies)
    {
        $params = [
            'access_key' => $this->iaCore->get('cr_provider_apikey'),
            'source' => $source,
            'currencies' => implode(',', $currencies),
            'format' => 1
        ];

        return self::ENDPOINT_URL . '?' . http_build_query($params);
    }

    public function fetch(array $currencies)
    {
        $source = null;
        $currencyCodes = [];

        foreach ($currencies as $currency) {
            if ($currency['default']) {
                $source = $currency['code'];
            } else {
                $currencyCodes[] = $currency['code'];
            }
        }

        $url = $this->_composeUrl($source, $currencyCodes);

        $response = $this->_httpRequest($url);

        if ($response) {
            $array = json_decode($response, true);

            if (!empty($array['quotes'])) {
                $rates = [];
                foreach ($array['quotes'] as $cur => $rate) {
                    $code = substr($cur, 3);
                    $rate = (float)$rate;

                    $rates[$code] = $rate;
                }

                return $rates;
            }
        }

        return false;
    }

    protected function _httpRequest($url)
    {
        return iaUtil::getPageContent($url);
    }
}