<?php


namespace App;


use App\Models\Error;

class Sms
{

    /**
     * @param $user
     * @param $product
     * @return string
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public static function sendSmsNotification($user, $product)
    {
        $basic  = new \Vonage\Client\Credentials\Basic(env('VONAGE_API_KEY'), env('VONAGE_API_SECRET'));
        $client = new \Vonage\Client($basic);

        try{
            $client->message()->send([
                'to' => $user->phone,
                'from' => 'Buy event',
                'text' => 'New product bought! ' .
                    'Product name: ' . $product->name . ' ' .
                    'Product price: ' . $product->price
            ]);
            $message = 'Successfully sent!';
        }catch (\Exception $exception) {
            Error::query()->create([
                'client_id' => $user->id,
                'product_id' => $product->id,
                'error' => $exception->getMessage()
            ]);
            $message = $exception->getMessage();
        }
        return $message;
    }
}