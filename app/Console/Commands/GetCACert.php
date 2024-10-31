<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetCACert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scep:get-ca-cert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command downloads the CA Cert from the SCEP Server.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = config('scep.server_url');

        $contents = file_get_contents($url . '?operation=GetCACert');

        $cert = "-----BEGIN CERTIFICATE-----\n" .
        chunk_split(base64_encode($contents), 64, "\n") .
        "-----END CERTIFICATE-----\n";

        Storage::disk('scep_certificate')->put('ca.pem', $cert);
    }
}
