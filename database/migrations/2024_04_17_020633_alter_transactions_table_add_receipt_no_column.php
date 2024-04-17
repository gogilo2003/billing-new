<?php

use App\Models\Transaction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('method')->nullable()->after('invoice_id')->change();
            $table->string('transaction_ref')->nullable()->after('method');
        });

        $txs = Transaction::whereNotNull('method')->get();

        foreach ($txs as $tx) {
            $method = explode('(', $tx->method);
            if (count($method) == 2) {
                $tx->method = trim($method[0]);
                $tx->transaction_ref = trim(str_replace(")", "", $method[1]));
                $tx->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $txs = Transaction::whereNotNull('transaction_ref')->get();
        foreach ($txs as $tx) {
            $method = sprintf("%s(%s)", trim($tx->method), trim($tx->transaction_ref));
            $tx->method = $method;
            $tx->save();
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_ref');
        });
    }
};
