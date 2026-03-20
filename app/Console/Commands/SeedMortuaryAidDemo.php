<?php

namespace App\Console\Commands;

use App\Models\Contribution;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SeedMortuaryAidDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:mortuary-aid {--count=30 : How many members to seed (max)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed dummy Mortuary Aid contributions for existing member accounts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $max = (int) $this->option('count');
        if ($max <= 0) {
            $this->error('Count must be a positive integer.');
            return self::FAILURE;
        }

        $members = Member::where('status', 'active')
            ->whereHas('contributions', function ($q) {
                // Ensure member has at least one contribution already
                $q->whereNull('contribution_type')->orWhereIn('contribution_type', ['regular', 'special', 'emergency', 'mortuary']);
            })
            ->whereDoesntHave('contributions', function ($q) {
                $q->where('contribution_type', 'mortuary');
            })
            ->take($max)
            ->get();

        if ($members->isEmpty()) {
            $this->warn('No eligible members found to seed mortuary aid contributions.');
            return self::SUCCESS;
        }

        $created = 0;

        foreach ($members as $member) {
            // Create 1–3 mortuary aid contributions spread over the last 12 months
            $entries = random_int(1, 3);

            for ($i = 0; $i < $entries; $i++) {
                $monthsAgo = random_int(0, 11);
                $date = Carbon::now()->subMonths($monthsAgo)->startOfMonth()->addDays(random_int(0, 27));

                Contribution::create([
                    'member_id'         => $member->id,
                    'amount'            => random_int(100, 300),
                    'contribution_type' => 'mortuary',
                    'contribution_date' => $date,
                    'status'            => 'approved',
                ]);

                $created++;
            }
        }

        $this->info("Seeded {$created} mortuary aid contribution(s) for {$members->count()} member(s).");

        return self::SUCCESS;
    }
}

