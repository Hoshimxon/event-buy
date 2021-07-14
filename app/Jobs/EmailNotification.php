<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductBoughtMailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    protected $client;

    /**
     * @var Product
     */
    protected $product;

    /**
     * EmailNotification constructor.
     * @param User $client
     * @param Product $product
     */
    public function __construct(User $client, Product $product)
    {
        $this->client = $client;
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->client->notify(new ProductBoughtMailNotification($this->client, $this->product));
    }
}
