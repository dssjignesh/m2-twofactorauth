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
namespace Dss\TwoFactorAuthentication\Block\User\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Store\Model\System\Store;
use Dss\TwoFactorAuthentication\Model\User;
use Dss\TwoFactorAuthentication\Model\Ip;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Action\Context as ContextManager;
use Magento\Authorization\Model\Role;
use Magento\Framework\Phrase;

class IpSettings extends Generic implements TabInterface
{
    /**
     * @var Role
     */
    protected $roleManager;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Settings constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ContextManager $contextManager
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param User $user
     * @param Ip $ipModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ContextManager $contextManager,
        protected FormFactory $formFactory,
        protected Store $systemStore,
        protected User $user,
        protected Ip $ipModel,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_objectManager = $contextManager->getObjectManager();
    }

    /**
     * Get tab label
     *
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('IP Restriction');
    }

    /**
     * Get tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Whether tab is available
     *
     * @return bool
     */
    public function canShowTab(): bool
    {
        return true;
    }

    /**
     * Whether tab is visible
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return false;
    }

    /**
     * Is single store
     *
     * @return bool
     */
    public function isSingleStoreMode(): bool
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    /**
     * Get user from registry
     *
     * @return mixed
     */
    public function getUser(): mixed
    {
        if (!$this->roleManager) {
            $this->roleManager = $this->_coreRegistry->registry('permissions_user');
        }
        return $this->roleManager;
    }

    /**
     * Get suffix
     *
     * @return string
     */
    public function getSuffix(): string
    {
        return 'setting';
    }

    /**
     * Get field from role
     *
     * @param string $field
     *
     * @return mixed
     */
    public function getFieldValue($field): mixed
    {
        return $this->getRole()->getData($field);
    }

    /**
     * Get field from Global
     *
     * @param string $field
     *
     * @return int
     */
    public function getFieldValueUseConfig($field): int
    {
        if ($this->getRole()->hasData($field)) {
            return $this->getRole()->getData($field);
        }

        return 1;
    }

    /**
     * Prepare form for IP settings
     *
     * @return $this
     */
    protected function _prepareForm(): self
    {
        $extUser = $this->user->loadOriginal($this->getUser()->getId());

        $form = $this->formFactory->create();
        $fieldset = $form->addFieldset('ip_config_fieldset', ['legend' => __('Admin IP Restrictions')]);

        $fieldset->addField('note', 'note', [
            'text' => __('Make sure that specified IPs are <b>static</b>. Otherwise the account can be <b>locked</b>.')
        ]);

        $fieldset->addField(
            $extUser::IP_LIST,
            'text',
            [
                'name' => "dss_tfa[" . $extUser::IP_LIST . "]",
                'label' => __('Whitelisted IPs'),
                'class' => 'aittfa-ip-validate',
                'note' => 'Use a space to separate IPs.
                Example: 192.168.135.65 192.168.18.230<br>Leave empty for access from any location.'
                ]
        );

        $extUser->setData('current_ip', $this->ipModel->getCurrentIp());

        $fieldset->addField(
            $extUser::SHOW_IP_FIELD,
            'text',
            [
                'name' => $extUser::SHOW_IP_FIELD,
                'label' => __('Your Current IP'),
                'title' => __('Your Current IP'),
                'readonly' => 'true'
                ]
        );

        $form->setValues($extUser->getData());
        $this->setForm($form);

        return $this;
    }
}
