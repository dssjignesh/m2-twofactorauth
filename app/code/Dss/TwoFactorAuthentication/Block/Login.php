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
namespace Dss\TwoFactorAuthentication\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Dss\TwoFactorAuthentication\Model\Authentication;

class Login extends Template
{
    /**
     * @var string
     */
    protected $_template = 'default.phtml';

    /**
     * @param Context $context
     * @param Authentication $authModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        protected Authentication $authModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Returns template
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->getIsAjax() ? '' : $this->_template;
    }

    /**
     * Renders HTML
     *
     * @return string
     */
    protected function _toHtml(): string
    {
        if ($this->authModel->isEnabled()) {
            return parent::_toHtml();
        }
        return '';
    }
}
