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
class iaRates_logger extends abstractModuleFront
{
    protected static $_table = 'currency_rates_log';

    protected $_success = false;
    protected $_log = '';


    public function write($contents)
    {
        $this->_log .= $contents . PHP_EOL;
    }

    public function setSuccess($success)
    {
        $this->_success = $success;
    }

    public function flush()
    {
        $entry = [
            'ts' => date(iaDb::DATETIME_FORMAT),
            'success' => (bool)$this->_success,
            'content' => $this->_log
        ];

        $this->iaDb->insert($entry, null, self::getTable());
    }

    public function reset()
    {
        $this->_log = '';
    }
}
