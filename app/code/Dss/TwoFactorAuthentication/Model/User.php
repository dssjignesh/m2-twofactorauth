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
namespace Dss\TwoFactorAuthentication\Model;

use Magento\Framework\Model\AbstractModel;
use Dss\TwoFactorAuthentication\Api\Data\UserInterface;
use Dss\TwoFactorAuthentication\Model\ResourceModel\User as ResourceModelUser;

class User extends AbstractModel implements UserInterface
{
    /**
     * @var string
     */
    public const SHOW_IP_FIELD = 'current_ip';

    /**
     * @var string
     */
    public const SHOW_TFA_FIELD = 'tfa_key';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModelUser::class);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): ?int
    {
        if ($this->getData(self::USER_ID)) {
            return (int) $this->getData(self::USER_ID);
        }
        return $this->getData(self::USER_ID);
    }

    /**
     * Get Original User Id
     *
     * @return string
     */
    public function getOriginalId(): ?string
    {
        return $this->getData(self::ORIGINAL_USER_ID);
    }

    /**
     * Get Secret
     *
     * @return string
     */
    public function getUserSecret(): ?string
    {
        return $this->getData(self::USER_SECRET);
    }

    /**
     * Get Time Shift
     *
     * @return string
     */
    public function getTimeShift(): ?string
    {
        return $this->getData(self::TIME_SHIFT);
    }

    /**
     * Get Is Active
     *
     * @return string
     */
    public function getIsActive(): ?string
    {
        return (string) $this->getData(self::IS_ACTIVE);
    }

    /**
     * Get IP Enabled
     *
     * @return string
     */
    public function getIpEnabled(): ?string
    {
        return $this->getData(self::IP_ENABLED);
    }

    /**
     * Get IP List
     *
     * @return string
     */
    public function getIpList(): ?string
    {
        return $this->getData(self::IP_LIST);
    }

    /**
     * Get Email Enabled
     *
     * @return string
     */
    public function getEmailCodeEnabled(): ?string
    {
        return $this->getData(self::EMAIL_CODE_ENABLED);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return void
     */
    public function setId($id): void
    {
        $this->setData(self::USER_ID, $id);
    }

    /**
     * Set Original User Id
     *
     * @param int $originalId
     * @return void
     */
    public function setOriginalId($originalId): void
    {
        $this->setData(self::ORIGINAL_USER_ID, $originalId);
    }

    /**
     * Set Secret
     *
     * @param string $secret
     * @return void
     */
    public function setUserSecret($secret): void
    {
        $this->setData(self::USER_SECRET, $secret);
    }

    /**
     * Set Time Shift
     *
     * @param int $timeShift
     * @return void
     */
    public function setTimeShift($timeShift): void
    {
        $this->setData(self::TIME_SHIFT, $timeShift);
    }

    /**
     * Set Is Active
     *
     * @param int $value
     * @return void
     */
    public function setIsActive($value): void
    {
        $this->setData(self::IS_ACTIVE, $value);
    }

    /**
     * Set IP Enabled
     *
     * @param int $enabled
     * @return void
     */
    public function setIpEnabled($enabled): void
    {
        $this->setData(self::IP_ENABLED, $enabled);
    }

    /**
     * Set IP List
     *
     * @param string $list
     * @return void
     */
    public function setIpList($list): void
    {
        $this->setData(self::IP_LIST, $list);
    }

    /**
     * Set Email Enabled
     *
     * @param int $enabled
     * @return void
     */
    public function setEmailCodeEnabled($enabled): void
    {
        $this->setData(self::EMAIL_CODE_ENABLED, $enabled);
    }

    /**
     * Load object by ID
     *
     * @param integer $originalId
     *
     * @return $this
     */
    public function loadOriginal($originalId): self
    {
        $this->_getResource()->load($this, $originalId, self::ORIGINAL_USER_ID);
        return $this;
    }

    /**
     * Load object data
     *
     * @param int|string $key
     * @param null|string $field
     * @return $this
     */
    public function load($key, $field = null): self
    {
        if (!is_numeric($key)) {
            $this->_getResource()->load($this, $key, $field);

            return $this;
        }

        return parent::load($key, $field);
    }
}
