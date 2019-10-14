<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Lionel Lecaque  <lionel@taotesting.com>
 * @license GPLv2
 */

/**
 * The class is a helper to generate the persistence config
 * based on command line parameters or web installer parameters
 */
class tao_install_utils_DbalConfigCreator {

    public function createDbalConfig($installData)
    {
        // Oracle driver stores db host as db name.
        if($installData['db_driver'] == 'pdo_oci'){
            $installData['db_name'] = $installData['db_host'];
            $installData['db_host'] = '';
        }

        // Default configuration.
        $dbConnectionParams = array(
            'driver' => $installData['db_driver'],
            'host' => $installData['db_host'],
            'dbname' => $installData['db_name'],
            'user' => $installData['db_user'],
            'password' => $installData['db_pass'],
        );
        
        // Split host and port if port is present.
        $hostParts = explode(':', $installData['db_host']);
        if (count($hostParts) == 2) {
            $dbConnectionParams['host'] = $hostParts[0];
            $dbConnectionParams['port'] = $hostParts[1];
        }
        
        // Oracle driver uses portability construct
        if($installData['db_driver'] == 'pdo_oci'){
            $dbConnectionParams['wrapperClass'] = 'Doctrine\DBAL\Portability\Connection';
            $dbConnectionParams['portability'] = \Doctrine\DBAL\Portability\Connection::PORTABILITY_ALL;
            $dbConnectionParams['fetch_case'] = PDO::CASE_LOWER;
        }
        
        // Spanner driver is not registere in DBAL, so needs the correct classes for driver and platform.
        if($installData['db_driver'] == SpannerDriver::DRIVER_NAME) {
            $dbConnectionParams = [
                'dbname' => $installData['db_name'],
                'instance' => $installData['db_host'],
                'driverClass' => SpannerDriver::class,
                'platform' => new SpannerPlatform(),
            ];
        }

        return array(
            'driver' => 'dbal',
            'connection' => $dbConnectionParams,
        );
    }
}
