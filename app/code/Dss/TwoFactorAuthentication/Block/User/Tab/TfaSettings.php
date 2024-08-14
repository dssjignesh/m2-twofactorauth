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
use Dss\TwoFactorAuthentication\Model\User;
use Magento\Framework\Data\FormFactory;
use Dss\TwoFactorAuthentication\Model\Authentication;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Framework\DataObject;
use Magento\Authorization\Model\Role;
use Magento\Framework\Phrase;

class TfaSettings extends Generic implements TabInterface
{
    /**
     * @var Role
     */
    protected $roleManager;

    /**
     * Settings constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param User $user
     * @param Authentication $authModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        protected FormFactory $formFactory,
        protected User $user,
        protected Authentication $authModel,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Get tab label
     *
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('TFA Settings');
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
     * Timer HTML
     *
     * @return string
     */
    protected function _getTimerHtml(): string
    {
        $js = "<span id=\"system_time\"></span>
        <script type=\"text/javascript\">
            (function () {
                function startTime(diff) {
                    var t    = new Date(Date.now() + diff);
                    var text =
                        ('0' + t.getHours()).slice(-2) +
                        ':' +
                        ('0' + t.getMinutes()).slice(-2) +
                        ':' +
                        ('0' + t.getSeconds()).slice(-2);
                    document.getElementById('system_time').innerHTML = '(Server Time: ' + text + ')';
                    t = setTimeout(function () {
                        startTime(diff)
                    }, 1000);
                }
                startTime(" . time() * 1000 . " - Date.now());
            })();
        </script>";

        return $js;
    }

    /**
     * QR HTML
     *
     * @param string $secret
     * @return string
     */
    protected function _getQRHtml($secret): string
    {
        $email = $this->getUser()->getEmail();

        return '<img src="' . $this->authModel->getQRUrl($secret, $email) . '" />';
    }

    /**
     * Generate select options
     *
     * @return array
     */
    protected function _getTimeShiftOptions(): array
    {
        for ($i = -24; $i <= 24; $i++) {
            $timeOptions[] = [
                'label' => ($i < 0 ? '-' : '+') . sprintf('%02d', abs($i)) . ' h',
                'value' => $i * 3600
            ];
        }
        return $timeOptions;
    }

    /**
     * Prepare Form
     *
     * @return $this
     */
    protected function _prepareForm(): self
    {
        $extUser = $this->user->loadOriginal($this->getUser()->getId());
        $newSecret = $this->authModel->createAccountSecret();

        $formData = new DataObject($extUser->getData());
        $formData->setUserSecret($newSecret);
        $formData->setTfaKey($newSecret);

        $form = $this->formFactory->create();
        $fieldset = $form->addFieldset('main_fieldset', [
            'legend' => __('User Two-Factor Authentication Settings')
        ]);

        $fieldset->addField(
            $extUser::EMAIL_CODE_ENABLED,
            'select',
            [
                'name' => "dss_tfa[" . $extUser::EMAIL_CODE_ENABLED . "]",
                'label' => __('Email Verification'),
                'title' => __('Email Verification'),
                'values' => [
                    ['label' => __('Disable'), 'value' => 0],
                    ['label' => __('Enable'), 'value' => 1]
                ],
                'class' => 'select'
                ]
        );

        $toggle = $fieldset->addField(
            $extUser::IS_ACTIVE,
            'select',
            [
                'name' => "dss_tfa[" . $extUser::IS_ACTIVE . "]",
                'label' => __('Mobile Device Verification'),
                'title' => __('Mobile Device Verification'),
                'values' => [
                    ['label' => __('Disable'), 'value' => 0],
                    ['label' => __('Enable'), 'value' => 1]
                ],
                'class' => 'select'
                ]
        );

        $fieldset->addField('note', 'note', [
            'text' => __(
                '<b>Note:</b> When both <b>mobile device verification</b> and <b>email verification</b> are enabled,
                you can use any of the verification options upon logging in to the Admin panel.'
            )
        ]);

        $fieldset->addField(
            $extUser::TIME_SHIFT,
            'select',
            [
                'name' => "dss_tfa[" . $extUser::TIME_SHIFT . "]",
                'label' => __('Server Time Correction'),
                'title' => __('Server Time Correction'),
                'values' => $this->_getTimeShiftOptions(),
                'class' => 'select',
                'after_element_html' => $this->_getTimerHtml(),
                'note' => '<strong>Attention:</strong>
                You can use this option only in case if you manually changed time (not time zone!)
                on your mobile device. Please note that time differences between the server
                and your device can cause a one-time password mismatch.'
                ]
        );

        if ($extUser->getIsActive()) {
            // manual update option
            $toggle = $fieldset->addField(
                "dss_tfa_update",
                'select',
                [
                    'name' => "dss_tfa_update",
                    'label' => __('Set up New Secret'),
                    'title' => __('Set up New Secret'),
                    'values' => [
                        ['label' => __('No'), 'value' => 0],
                        ['label' => __('Yes'), 'value' => 1]
                    ],
                    'class' => 'select'
                ]
            );
        } else { // update secret while user enable tfa
            $fieldset->addField(
                "dss_tfa_update",
                'hidden',
                [
                    'name' => "dss_tfa_update"
                ]
            );

            $formData->setData("dss_tfa_update", 1);
        }

        //hidden
        $fieldset->addField(
            $extUser::USER_SECRET,
            'hidden',
            [
                'name' => "dss_tfa[" . $extUser::USER_SECRET . "]"
                ]
        );

        //visualize secret & qr
        $dependFields[] = $fieldset->addField(
            $extUser::SHOW_TFA_FIELD,
            'text',
            [
                'name' => $extUser::SHOW_TFA_FIELD,
                'label' => __('Secret Key'),
                'readonly' => true,
                'note' => $this->_getQRHtml($newSecret)
                ]
        );

        $dependFields[] = $fieldset->addField(
            'otp_password',
            'password',
            [
                'name' => "otp_password",
                'label' => __('Password Verification'),
                'title' => __('Password Verification'),
                'required' => true,
                'class' => 'validate-number',
                'note' => 'Add the secret key to your mobile app and enter a one-time password to verify the settings.'
            ]
        );

        $formAfter = $this->getLayout()->createBlock(
            Dependence::class
        );

        foreach ($dependFields as $field) {
            $formAfter->addFieldMap(
                $toggle->getHtmlId(),
                $toggle->getName()
            )->addFieldMap(
                $field->getHtmlId(),
                $field->getName()
            )->addFieldDependence(
                $field->getName(),
                $toggle->getName(),
                '1'
            );
        }

        $this->setChild('form_after', $formAfter);
        $form->setValues($formData->getData());
        $this->setForm($form);

        return $this;
    }
}
