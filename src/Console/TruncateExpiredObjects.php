<?php

namespace Spatie\Tags\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateExpiredObjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Free your database space by removing expired objects';

    /**
     *
     */
    public function handle()
    {
        DB::table('taggables')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', Carbon::now()->toDateTimeString())
            ->delete();
    }
}
