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
namespace Dss\TwoFactorAuthentication\Plugin\Controller\Adminhtml\User;

use Magento\Framework\Controller\ResultFactory;
use Dss\TwoFactorAuthentication\Model\Authentication;
use Magento\Backend\Model\Auth\Session;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Controller\Adminhtml\Auth\Login;

class BeforeLogin
{
    /**
     * BeforeLogin constructor.
     *
     * @param Session $authStorage
     * @param Redirect $resultRedirectFactory
     */
    public function __construct(
        protected Session $authStorage,
        protected Redirect $resultRedirectFactory
    ) {
    }

    /**
     * Redirect to verification form
     *
     * @param Login $object
     * @param callable $proceed
     */
    public function aroundExecute(Login $object, callable $proceed)
    {
        if ($this->authStorage->getNeedVerification()) {
            $resultRedirect = $this->resultRedirectFactory;
            $resultRedirect->setPath('aitauth/auth/resetpassword');
            return $resultRedirect;
        }

        return $proceed();
    }
}
