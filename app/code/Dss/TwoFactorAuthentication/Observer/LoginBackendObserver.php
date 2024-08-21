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
use Magento\Framework\Exception\Plugin\AuthenticationException;
use Magento\Store\Model\Store;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Backend\App\ConfigInterface;
use Magento\Framework\App\RequestInterface;
use Dss\TwoFactorAuthentication\Model\Authentication;
use Dss\TwoFactorAuthentication\Model\User;
use Dss\TwoFactorAuthentication\Model\Ip;
use Magento\Backend\Model\Auth\Session;
use Magento\User\Model\UserFactory;
use Magento\Framework\Event\Observer;
use Dss\TwoFactorAuthentication\Helper\Data;
use Magento\Email\Model\BackendTemplate;

class LoginBackendObserver implements ObserverInterface
{
    /**
     * @param RequestInterface $request
     * @param Authentication $authModel
     * @param Ip $ipModel
     * @param User $user
     * @param UserFactory $userFactory
     * @param Session $authStorage
     * @param TransportBuilder $transportBuilder
     * @param ConfigInterface $config
     */
    public function __construct(
        protected RequestInterface $request,
        protected Authentication $authModel,
        protected Ip $ipModel,
        protected User $user,
        protected UserFactory $userFactory,
        protected Session $authStorage,
        protected TransportBuilder $transportBuilder,
        protected ConfigInterface $config
    ) {
    }

    /**
     * Check Login Backend
     *
     * @param Observer $observer
     * @throws AuthenticationException
     */
    public function execute(Observer $observer)
    {
        if (!$this->authStorage->getAllowBackendAccountLoginCheck()) {
            return;
        }
        $this->authStorage->setAllowBackendAccountLoginCheck(false);

        $user = $this->userFactory->create()->loadByUsername($observer->getEvent()->getUsername());
        $otpPassword = $this->request->getPost('otp_password');
        $loginSuccess = $observer->getEvent()->getResult();

        if ($loginSuccess && $user->getId()) {
            $extUser = $this->user->loadOriginal($user->getId());

            if ($this->ipModel->userIpRestricted($extUser)) {
                throw new AuthenticationException(__('You did not sign in correctly or access restricted.'));
            }

            if ($this->authModel->userRestricted($extUser, $otpPassword)) {
                // enable second login step
                if (empty($otpPassword) && $extUser->getEmailCodeEnabled()) {
                    $this->_sendAdminOtpEmail($user, $this->authModel->createHotp($extUser));
                    // prepare verification form
                    $this->authStorage->setNeedVerification(true);
                    $this->authStorage->setTfaUser($user);
                    $this->authStorage->setUser(null);
                }

                throw new AuthenticationException(__('You did not sign in correctly or access restricted.'));
            }
        }
    }

    /**
     * Send admin one time password action
     *
     * @param mixed $user
     * @param string $newPassword
     *
     * @return void
     */
    protected function _sendAdminOtpEmail($user, $newPassword)
    {
        $transport = $this->transportBuilder->setTemplateIdentifier(
            Data::OTP_EMAIL_TEMPLATE
        )
            ->setTemplateModel(BackendTemplate::class)
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                'store' => Store::DEFAULT_STORE_ID
            ])
            ->setTemplateVars([
                'user' => $user,
                'password' => $newPassword
            ])
            ->setFrom($this->config->getValue(\Magento\User\Model\User::XML_PATH_FORGOT_EMAIL_IDENTITY))
            ->addTo($user->getEmail(), $user->getName())
            ->getTransport();

        $transport->sendMessage();
    }
}
