<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Command;
use App\Packages\Support\PhoneNumber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;
use Throwable;

class ImportWishlist extends Command
{
    protected $signature = "import:wishlist";
    protected $description = "";

    public function __construct(
        private readonly LoggerInterface $logger,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $bar = $this->initializeProgressBar();
        DB::table('public.tmp_users_wishlist')->orderBy('phone')->orderBy('sku_id')->chunk(100, function (Collection $wishlists) use($bar) {
            foreach ($wishlists as $row) {
                $bar->advance();
                try {
                    $phone = ltrim(PhoneNumberUtil::getInstance()->format(PhoneNumberUtil::getInstance()->parse($row->phone, 'RU', new PhoneNumber()), PhoneNumberFormat::E164), '+');
                    $user = DB::table('users.users')->select('user_id')->where('phone', $phone)->first();
                    $product = DB::table('catalog.products')->select('id')->where('sku', $row->sku_id)->first();
                    if ($user !== null && $product !== null) {
                        $userId = $user->user_id;
                        $productId = $product->id;
                        DB::table('users.wishlist_products')->updateOrInsert(
                            [
                                'user_id' => $userId,
                                'product_id' => $productId,
                            ],
                            [
                                'uuid' => (string)Str::uuid(),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]
                        );
                        $this->info("Imported: " . json_encode($row));
                    }
                } catch (Throwable $e) {
                    $this->error($e->getCode());
                    $this->logger->error($e->getCode(), [
                        'row' => $row
                    ]);
                }
            }
        });
        $bar->finish();
        return 0;
    }
}
