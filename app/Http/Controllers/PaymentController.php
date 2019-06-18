<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 16.06.2019
 * Time: 21:43
 */

namespace App\Http\Controllers;


use App\Http\Requests\PaymentRequest;
use App\Payment;
use Illuminate\Http\Request;
use YandexCheckout\Client;

class PaymentController extends Controller
{
    /**
     * @param PaymentRequest $request
     *
     * @return array
     */
    public function payment(PaymentRequest $request)
    {
        $validated = $request->validated();
        $payment = new Payment($validated);
        $payment->return_id = uniqid("",true);

        try {
            $client = new Client();
            $client->setAuth(env('YANDEX_KASSA_SHOPID'),env('YANDEX_KASSA_SECRET'));

            $yaPayment = $client->createPayment([
                'amount' => [
                    'value' => 4900.0,
                    'currency' => 'RUB',
                ],
                'payment_method_data' => [
                    'type' => 'bank_card',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => 'http://12space.101ad.ru/payment_result/'.$payment->return_id,
                ],
                'capture' => true,
                'description' => 'Курс',
                'receipt' => [
                    'phone' => $payment->phone,
                    'items' => [
                        [
                            'description' => 'Авансовый платеж за курс',
                            'quantity' => 1,
                            'amount' => [
                                'value' => 4900.0,
                                'currency' => 'RUB',
                            ],
                            'vat_code' => 1,
                        ]
                    ],
                ],
            ],$payment->return_id);

        } catch (\Exception $exception) {

            $payment->status = Payment::STATUS['error'];
            $payment->details = $exception->getMessage();

            return [
                'status' => 'error',
                'message' => $exception->getMessage(),
            ];
        }

        $payment->status = Payment::STATUS['pending'];
        $payment->yandex_kassa_id = $yaPayment->getId();
        $payment->save();

        return $yaPayment->getConfirmation()->jsonSerialize();
    }

    /**
     * @param Request $request
     * @param         $return_id
     *
     * @return array
     * @throws \Exception
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function paymentResult(Request $request, $return_id)
    {
        $payment = Payment::where('return_id',$return_id)->firstOrfail();

        $client = new Client();
        $client->setAuth(env('YANDEX_KASSA_SHOPID'),env('YANDEX_KASSA_SECRET'));
        $yaPayment = $client->getPaymentInfo($payment->yandex_kassa_id);

        if (false == isset(Payment::STATUS[$yaPayment->getStatus()])) {
            throw new \Exception('Unexpected payment status',500);
        }

        if (
            Payment::STATUS[$yaPayment->getStatus()] != $payment->status
        ) {
            $payment->status = Payment::STATUS[$yaPayment->getStatus()];
            $payment->save();
        }

        if ($request->wantsJson() || isset($_COOKIE['z0dd'])) {
            return [
                'status' => $yaPayment->getStatus(),
            ];
        }

        return redirect('http://12space.ru');
    }
}
