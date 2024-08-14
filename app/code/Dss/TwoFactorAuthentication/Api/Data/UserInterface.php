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
     * @return int
     */
    public function getId(): int;

    /**
     * Get Original User Id
     *
     * @return int
     */
    public function getOriginalId(): int;

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
     * @return int
     */
    public function getIsActive(): int;

    /**
     * Get IP Restriction Enabled
     *
     * @return int|null
     */
    public function getIpEnabled(): ?int;

    /**
     * Get IP List
     *
     * @return string|null
     */
    public function getIpList(): ?string;

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
     * @return void
     */
    public function setId($id): void;

    /**
     * Set Original Role Id
     *
     * @param int $originalId
     * @return void
     */
    public function setOriginalId($originalId): void;

    /**
     * Set Secret
     *
     * @param string $secret
     * @return void
     */
    public function setUserSecret($secret): void;

    /**
     * Set Time Shift
     *
     * @param int|null $timeShift
     * @return void
     */
    public function setTimeShift($timeShift): void;

    /**
     * Set Is Active
     *
     * @param int $isActive
     * @return void
     */
    public function setIsActive($isActive): void;

    /**
     * Set IP Restriction Enabled
     *
     * @param int $ipEnabled
     * @return void
     */
    public function setIpEnabled($ipEnabled): void;

    /**
     * Set IP List
     *
     * @param string|null $ipList
     * @return void
     */
    public function setIpList($ipList): void;

    /**
     * Set Email enabled
     *
     * @param int $enabled
     * @return void
     */
    public function setEmailCodeEnabled($enabled): void;
}
