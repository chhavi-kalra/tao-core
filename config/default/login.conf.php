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
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA;
 */

/**
 * Default test runner config
 */
return array(
    /**
     * Disable the browser ability to store login/passwords
     * @type bool
     */
    'disableAutocomplete' => false,

    'use_captcha' => false,
    'use_hard_lockout' => false, // by default soft lockout will be used

    'captcha_failed_attempts' => 2, // amount of failed login attempts before captcha showing
    'lockout_failed_attempts' => 5, // amount of failed login attempts before lockout

    'soft_lockout_period' => 'P15M', // 15 minutes

    // todo: 'trusted_terminal_ttl' => 180, // amount of days while TT will be active
);
