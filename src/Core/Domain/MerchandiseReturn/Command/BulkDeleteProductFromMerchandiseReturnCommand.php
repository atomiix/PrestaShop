<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

declare(strict_types=1);

namespace PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\Command;

use PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\Exception\MerchandiseReturnConstraintException;
use PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\Exception\MerchandiseReturnException;
use PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\ValueObject\MerchandiseReturnDetail;
use PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\ValueObject\MerchandiseReturnId;

/**
 * Deletes products from given order.
 */
class BulkDeleteProductFromMerchandiseReturnCommand
{
    /**
     * @var MerchandiseReturnId
     */
    private $merchandiseReturnId;

    /**
     * @var MerchandiseReturnDetail[]
     */
    private $merchandiseReturnDetails;

    /**
     * @param int $merchandiseReturnId
     * @param MerchandiseReturnDetail[] $merchandiseReturnDetails
     *
     * @throws MerchandiseReturnException
     * @throws MerchandiseReturnConstraintException
     */
    public function __construct(
        int $merchandiseReturnId,
        array $merchandiseReturnDetails
    ) {
        $this->merchandiseReturnId = new MerchandiseReturnId($merchandiseReturnId);
        $this->setMerchandiseReturnDetails($merchandiseReturnDetails);
    }

    /**
     * @param MerchandiseReturnDetail[] $merchandiseReturnDetails
     *
     * @throws MerchandiseReturnException
     */
    private function setMerchandiseReturnDetails(array $merchandiseReturnDetails): void
    {
        foreach ($merchandiseReturnDetails as $merchandiseReturnDetail) {
            if (!$merchandiseReturnDetail instanceof MerchandiseReturnDetail) {
                throw new MerchandiseReturnConstraintException(
                    'merchandise return details array must instances of MerchandiseReturnDetail'
                );
            }
            $this->merchandiseReturnDetails[] = $merchandiseReturnDetail;
        }
    }

    /**
     * @return MerchandiseReturnDetail[]
     */
    public function getMerchandiseReturnDetails(): array
    {
        return $this->merchandiseReturnDetails;
    }

    /**
     * @return MerchandiseReturnId
     */
    public function getMerchandiseReturnId(): MerchandiseReturnId
    {
        return $this->merchandiseReturnId;
    }
}