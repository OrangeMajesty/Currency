<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\CurrencyType;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use function PHPUnit\Framework\isNull;

class CurrencyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update {code} {rate} {date?} {name?} {nominal=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет данные о валюте';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $date = $this->argument('date');
        $code = $this->argument('code');
        $nominal = $this->argument('nominal');
        $rate = round($this->argument('rate') * (10000 / $nominal));

        try {
            $type = CurrencyType::query()->firstOrCreate(
                [
                    'code' => $code
                ],
                [
                    'name' => $name,
                    'code' => $code,
                ]
            )->id;

            $attributes = [
                'currency_id' => $type,
                'created_at' => (new \DateTime($date))->setTime(0, 0, 0),
            ];

            $firstCurrency = Currency::query()->where($attributes)->first();
            if($firstCurrency)
                return 0;

            $currency = new Currency(
                array_merge($attributes, [
                    'rate' => $rate,
                ])
            );

            $result = $currency->save();
            if($result)
                $this->info("Rates currency was successfully updated");
            else
                throw new \Exception("Error adding rates currency");

        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return -1;
        }

        return 0;
    }

    /**
     * @return array[]
     */
    public function getArguments()
    {
        return [
            ['code', InputArgument::REQUIRED, 'Код валюты'],
            ['rate', InputArgument::REQUIRED, 'Курс к рублю'],
            ['name', InputArgument::OPTIONAL, 'Название валюты', ""],
            ['date', InputArgument::OPTIONAL, 'Дата записи', now()],
            ['nominal', InputArgument::OPTIONAL, 'Дата записи', 1]
        ];
    }
}
