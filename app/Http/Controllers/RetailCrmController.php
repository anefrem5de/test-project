<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use RetailCrm\Api\Enum\CountryCodeIso3166;
use RetailCrm\Api\Exception\Client\BuilderException;
use RetailCrm\Api\Factory\SimpleClientFactory;
use RetailCrm\Api\Model\Entity\Orders\Delivery\OrderDeliveryAddress;
use RetailCrm\Api\Model\Entity\Orders\Delivery\SerializedOrderDelivery;
use RetailCrm\Api\Model\Entity\Orders\Items\Offer as OrdersOffer;
use RetailCrm\Api\Model\Entity\Orders\Items\OrderProduct;
use RetailCrm\Api\Model\Entity\Orders\Items\PriceType;
use RetailCrm\Api\Model\Entity\Orders\Order;
use RetailCrm\Api\Model\Entity\Orders\Payment;
use RetailCrm\Api\Model\Entity\Store\Product;
use RetailCrm\Api\Model\Filter\Store\ProductFilterType;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;
use RetailCrm\Api\Model\Request\Store\ProductsRequest;
use Throwable;


class RetailCrmController extends Controller
{
    public function orderFormView(): View
    {
        return view("order")->with(["article" => "AZ105W",
            "brand" => "Azalita",
            "full_name" => "Efremov Anton Vladimirovich",
            "comment" => "git"
        ]);
    }

    /**
     * @throws BuilderException
     */

    public function orderFormHandler(Request $request)
    {
        $client = SimpleClientFactory::createClient('https://superposuda.retailcrm.ru/',
            'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb');
        $productsRequest = new ProductsRequest();
        $order = new Order();
        $ordersOffer = new OrdersOffer();
        $item = new OrderProduct();
        $productsRequest->filter = new ProductFilterType();
        $orderRequest = new OrdersCreateRequest();
        $payment = new Payment();
        $delivery = new SerializedOrderDelivery();
        $deliveryAddress = new OrderDeliveryAddress();

        $payment->type = 'bank-card';
        $payment->status = 'paid';
        $payment->amount = 1000;
        $payment->paidAt = new DateTime();

        $deliveryAddress->index = '344001';
        $deliveryAddress->countryIso = CountryCodeIso3166::RUSSIAN_FEDERATION;
        $deliveryAddress->region = 'Region';
        $deliveryAddress->city = 'City';
        $deliveryAddress->street = 'Street';
        $deliveryAddress->building = '10';

        $delivery->address = $deliveryAddress;
        $delivery->cost = 0;
        $delivery->netCost = 0;

        try {
            $article = $request["article"];
            $productsRequest->filter->manufacturer = $request["brand"];
            $response = $client->store->products($productsRequest);

            $product = array_values(array_filter($response->products,
                fn(Product $product) => $product->article == $article))[0];

            $productOffer = $product->offers[0];
            $ordersOffer->name = $productOffer->name;
            $ordersOffer->id = $productOffer->id;
            $ordersOffer->externalId = $productOffer->externalId;
            $ordersOffer->xmlId = $productOffer->xmlId;
            $ordersOffer->article = $productOffer->article;
            $ordersOffer->unit = $productOffer->unit;

            $item->offer = $ordersOffer;
            $item->id = $ordersOffer->id;
            $item->priceType = new PriceType('base');
            $item->quantity = 1;
            $item->productName = $item->offer->name;
            $order->status = 'trouble';
            $order->orderType = 'fizik';

            $flp = explode(" ", $request["full_name"]);
            $order->firstName = $flp[1];
            $order->lastName = $flp[0];
            $order->patronymic = $flp[2];

            $order->orderMethod = 'test';
            $order->site = 'test';
            $order->number = 25021995;
            $order->delivery = $delivery;
            $order->items = [$item];
            $order->payments = [$payment];
            $order->customerComment = $request["comment"];

            $orderRequest->order = $order;
            $orderRequest->site = 'test';

            try {
                $response = $client->orders->create($orderRequest);
                dd($response);
            } catch (Throwable $e) {
                dd($e);
            }
        } catch (Throwable $e) {
            dd($e);
        }


    }
}
