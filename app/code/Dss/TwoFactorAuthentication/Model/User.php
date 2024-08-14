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
     * @return string
     */
    public function getId(): string
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * Get Original User Id
     *
     * @return int|null
     */
    public function getOriginalId(): int|null
    {
        return $this->getData(self::ORIGINAL_USER_ID);
    }

    /**
     * Get Secret
     *
     * @return string
     */
    public function getUserSecret(): string
    {
        return $this->getData(self::USER_SECRET);
    }

    /**
     * Get Time Shift
     *
     * @return string
     */
    public function getTimeShift(): string
    {
        return $this->getData(self::TIME_SHIFT);
    }

    /**
     * Get Is Active
     *
     * @return int|string
     */
    public function getIsActive(): int|string
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Get IP Enabled
     *
     * @return int|null
     */
    public function getIpEnabled(): int|null
    {
        return $this->getData(self::IP_ENABLED);
    }

    /**
     * Get IP List
     *
     * @return string|null
     */
    public function getIpList(): string|null
    {
        return $this->getData(self::IP_LIST);
    }

    /**
     * Get Email Enabled
     *
     * @return string
     */
    public function getEmailCodeEnabled(): string
    {
        return $this->getData(self::EMAIL_CODE_ENABLED);
    }

    /**
     * Set ID
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->setData(self::USER_ID, $id);
    }

    /**
     * Set Original User Id
     *
     * @param int $originalId
     */
    public function setOriginalId($originalId)
    {
        $this->setData(self::ORIGINAL_USER_ID, $originalId);
    }

    /**
     * Set Secret
     *
     * @param string $secret
     */
    public function setUserSecret($secret)
    {
        $this->setData(self::USER_SECRET, $secret);
    }

    /**
     * Set Time Shift
     *
     * @param int $timeShift
     */
    public function setTimeShift($timeShift)
    {
        $this->setData(self::TIME_SHIFT, $timeShift);
    }

    /**
     * Set Is Active
     *
     * @param int $value
     */
    public function setIsActive($value)
    {
        $this->setData(self::IS_ACTIVE, $value);
    }

    /**
     * Set IP Enabled
     *
     * @param int $enabled
     */
    public function setIpEnabled($enabled)
    {
        $this->setData(self::IP_ENABLED, $enabled);
    }

    /**
     * Set IP List
     *
     * @param string $list
     */
    public function setIpList($list)
    {
        $this->setData(self::IP_LIST, $list);
    }

    /**
     * Set Email Enabled
     *
     * @param int $enabled
     */
    public function setEmailCodeEnabled($enabled)
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
