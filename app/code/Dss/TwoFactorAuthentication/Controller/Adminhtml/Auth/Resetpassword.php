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
namespace Dss\TwoFactorAuthentication\Controller\Adminhtml\Auth;

use Dss\TwoFactorAuthentication\Controller\Adminhtml\Auth;
use Dss\TwoFactorAuthentication\Model\Authentication;
use Magento\Backend\Model\Auth\Session;
use Magento\Security\Model\AdminSessionsManager;
use Magento\Backend\App\Action\Context;
use Magento\User\Model\UserFactory;
use Dss\TwoFactorAuthentication\Model\Ip;
use Dss\TwoFactorAuthentication\Model\User;
use Magento\Backend\Model\UrlInterface;
use Magento\Backend\Helper\Data;

class Resetpassword extends Auth
{
    /**
     * @param Context $context
     * @param UserFactory $userFactory
     * @param Authentication $authModel
     * @param Ip $ipModel
     * @param User $user
     * @param Session $authStorage
     * @param AdminSessionsManager $authManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Context $context,
        UserFactory $userFactory,
        Authentication $authModel,
        Ip $ipModel,
        User $user,
        protected Session $authStorage,
        protected AdminSessionsManager $authManager,
        protected UrlInterface $urlBuilder
    ) {
        parent::__construct(
            $context,
            $userFactory,
            $authModel,
            $ipModel,
            $user
        );
    }

    /**
     * Send admin one time password action
     */
    public function execute()
    {
        $user = $this->authStorage->getTfaUser();
        $extUser = $this->user->loadOriginal($user->getId());
        $password = (string) $this->getRequest()->getPost('password');

        if (!$this->ipModel->userIpRestricted($extUser)) {
            // show verification form
            if ($this->authStorage->getNeedVerification()) {
                $this->authStorage->setNeedVerification(false);

                $this->messageManager->getMessages(true);
                $this->messageManager->addSuccess(
                    __('The email with verification code has been successfully sent to your account address.')
                );

                $this->_view->loadLayout();
                $this->_view->renderLayout();
                return;
            } elseif (!$this->authModel->userRestricted($extUser, $password)) {
                $this->authStorage
                    ->setTfaUser(null)
                    ->setUser($user)
                    ->processLogin();

                if ($this->authStorage->isLoggedIn()) {
                    $this->authManager->processLogin();
                    return $this->_homeRedirect();
                }
            }
        }

        $this->messageManager->addErrorMessage(
            __('You did not sign in correctly or access restricted.')
        );
        return $this->_homeRedirect();
    }

    /**
     * Redirect to home page
     *
     * @param string|null $url
     * @return \Magento\Framework\App\ResponseInterface
     */
    protected function _homeRedirect($url = null)
    {
        if ($url === null) {
            $url = $this->_objectManager->get(Data::class)->getHomePageUrl();
        }
        return $this->getResponse()->setRedirect($url);
    }
}
