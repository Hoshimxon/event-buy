<?php

namespace App\Notifications;

use App\Models\Error;
use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductBoughtMailNotification extends Notification
{
    use Queueable;

    /**
     * @var
     */
    protected $client;

    /**
     * @var
     */
    protected $product;

    /**
     * ProductBoughtMailNotification constructor.
     * @param User $client
     * @param Product $product
     */
    public function __construct(User $client, Product $product)
    {
        $this->client = $client;
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|MailMessage
     */
    public function toMail($notifiable)
    {
        try{
            return (new MailMessage)
                ->view('email.buy_product', ['product' => $this->product])
                ->subject('New product bought!');
        }catch (\Exception $exception) {
            return Error::query()->create([
                'client_id' => $this->client->id,
                'product_id' => $this->product->id,
                'error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
