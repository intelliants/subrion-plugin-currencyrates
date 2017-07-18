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
 * @package Subrion\Plugin\Blog\Admin
 * @link https://subrion.org/
 * @author https://intelliants.com/ <support@subrion.org>
 * @license https://subrion.org/license.html
 *
 ******************************************************************************/

class iaBackendController extends iaAbstractControllerModuleBackend
{
    protected $_name = 'currency-rates';

    protected $_table = 'currency_rates_log';

    private $_limit = 7;


    public function __construct()
    {
        parent::__construct();

        $this->setHelper($this->_iaCore->factoryPlugin($this->getModuleName(), iaCore::ADMIN));
    }

    protected function _indexPage(&$iaView)
    {
        $iaView->display('index');

        $entries = $this->_fetch();

        $iaView->assign('entries', $entries);
        $iaView->assign('offset', $this->_limit);
    }

    protected function _gridRead($params)
    {
        if (1 == count($this->_iaCore->requestPath)) {
            if ('read' == $this->_iaCore->requestPath[0]) {
                $entries = $this->_fetch($_GET['offset']);

                $this->_iaCore->iaView->loadSmarty(true);

                $iaSmarty = $this->_iaCore->iaView->iaSmarty;
                $iaSmarty->assign('core', ['config' => [
                    'date_format' => $this->_iaCore->language['date_format'],
                    'time_format' => $this->_iaCore->language['time_format']
                ]]);

                $html = '';
                foreach ($entries as $entry) {
                    $iaSmarty->assign('entry', $entry);
                    $html .= $iaSmarty->fetch(IA_HOME . 'modules/currencyrates/templates/admin/widget.entry-row.tpl');
                }

                $offset = count($entries);
                if ($offset < $this->_limit) {
                    $offset = false;
                }

                return ['html' => $html, 'offset' => $offset];
            } elseif (is_numeric($this->_iaCore->requestPath[0])) {
                if ($entry = $this->getById($this->_iaCore->requestPath[0])) {
                    return ['log' => nl2br($entry['content'])];
                }
            }
        }

        return iaView::errorPage(iaView::ERROR_NOT_FOUND);
    }

    protected function _fetch($offset = 0)
    {
        return $this->_iaDb->all(iaDb::ALL_COLUMNS_SELECTION, '1 ORDER BY `ts` DESC', (int)$offset, $this->_limit);
    }
}
