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
     * @return string
     */
    public function getId(): string;

    /**
     * Get Original User Id
     *
     * @return int|null
     */
    public function getOriginalId(): int|null;

    /**
     * Get Secret
     *
     * @return string
     */
    public function getUserSecret(): string;

    /**
     * Get Time Shift
     *
     * @return string
     */
    public function getTimeShift(): string;

    /**
     * Get Is Active
     *
     * @return int|string
     */
    public function getIsActive(): int|string;

    /**
     * Get IP Restriction Enabled
     *
     * @return int|null
     */
    public function getIpEnabled(): int|null;

    /**
     * Get IP List
     *
     * @return string|null
     */
    public function getIpList(): string|null;

    /**
     * Get Email enabled
     *
     * @return string
     */
    public function getEmailCodeEnabled(): string;

    /**
     * Set ID
     *
     * @param int $id
     */
    public function setId($id);

    /**
     * Set Original Role Id
     *
     * @param int $originalId
     */
    public function setOriginalId($originalId);

    /**
     * Set Secret
     *
     * @param string $secret
     */
    public function setUserSecret($secret);

    /**
     * Set Time Shift
     *
     * @param int|null $timeShift
     */
    public function setTimeShift($timeShift);

    /**
     * Set Is Active
     *
     * @param int $isActive
     */
    public function setIsActive($isActive);

    /**
     * Set IP Restriction Enabled
     *
     * @param int $ipEnabled
     */
    public function setIpEnabled($ipEnabled);

    /**
     * Set IP List
     *
     * @param string|null $ipList
     */
    public function setIpList($ipList);

    /**
     * Set Email enabled
     *
     * @param int $enabled
     */
    public function setEmailCodeEnabled($enabled);
}
