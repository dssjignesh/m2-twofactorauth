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
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class Ip extends AbstractModel
{
    /**
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        private RemoteAddress $remoteAddress
    ) {
    }

    /**
     * Retrieve User IP
     *
     * @return string
     */
    public function getCurrentIp(): string
    {
        return $this->remoteAddress->getRemoteAddress();
    }

    /**
     * Search IP in specified list of IP's
     *
     * @param string $ip
     * @param string $ipList
     * @return bool
     */
    public function searchIpList($ip, $ipList): bool
    {
        $ipArray = explode(' ', $ipList);

        foreach ($ipArray as $ipItem) {
            if ($ipItem == $ip) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verify IP
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function userIpRestricted($data): bool
    {
        $ipList = $data->getIpList();

        if (!empty($ipList) &&
            !$this->searchIpList($this->getCurrentIp(), $ipList)
        ) {
            return true;
        }
        return false;
    }
}
