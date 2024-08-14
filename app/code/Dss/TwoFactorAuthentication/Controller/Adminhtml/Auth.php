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
namespace Dss\TwoFactorAuthentication\Controller\Adminhtml;

use Magento\Backend\App\AbstractAction;
use Magento\Backend\App\Action\Context;
use Magento\User\Model\UserFactory;
use Dss\TwoFactorAuthentication\Model\Authentication;
use Dss\TwoFactorAuthentication\Model\Ip;
use Dss\TwoFactorAuthentication\Model\User;

abstract class Auth extends AbstractAction
{
    /**
     * Construct
     *
     * @param Context $context
     * @param UserFactory $userFactory
     * @param Authentication $authModel
     * @param Ip $ipModel
     * @param User $user
     */
    public function __construct(
        Context $context,
        protected UserFactory $userFactory,
        protected Authentication $authModel,
        protected Ip $ipModel,
        protected User $user
    ) {
        parent::__construct($context);
    }

    /**
     * Check if user has permissions to access this controller
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
