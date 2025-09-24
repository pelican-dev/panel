<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $default = <<<'EOF'
'use strict';

const rfr = require('rfr');
const _ = require('lodash');

const Core = rfr('src/services/index.js');

class Service extends Core {}

module.exports = Service;
EOF;

    private $default_mc = <<<'EOF'
'use strict';

const rfr = require('rfr');
const _ = require('lodash');

const Core = rfr('src/services/index.js');

class Service extends Core {
    onConsole(data) {
        // Hide the output spam from Bungeecord getting pinged.
        if (_.endsWith(data, '<-> InitialHandler has connected')) return;
        return super.onConsole(data);
    }
}

module.exports = Service;
EOF;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->text('index_file')->nullable()->after('startup');
        });

        DB::transaction(function () {
            DB::table('services')->where('author', 'ptrdctyl-v040-11e6-8b77-86f30ca893d3')->where('folder', '!=', 'minecraft')->update([
                'index_file' => $this->default,
            ]);

            DB::table('services')->where('author', 'ptrdctyl-v040-11e6-8b77-86f30ca893d3')->where('folder', 'minecraft')->update([
                'index_file' => $this->default_mc,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('index_file');
        });
    }
};
