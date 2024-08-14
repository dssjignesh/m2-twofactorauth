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
namespace Dss\TwoFactorAuthentication\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Event\Observer;

class PrepareLoginBackendObserver implements ObserverInterface
{
    /**
     * @param Session $authStorage
     */
    public function __construct(
        protected Session $authStorage
    ) {
    }

    /**
     * Prepare Login Backend
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $this->authStorage->setAllowBackendAccountLoginCheck(true);
    }
}
