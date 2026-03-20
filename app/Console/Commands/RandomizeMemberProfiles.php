<?php

namespace App\Console\Commands;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RandomizeMemberProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:randomize-members
                            {--force-all : Randomize all members, not just incomplete ones}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give dummy member accounts random-looking profile data (phone, address, work, etc.)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $forceAll = (bool) $this->option('force-all');

        $query = Member::query();

        if (! $forceAll) {
            // Only touch members that look incomplete
            $query->whereNull('nature_of_work')
                ->orWhereNull('employer_business_name')
                ->orWhereNull('phone');
        }

        $members = $query->get();

        if ($members->isEmpty()) {
            $this->info('No members matched the criteria to randomize.');
            return self::SUCCESS;
        }

        $jobs = [
            'Teacher', 'Nurse', 'Engineer', 'Office Staff', 'Technician',
            'Administrator', 'Accountant', 'Driver', 'Supervisor', 'Staff'
        ];

        $employers = [
            'Davao del Sur State College',
            'Local Government Unit',
            'Community Cooperative',
            'Private Company',
            'State University',
        ];

        $streets = [
            'Rizal St.', 'Bonifacio St.', 'Mabini St.', 'Luna St.', 'Quezon Ave.',
            'National Highway', 'Poblacion Road', 'Market Road',
        ];

        $barangays = [
            'Poblacion', 'San Isidro', 'San Roque', 'San Jose', 'Sto. Niño',
            'Bagong Silang', 'Hope Village',
        ];

        // Load demo photo URLs
        $photoJson = storage_path('app/public/profile_photos/demo_photos.json');
        $demoPhotos = file_exists($photoJson) ? json_decode(file_get_contents($photoJson), true) : [];
        if (empty($demoPhotos)) {
            $this->warn('No demo profile photos found. Skipping photo assignment.');
        }

        $this->info('Randomizing profiles for '.$members->count().' member(s)...');

        foreach ($members as $member) {
            $member->phone = $member->phone ?: '09'.random_int(100000000, 999999999);

            if (! $member->address) {
                $street = $streets[array_rand($streets)];
                $brgy = $barangays[array_rand($barangays)];
                $member->address = random_int(1, 200).' '.$street.', Brgy. '.$brgy;
            }

            if (! $member->join_date) {
                $member->join_date = Carbon::now()->subDays(random_int(30, 365));
            }

            $member->nature_of_work = $member->nature_of_work ?: $jobs[array_rand($jobs)];
            $member->employer_business_name = $member->employer_business_name ?: $employers[array_rand($employers)];

            if (! $member->date_of_employment) {
                $member->date_of_employment = Carbon::now()->subYears(random_int(1, 20))->subMonths(random_int(0, 11));
            }

            $member->tin_number = $member->tin_number ?: random_int(100, 999).'-'.random_int(100, 999).'-'.random_int(100, 999).'-'.random_int(100, 999);
            $member->sss_gsis_no = $member->sss_gsis_no ?: random_int(1000000000, 9999999999);

            // Assign a random demo profile photo if available
            if (!empty($demoPhotos)) {
                $randomPhoto = $demoPhotos[array_rand($demoPhotos)];
                // Download and save to storage/app/public/profile_photos
                $filename = 'profile_photos/demo_' . $member->id . '_' . uniqid() . '.jpg';
                try {
                    $imgData = @file_get_contents($randomPhoto);
                    if ($imgData !== false) {
                        file_put_contents(storage_path('app/public/' . $filename), $imgData);
                        $member->profile_photo = $filename;
                    }
                } catch (\Exception $e) {
                    // Ignore download errors
                }
            }

            // Mark as profile completed for demo purposes
            $member->profile_completed = true;

            $member->save();
        }

        $this->info('Randomized profiles for '.$members->count().' member(s).');

        return self::SUCCESS;
    }
}

