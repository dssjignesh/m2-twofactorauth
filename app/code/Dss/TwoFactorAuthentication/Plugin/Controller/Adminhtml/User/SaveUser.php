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
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Message\ManagerInterface;
use Dss\TwoFactorAuthentication\Model\User;
use Dss\TwoFactorAuthentication\Model\Authentication;
use Magento\User\Model\User as AdminUser;
use Magento\Backend\App\Action\Context;
use Magento\User\Controller\Adminhtml\User\Save;
use Magento\User\Block\User\Edit\Tab\Main;
use Magento\Framework\DataObject;
use Magento\User\Controller\Adminhtml\User\Role\SaveRole;

class SaveUser
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * SaveRole constructor.
     *
     * @param Context $context
     * @param Session $authSession
     * @param AdminUser $adminUser
     * @param User $user
     * @param Authentication $authModel
     */
    public function __construct(
        Context $context,
        protected Session $authSession,
        protected AdminUser $adminUser,
        protected User $user,
        protected Authentication $authModel
    ) {
        $this->request = $context->getRequest();
        $this->messageManager = $context->getMessageManager();
        $this->resultFactory = $context->getResultFactory();
    }

    /**
     * Validate and save admin settings
     *
     * @param SaveRole $object
     * @param callable $proceed
     */
    public function aroundExecute(Save $object, callable $proceed)
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $userId = (int) $this->request->getPost('user_id') ?: null;
        $isUpdateSecret = $this->request->getPost('dss_tfa_update');

        try {
            $this->_validateUser();

            $extUser = $this->user->loadOriginal($userId);
            $newData = $this->_getDataUpdated($extUser);

            $isUpdateSecret &= ($newData->getIsActive() || $newData->getEmailCodeEnabled());

            if (!$isUpdateSecret) {
                // reset secret
                $newData->setUserSecret($extUser->getUserSecret());
                // totp verifycation
            } elseif ($newData->getIsActive() &&
                !$this->authModel->verifySecurely($newData, $this->request->getPost('otp_password'))
            ) {
                throw new AuthenticationException(__('You have entered an invalid One-Time Password.'));
            }
        } catch (AuthenticationException $e) {
            $this->messageManager->addError($e->getMessage());
            $arguments = $userId ? ['user_id' => $userId] : [];
            return $redirect->setPath('*/*/edit', $arguments);
        }

        $return = $proceed();

        $newData->setOriginalUserId(
            $userId ?: $this->_getLastNewUser()->getId()
        );

        $extUser->setData($newData->getData())->save();

        return $return;
    }

    /**
     * Validate current user password
     *
     * @return $this
     * @throws AuthenticationException
     */
    protected function _validateUser(): self
    {
        $password = $this->request->getParam(
            Main::CURRENT_USER_PASSWORD_FIELD
        );
        $user = $this->authSession->getUser();
        $user->performIdentityCheck($password);

        return $this;
    }

    /**
     * Init form data object
     *
     * @param User $user
     * @return DataObject
     */
    protected function _getDataUpdated(User $user): DataObject
    {
        $params = $this->request->getPost('dss_tfa');
        $newData = new DataObject($user->getData());

        foreach ($params as $field => $value) {
            $newData->setData($field, $value);
        }

        return $newData;
    }

    /**
     * Returns Last Created User
     *
     * @return \Magento\User\Model\User
     */
    protected function _getLastNewUser()
    {
        return $this->adminUser->getCollection()->setOrder('user_id', 'DESC')->getFirstItem();
    }
}
