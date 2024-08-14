<?php

declare(strict_types=1);
/**
 * Digit Software Solutions.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 * @category  Dss
 * @package   Dss_TwoFactorAuthentication
 * @author    Extension Team
 * @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
 */
namespace Dss\TwoFactorAuthentication\Api\Data;

interface UserInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const USER_ID = "user_id";
    public const ORIGINAL_USER_ID = "original_user_id";
    public const USER_SECRET = "user_secret";
    public const TIME_SHIFT = "time_shift";
    public const IS_ACTIVE = "is_active";
    public const IP_ENABLED = "ip_enabled";
    public const IP_LIST = "ip_list";
    public const EMAIL_CODE_ENABLED = "email_code_enabled";

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Original User Id
     *
     * @return int|null
     */
    public function getOriginalId();

    /**
     * Get Secret
     *
     * @return string
     */
    public function getUserSecret();

    /**
     * Get Time Shift
     *
     * @return int|null
     */
    public function getTimeShift();

    /**
     * Get Is Active
     *
     * @return int|null
     */
    public function getIsActive();

    /**
     * Get IP Restriction Enabled
     *
     * @return int|null
     */
    public function getIpEnabled();

    /**
     * Get IP List
     *
     * @return string|null
     */
    public function getIpList();

    /**
     * Get Email enabled
     *
     * @return int
     */
    public function getEmailCodeEnabled();

    /**
     * Set ID
     *
     * @param int $id
     * @return UserInterface
     */
    public function setId($id);

    /**
     * Set Original Role Id
     *
     * @param int $originalId
     * @return UserInterface
     */
    public function setOriginalId($originalId);

    /**
     * Set Secret
     *
     * @param string $secret
     * @return UserInterface
     */
    public function setUserSecret($secret);

    /**
     * Set Time Shift
     *
     * @param int|null $timeShift
     * @return UserInterface
     */
    public function setTimeShift($timeShift);

    /**
     * Set Is Active
     *
     * @param int $isActive
     * @return UserInterface
     */
    public function setIsActive($isActive);

    /**
     * Set IP Restriction Enabled
     *
     * @param int $ipEnabled
     * @return UserInterface
     */
    public function setIpEnabled($ipEnabled);

    /**
     * Set IP List
     *
     * @param string|null $ipList
     * @return UserInterface
     */
    public function setIpList($ipList);

    /**
     * Set Email enabled
     *
     * @param int $enabled
     * @return UserInterface
     */
    public function setEmailCodeEnabled($enabled);
}
