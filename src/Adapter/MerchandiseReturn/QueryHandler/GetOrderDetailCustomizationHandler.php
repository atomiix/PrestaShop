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

namespace PrestaShop\PrestaShop\Adapter\MerchandiseReturn\QueryHandler;

use Order;
use PrestaShop\PrestaShop\Adapter\Entity\OrderDetail;
use PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\Query\GetOrderDetailCustomization;
use PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\QueryHandler\GetOrderDetailCustomizationHandlerInterface;
use PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\QueryResult\OrderDetailCustomization;
use PrestaShop\PrestaShop\Core\Domain\MerchandiseReturn\QueryResult\OrderDetailCustomizations;
use Product;

class GetOrderDetailCustomizationHandler implements GetOrderDetailCustomizationHandlerInterface
{
    /**
     * @param GetOrderDetailCustomization $query
     *
     * @return OrderDetailCustomizations|null
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function handle(GetOrderDetailCustomization $query): ?OrderDetailCustomizations
    {
        $orderDetail = new OrderDetail($query->getOrderDetailId());
        $order = new Order($orderDetail->id_order);
        $customizations = [];
        /** @todo need id lang */
        $productCustomizations = Product::getAllCustomizedDatas($order->id_cart, 1, true, null, $orderDetail->id_customization);
        $customizedDatas = null;
        if (isset($productCustomizations[$orderDetail->product_id][$orderDetail->product_attribute_id])) {
            $customizedDatas = $productCustomizations[$orderDetail->product_id][$orderDetail->product_attribute_id];
        }
        if (is_array($customizedDatas)) {
            foreach ($customizedDatas as $customizationPerAddress) {
                foreach ($customizationPerAddress as $customizationId => $customization) {
                    foreach ($customization['datas'] as $datas) {
                        foreach ($datas as $data) {
                            $customizations[] = new OrderDetailCustomization((int) $data['type'], $data['name'], $data['value']);
                        }
                    }
                }
            }

            return new OrderDetailCustomizations($customizations);
        }

        return null;
    }
}
