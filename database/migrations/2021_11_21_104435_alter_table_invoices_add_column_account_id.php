<?php

use App\Models\Client;
use App\Models\Account;
use App\Models\Invoice;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableInvoicesAddColumnAccountId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->foreignId('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {

            $client = Client::with('accounts')->find($invoice->client_id);
            $account = null;

            if ($client->accounts->count()) {
                $account = $client->accounts->first();
            } else {
                $account = new Account();
                $account->name = 'Default Account';
                $client->accounts()->save($account);
            }

            $invoice->account_id = $account->id;
            $invoice->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
        });
    }
}
