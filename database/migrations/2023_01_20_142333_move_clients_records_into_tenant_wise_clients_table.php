<?php

use App\Models\Client;
use App\Models\TenantWiseClient;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $clients = Client::whereNotNull('tenant_id')->get();

        foreach ($clients as $client) {
            TenantWiseClient::create([
                'user_id' => $client->user_id,
                'client_id' => $client->id,
                'tenant_id' => $client->tenant_id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
