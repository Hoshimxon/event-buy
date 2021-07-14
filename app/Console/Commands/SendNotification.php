<?php

namespace App\Console\Commands;

use App\Jobs\EmailNotification;
use App\Models\Product;
use App\Models\User;
use App\Sms;
use Illuminate\Console\Command;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification {type} {client} {product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to client about shopping. This command has 3 parameters. First parameter is type which channel type. Second parameter is client id and third is product id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle()
    {
        $client = User::query()->where('id', $this->argument('client'))->first();
        if (!$client) {
            $this->info('Client not found!');
            return 0;
        }

        $product = Product::query()->where('id', $this->argument('product'))->first();
        if (!$product) {
            $this->info('Product not found!');
            return 0;
        }

        switch ($this->argument('type')) {
            case 'mail':
                EmailNotification::dispatch($client, $product);
                $this->info('Successfully sent!');
                break;
            case 'sms':
                $message = Sms::sendSmsNotification($client, $product);
                $this->info($message);
                break;
            default:
                $this->info('Type is incorrect!');
        }
    }
}
