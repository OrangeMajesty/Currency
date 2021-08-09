<?php

namespace App\Console\Commands;

use Faker\Core\Number;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output;
use Symfony\Component\Console\Output\BufferedOutput;


class CurrencySync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизует данные с cbr.ru';

    /**
     * The path by which to synchronize data
     * @var string
     */
    private const PATH = 'http://cbr.ru/scripts/XML_daily.asp';

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
        $content = file_get_contents(self::PATH);

        try {
            $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $contentArray = json_decode($json,TRUE);

            if(array_key_exists("Valute", $contentArray))
            {
                if(!array_key_exists("@attributes", $contentArray))
                    throw new \Exception("Attributes not founded");

                $attrArray = $contentArray['@attributes'];
                $date = isset($attrArray['Date']) ? $attrArray['Date'] : now();

                foreach ($contentArray['Valute'] as $currencyItem)
                {
                    $rate = str_replace(",", ".", $currencyItem['Value']);
                    $nominal = intval($currencyItem['Nominal']);

                    $result = Artisan::call("currency:update", [
                        'code' => $currencyItem['CharCode'],
                        'rate' => $rate,
                        'name' => $currencyItem['Name'],
                        'date' => $date,
                        'nominal' => $nominal,
                    ]);

                    if($result != 0)
                        throw new \Exception("Error synchronize rates currency");
                }

                $this->info("Rates currency successful synchronize");
            }

        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return -1;
        }
        return 0;
    }
}
