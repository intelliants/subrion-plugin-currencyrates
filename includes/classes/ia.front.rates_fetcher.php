<?php

/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2017 Intelliants, LLC <https://intelliants.com>
 *
 * This file is part of Subrion.
 *
 * Subrion is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Subrion is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Subrion. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://subrion.org/
 *
 ******************************************************************************/
class iaRates_fetcher extends abstractModuleFront
{
    protected $_moduleName = 'currencyrates';

    protected $_logger;


    public function fetch()
    {
        $this->_logger()->reset();

        $this->_logger()->write('RUNNING...');

        if (!$this->iaCore->get('cr_enable')) {
            $this->_logger()->write('RATES UPDATE IS DISABLED. STOPPED.');
            $this->_logger()->flush();

            return;
        }

        $providerName = $this->iaCore->get('cr_rates_provider');

        $this->_logger()->write('SELECTED PROVIDER IS "' . $providerName . '"');

        if ($provider = $this->_instantiateProvider($providerName)) {
            try {
                $iaCurrency = $this->iaCore->factory('currency');

                if ($rates = $provider->fetch($iaCurrency->fetchFromDb())) {
                    $this->_logger()->write('RATES FETCHED: ' . print_r($rates, true));

                    $this->iaDb->setTable(iaCurrency::getTable());
                    foreach ($rates as $currencyCode => $rate) {
                        $this->iaDb->update(['rate' => $rate], iaDb::convertIds($currencyCode, 'code'));
                    }
                    $this->iaDb->resetTable();

                    $this->iaCore->factory('currency')->invalidateCache();

                    $this->_logger()->write('NEW RATES SAVED');
                    $this->_logger()->setSuccess(true);
                } else {
                    $this->_logger()->write('FETCHED EMPTY RATES');
                }
            } catch (Exception $e) {
                $this->_logger()->write('EXCEPTION: ' . $e->getMessage());
            }
        } else {
            $this->_logger()->write('INTERNAL ERROR: CAN NOT INSTANTIATE PROVIDER CLASS');
        }

        $this->_logger()->flush();
    }

    protected function _instantiateProvider($name)
    {
        $class = 'iaRatesProvider' . ucfirst($name);
        $file = IA_MODULES . $this->getModuleName() . '/includes/providers/' . $name . iaSystem::EXECUTABLE_FILE_EXT;

        if (file_exists($file)) {
            require_once $file;

            if (class_exists($class)) {
                $instance = new $class($this->iaCore->factory('currency'));
                $instance->init();

                return $instance;
            }
        }

        return false;
    }

    protected function _logger()
    {
        if (is_null($this->_logger)) {
            $this->_logger = $this->iaCore->factoryModule('rates_logger', $this->getModuleName());
        }

        return $this->_logger;
    }
}
