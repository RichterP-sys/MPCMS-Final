<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Loan;
use App\Models\LoanCollateral;
use App\Models\LoanRepayment;
use App\Models\Dividend;
use App\Models\Notification;
use App\Models\CooperativeFund;
use App\Models\CooperativeAnnouncement;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding dummy data...');

        // ──────────────────────────────────────────────
        // 0. CLEAR EXISTING DATA (fresh seed each run)
        // ──────────────────────────────────────────────
        Schema::disableForeignKeyConstraints();
        ActivityLog::truncate();
        Notification::truncate();
        LoanRepayment::truncate();
        LoanCollateral::truncate();
        Loan::truncate(); // Also clears contributions (shared financial_records table)
        Dividend::truncate();
        CooperativeAnnouncement::truncate();
        CooperativeFund::truncate();
        User::truncate();
        Member::truncate();
        Schema::enableForeignKeyConstraints();
        $this->command->info('  ✓ Cleared existing data');

        // ──────────────────────────────────────────────
        // 1. ADMIN USER
        // ──────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin1234'),
            ]
        );
        $this->command->info('  ✓ Admin user');

        // ──────────────────────────────────────────────
        // 2. MEMBERS (200 - generated from Filipino name pools)
        // ──────────────────────────────────────────────
        $firstNames = ['Juan', 'Maria', 'Jose', 'Ana', 'Pedro', 'Rosa', 'Carlos', 'Lucia', 'Miguel', 'Elena', 'Roberto', 'Carmen', 'Antonio', 'Sofia', 'Francisco', 'Teresa', 'Ricardo', 'Isabel', 'Fernando', 'Gloria', 'Ramon', 'Lourdes', 'Manuel', 'Rita', 'Eduardo', 'Corazon', 'Alberto', 'Imelda', 'Enrique', 'Lydia', 'Felipe', 'Amelia', 'Raul', 'Consuelo', 'Victor', 'Patricia', 'Arturo', 'Rosario', 'Roberto', 'Teresita', 'Jorge', 'Leticia', 'Pablo', 'Mercedes', 'Andres', 'Fe', 'Oscar', 'Aurora', 'Hector', 'Dolores', 'Cesar', 'Esperanza', 'Rafael', 'Virginia', 'Sergio', 'Nora', 'Guillermo', 'Clara', 'Rodrigo', 'Rebecca'];
        $lastNames = ['Dela Cruz', 'Santos', 'Reyes', 'Garcia', 'Ramos', 'Mendoza', 'Torres', 'Fernandez', 'Villanueva', 'Bautista', 'Aquino', 'Gonzales', 'Cruz', 'Lim', 'Pascual', 'Rivera', 'Navarro', 'Morales', 'Santiago', 'Dizon', 'Castillo', 'Ocampo', 'Romero', 'Salazar', 'Mercado', 'Domingo', 'Valdez', 'Castro', 'Ortega', 'Vargas', 'Ramos', 'Herrera', 'Medina', 'Aguilar', 'Sandoval', 'Guerrero', 'Jimenez', 'Moreno', 'Silva', 'Flores', 'Delos Reyes', 'Molina', 'Cabrera', 'Estrada', 'Miranda', 'Luna', 'Padilla', 'Acosta', 'Serrano', 'Marquez', 'Fuentes', 'Espinoza', 'Rojas', 'Contreras', 'Mejia', 'Figueroa', 'Solis', 'Cervantes', 'Vega', 'Maldonado'];
        $jobs = ['Teacher', 'Nurse', 'Engineer', 'Accountant', 'Driver', 'Clerk', 'Technician', 'Pharmacist', 'Programmer', 'Manager', 'Security Guard', 'Electrician', 'Marketing Officer', 'Professor', 'Cashier', 'Welder', 'HR Specialist', 'Janitor', 'Cook', 'Sales Agent', 'Designer', 'Analyst', 'Supervisor', 'Operator', 'Secretary'];
        $employers = ['DepEd', 'PGH', 'DPWH', 'BIR', 'LTO', 'DOLE', 'PLDT', 'Mercury Drug', 'Accenture PH', 'SM Retail', 'Securitas PH', 'Meralco', 'Jollibee', 'Ateneo', 'BDO', 'SteelAsia', 'Ayala Corp', 'DLSU', 'Max\'s', 'McDonald\'s', 'Shell', 'Petron', 'Globe', 'Smart', 'Manila Water'];
        $streets = ['Rizal St', 'Mabini Ave', 'Bonifacio Blvd', 'Luna St', 'Aguinaldo Rd', 'Magsaysay St', 'Quezon Ave', 'Roxas Blvd', 'Osmeña St', 'Laurel Ave', 'Del Pilar St', 'Tandang Sora', 'Commonwealth Ave', 'Ayala Ave', 'Katipunan Ave', 'España Blvd', 'EDSA', 'Shaw Blvd', 'Taft Ave', 'C5 Road'];
        $cities = ['Quezon City', 'Manila', 'Makati', 'Pasig', 'Cavite', 'Taguig', 'Caloocan', 'Parañaque', 'Mandaluyong', 'Las Piñas', 'Muntinlupa', 'Pasay', 'Marikina', 'Valenzuela', 'Malabon', 'Navotas', 'San Juan', 'Pateros', 'Baguio', 'Cebu City'];

        $members = [];
        for ($i = 0; $i < 200; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $baseEmail = strtolower(Str::slug($firstName . '.' . $lastName));
            $email = $baseEmail . '.' . ($i + 1) . '@mpc.local';

            $joinDate = Carbon::now()->subMonths(rand(3, 24))->subDays(rand(0, 28));
            $status = $i < 170 ? 'active' : 'inactive'; // 170 active, 30 inactive

            $member = Member::create([
                'member_id' => 'MPC-' . now()->format('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make('member1234'),
                'phone' => '09' . rand(17, 39) . rand(1000000, 9999999),
                'address' => rand(1, 999) . ' ' . $streets[array_rand($streets)] . ', ' . $cities[array_rand($cities)],
                'join_date' => $joinDate,
                'status' => $status,
                'nature_of_work' => $jobs[array_rand($jobs)],
                'employer_business_name' => $employers[array_rand($employers)],
                'profile_completed' => true,
            ]);
            $members[] = $member;
        }
        $this->command->info('  ✓ 200 Members created');

        // ──────────────────────────────────────────────
        // 3. USER ACCOUNTS (linked to members)
        // ──────────────────────────────────────────────
        foreach ($members as $member) {
            User::create([
                'name' => $member->first_name . ' ' . $member->last_name,
                'email' => $member->email,
                'password' => Hash::make('member1234'),
                'member_id' => $member->id,
                'confirmed' => true,
            ]);
        }
        $this->command->info('  ✓ 200 User accounts linked');

        // ──────────────────────────────────────────────
        // 4. CONTRIBUTIONS (spread across 12 months)
        // ──────────────────────────────────────────────
        $contributionTypes = ['regular', 'special', 'emergency'];
        $statuses = ['approved', 'approved', 'approved', 'approved', 'pending']; // 80% approved

        foreach ($members as $member) {
            if ($member->status === 'inactive') {
                // Inactive members get only 2-3 old contributions
                $months = rand(2, 3);
                $startMonth = rand(8, 12);
            } else {
                $months = rand(6, 12);
                $startMonth = 1;
            }

            for ($m = 0; $m < $months; $m++) {
                $date = Carbon::now()->subMonths($months - $m - 1)->startOfMonth()->addDays(rand(0, 27));

                Contribution::updateOrCreate(
                    [
                        'member_id' => $member->id,
                        'contribution_date' => $date->format('Y-m-d'),
                        'contribution_type' => 'regular',
                    ],
                    [
                        'amount' => rand(5, 30) * 100, // 500 - 3000
                        'status' => $statuses[array_rand($statuses)],
                    ]
                );

                // 30% chance of additional special/emergency contribution
                if (rand(1, 100) <= 30) {
                    $type = $contributionTypes[rand(1, 2)]; // special or emergency
                    $extraDate = $date->copy()->addDays(rand(1, 10));

                    Contribution::updateOrCreate(
                        [
                            'member_id' => $member->id,
                            'contribution_date' => $extraDate->format('Y-m-d'),
                            'contribution_type' => $type,
                        ],
                        [
                            'amount' => rand(10, 50) * 100,
                            'status' => 'approved',
                        ]
                    );
                }
            }
        }
        $this->command->info('  ✓ Contributions seeded');

        // ──────────────────────────────────────────────
        // 5. LOANS (heavily favor active/approved loans)
        // ──────────────────────────────────────────────
        $loanPurposes = ['Personal', 'Education', 'Medical', 'Home Improvement', 'Business', 'Emergency', 'Other'];
        $loanTerms = ['3 months', '6 months', '12 months', '18 months', '24 months'];
        // 70% approved (active), 15% pending, 10% completed, 5% rejected
        $loanStatuses = ['approved', 'approved', 'approved', 'approved', 'approved', 'approved', 'approved', 'pending', 'pending', 'completed', 'rejected'];
        $sexOptions = ['Male', 'Female'];
        $maritalOptions = ['Single', 'Married', 'Widowed'];

        $loanMembers = array_values(array_filter($members, fn($m) => $m->status === 'active'));
        $createdLoans = [];
        $termMonthsMap = ['3 months' => 3, '6 months' => 6, '12 months' => 12, '18 months' => 18, '24 months' => 24];

        foreach ($loanMembers as $idx => $member) {
            $numLoans = $idx < 80 ? 2 : 1; // First 80 active members get 2 loans

            for ($l = 0; $l < $numLoans; $l++) {
                $status = $loanStatuses[array_rand($loanStatuses)];
                $term = $loanTerms[array_rand($loanTerms)];
                $termMonths = $termMonthsMap[$term];
                $amount = rand(5, 50) * 1000;

                // Ensure no overdue past 50 days: due_date must be >= now() - 50 days
                if (in_array($status, ['approved', 'completed', 'active'])) {
                    // 20% overdue (1-50 days), 80% not overdue
                    $isOverdue = (rand(1, 100) <= 20);
                    if ($isOverdue) {
                        $dueDate = Carbon::now()->subDays(rand(1, 50)); // Max 50 days overdue
                    } else {
                        $dueDate = Carbon::now()->addDays(rand(1, 180)); // Due in future
                    }
                    $approvalDate = $dueDate->copy()->subMonths($termMonths);
                    $applicationDate = $approvalDate->copy()->subDays(rand(2, 7));
                } else {
                    $applicationDate = Carbon::now()->subMonths(rand(1, 6))->subDays(rand(0, 28));
                    $approvalDate = ($status === 'rejected' && rand(0, 1)) ? $applicationDate->copy()->addDays(rand(2, 7)) : null;
                }

                $interestRate = 5;
                $interestAmount = round($amount * ($interestRate / 100), 2);
                $totalAmount = round($amount + $interestAmount, 2);
                $monthlyRepayment = round($totalAmount / $termMonths, 2);

                $loan = Loan::create([
                    'member_id' => $member->id,
                    'amount' => $amount,
                    'interest_rate' => $interestRate,
                    'interest_amount' => $interestAmount,
                    'total_amount' => $totalAmount,
                    'monthly_repayment' => $monthlyRepayment,
                    'term_months' => $termMonths,
                    'remaining_balance' => in_array($status, ['approved', 'active']) ? $totalAmount : ($status === 'completed' ? 0 : null),
                    'status' => $status,
                    'application_date' => $applicationDate,
                    'approval_date' => $approvalDate,
                    // Loan application personal info
                    'last_name' => $member->last_name,
                    'first_name' => $member->first_name,
                    'date_of_birth' => Carbon::now()->subYears(rand(25, 55))->subDays(rand(0, 364)),
                    'place_of_birth' => 'Metro Manila',
                    'nationality' => 'Filipino',
                    'sex' => $sexOptions[array_rand($sexOptions)],
                    'marital_status' => $maritalOptions[array_rand($maritalOptions)],
                    'citizenship' => 'Filipino',
                    'tin_number' => rand(100, 999) . '-' . rand(100, 999) . '-' . rand(100, 999),
                    'email' => $member->email,
                    'cell_phone' => $member->phone,
                    'barangay' => 'Brgy. ' . rand(1, 99),
                    'municipality_city' => 'Quezon City',
                    'province_state_country' => 'Metro Manila',
                    'zip_code' => '1' . rand(100, 199),
                    'nature_of_work' => $member->nature_of_work,
                    'employer_business_name' => $member->employer_business_name,
                    'source_of_fund' => 'Employment Salary',
                    'loan_purpose' => $loanPurposes[array_rand($loanPurposes)],
                    'desired_loan_amount' => (string) $amount,
                    'loan_term' => $term,
                ]);

                // Collateral: frozen amount = 15% of principal
                LoanCollateral::create([
                    'loan_id' => $loan->id,
                    'member_id' => $member->id,
                    'frozen_amount' => round($amount * 0.15, 2),
                ]);

                $createdLoans[] = $loan;
            }
        }
        $this->command->info('  ✓ ' . count($createdLoans) . ' Loans seeded');

        // ──────────────────────────────────────────────
        // 6. LOAN REPAYMENTS (for approved/completed loans)
        // ──────────────────────────────────────────────
        $paymentMethods = ['Cash', 'Bank Transfer', 'GCash', 'Maya'];
        $repaymentCount = 0;

        foreach ($createdLoans as $loan) {
            if (!in_array($loan->status, ['approved', 'completed', 'active'])) continue;
            if (!$loan->approval_date) continue;

            $approvalDate = Carbon::parse($loan->approval_date);
            $totalRepaid = 0;

            $termMonths = $loan->term_months ?? 12;
            $totalToRepay = $loan->total_amount ?? $loan->amount;
            $monthlyPayment = $loan->monthly_repayment ?? round($totalToRepay / $termMonths, 2);
            // For completed: pay in full. For approved (active): 0-50% of term so most have substantial remaining balance
            $numPayments = $loan->status === 'completed'
                ? $termMonths
                : (int) ceil($termMonths * (rand(0, 50) / 100)); // 0-50% of payments = active loan with balance

            for ($p = 1; $p <= $numPayments; $p++) {
                $paymentDate = $approvalDate->copy()->addMonths($p)->addDays(rand(0, 5));
                if ($paymentDate->isFuture()) break;

                $remaining = $totalToRepay - $totalRepaid;
                $payAmount = $p === $numPayments && $loan->status === 'completed'
                    ? $remaining
                    : $monthlyPayment + rand(-200, 200);
                $payAmount = max(100, min($payAmount, $remaining));

                if ($payAmount <= 0) break;

                LoanRepayment::create([
                    'loan_id' => $loan->id,
                    'amount' => round($payAmount, 2),
                    'payment_date' => $paymentDate,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'reference_number' => 'REP-' . strtoupper(Str::random(8)),
                ]);

                $totalRepaid += $payAmount;
                $repaymentCount++;

                if ($totalRepaid >= $totalToRepay) break;
            }

            // Update remaining balance (total amount minus repaid)
            $loan->remaining_balance = max(0, $totalToRepay - $totalRepaid);
            $loan->save();
        }
        $this->command->info("  ✓ {$repaymentCount} Loan Repayments seeded");

        // ──────────────────────────────────────────────
        // 7. DIVIDENDS (for last year and this year)
        // ──────────────────────────────────────────────
        $lastYear = now()->year - 1;
        $activeMembers = collect($members)->where('status', 'active');

        foreach ($activeMembers as $member) {
            $totalContribs = Contribution::where('member_id', $member->id)
                ->where('status', 'approved')
                ->whereYear('contribution_date', $lastYear)
                ->sum('amount');

            if ($totalContribs <= 0) {
                // Give them some contributions for last year so dividends work
                $totalContribs = rand(3, 12) * 1000;
            }

            $rate = 0.05; // 5% dividend rate
            $dividendAmount = $totalContribs * $rate;

            Dividend::updateOrCreate(
                ['member_id' => $member->id, 'year' => $lastYear],
                [
                    'total_contributions' => $totalContribs,
                    'dividend_rate' => $rate,
                    'dividend_amount' => $dividendAmount,
                    'status' => 'released',
                    'released_at' => Carbon::create($lastYear, 12, 28),
                ]
            );
        }

        // This year — pending dividends for some members
        $thisYearMembers = $activeMembers->take(10);
        foreach ($thisYearMembers as $member) {
            $totalContribs = Contribution::where('member_id', $member->id)
                ->where('status', 'approved')
                ->whereYear('contribution_date', now()->year)
                ->sum('amount');

            if ($totalContribs <= 0) continue;

            $rate = 0.05;
            Dividend::updateOrCreate(
                ['member_id' => $member->id, 'year' => now()->year],
                [
                    'total_contributions' => $totalContribs,
                    'dividend_rate' => $rate,
                    'dividend_amount' => $totalContribs * $rate,
                    'status' => 'pending',
                ]
            );
        }
        $this->command->info('  ✓ Dividends seeded');

        // ──────────────────────────────────────────────
        // 8. COOPERATIVE FUNDS (Cash on Hand + 3 Banks)
        // ──────────────────────────────────────────────
        CooperativeFund::updateOrCreate(
            ['fund_type' => 'cash', 'bank_name' => null],
            [
                'amount' => 185750.00,
                'description' => 'Petty cash and operational funds',
                'is_active' => true,
            ]
        );

        $bankAccounts = [
            ['bank_name' => 'BDO', 'account_number' => '0012-3456-7890', 'account_name' => 'MPC Cooperative - BDO Savings', 'amount' => 523400.00],
            ['bank_name' => 'Landbank', 'account_number' => '1234-5678-9012', 'account_name' => 'MPC Cooperative - LBP Current', 'amount' => 312850.00],
            ['bank_name' => 'RSB', 'account_number' => '9876-5432-1098', 'account_name' => 'MPC Cooperative - RSB Savings', 'amount' => 178600.00],
        ];

        foreach ($bankAccounts as $bank) {
            CooperativeFund::updateOrCreate(
                ['fund_type' => 'bank', 'bank_name' => $bank['bank_name']],
                [
                    'account_number' => $bank['account_number'],
                    'account_name' => $bank['account_name'],
                    'amount' => $bank['amount'],
                    'description' => $bank['bank_name'] . ' deposit account',
                    'is_active' => true,
                ]
            );
        }
        $this->command->info('  ✓ Cooperative Funds seeded');

        // ──────────────────────────────────────────────
        // 9. COOPERATIVE ANNOUNCEMENTS
        // ──────────────────────────────────────────────
        $announcements = [
            [
                'title' => 'Annual General Assembly 2026',
                'description' => 'All members are required to attend the Annual General Assembly. Topics include financial report, board elections, and upcoming cooperative programs. Light snacks will be served.',
                'type' => 'meeting',
                'scheduled_date' => Carbon::now()->addWeeks(3)->format('Y-m-d'),
                'scheduled_time' => '09:00 AM',
                'location' => 'MPC Cooperative Hall, 2nd Floor',
                'priority' => 'high',
                'is_active' => true,
            ],
            [
                'title' => 'Board of Directors Election',
                'description' => 'Election of new Board of Directors for the term 2026-2028. Members who wish to nominate themselves must submit their candidacy form before the deadline.',
                'type' => 'election',
                'scheduled_date' => Carbon::now()->addMonth()->format('Y-m-d'),
                'scheduled_time' => '01:00 PM',
                'location' => 'MPC Cooperative Hall',
                'priority' => 'urgent',
                'is_active' => true,
            ],
            [
                'title' => 'New Emergency Loan Program',
                'description' => 'The cooperative is launching a new Emergency Loan Program with lower interest rates and faster processing for medical emergencies and natural disasters.',
                'type' => 'offering',
                'scheduled_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'scheduled_time' => null,
                'location' => null,
                'priority' => 'normal',
                'is_active' => true,
            ],
            [
                'title' => 'Monthly Membership Meeting',
                'description' => 'Regular monthly meeting to discuss cooperative progress, financial standing, and member concerns. Open forum will follow the presentation.',
                'type' => 'meeting',
                'scheduled_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'scheduled_time' => '02:00 PM',
                'location' => 'MPC Conference Room',
                'priority' => 'normal',
                'is_active' => true,
            ],
            [
                'title' => 'Special Savings Program',
                'description' => 'Introducing the "Future Fund" special savings program with a 3% annual bonus interest for deposits maintained over 12 months.',
                'type' => 'offering',
                'scheduled_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'scheduled_time' => null,
                'location' => null,
                'priority' => 'normal',
                'is_active' => true,
            ],
            [
                'title' => 'Holiday Schedule Notice',
                'description' => 'The cooperative office will be closed on February 25 (EDSA Anniversary) and April 9-10 (Araw ng Kagitingan / Good Friday). Please plan your transactions accordingly.',
                'type' => 'general',
                'scheduled_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'scheduled_time' => null,
                'location' => null,
                'priority' => 'low',
                'is_active' => true,
            ],
        ];

        foreach ($announcements as $a) {
            CooperativeAnnouncement::updateOrCreate(
                ['title' => $a['title']],
                $a
            );
        }
        $this->command->info('  ✓ 6 Announcements seeded');

        // ──────────────────────────────────────────────
        // 10. NOTIFICATIONS (for members)
        // ──────────────────────────────────────────────
        $notifTypes = [
            ['type' => 'loan_approved', 'data' => ['title' => 'Loan Approved', 'message' => 'Your loan application has been approved. Please visit the cooperative office to claim your disbursement.', 'icon' => 'fa-check-circle']],
            ['type' => 'loan_rejected', 'data' => ['title' => 'Loan Application Update', 'message' => 'Your loan application has been reviewed. Unfortunately, it was not approved at this time. Please contact the office for details.', 'icon' => 'fa-times-circle']],
            ['type' => 'contribution_received', 'data' => ['title' => 'Contribution Received', 'message' => 'Your monthly contribution has been received and recorded. Thank you for your commitment!', 'icon' => 'fa-coins']],
            ['type' => 'loan_due_reminder', 'data' => ['title' => 'Loan Payment Reminder', 'message' => 'Your loan payment is due in 5 days. Please ensure timely payment to avoid penalties.', 'icon' => 'fa-bell']],
            ['type' => 'meeting_notice', 'data' => ['title' => 'Meeting Notice', 'message' => 'You are invited to attend the Annual General Assembly on ' . Carbon::now()->addWeeks(3)->format('M d, Y') . ' at 9:00 AM.', 'icon' => 'fa-calendar']],
            ['type' => 'dividend_released', 'data' => ['title' => 'Dividend Released', 'message' => 'Your annual dividend has been calculated and released. Check your account for details.', 'icon' => 'fa-gift']],
            ['type' => 'account_update', 'data' => ['title' => 'Account Updated', 'message' => 'Your profile information has been updated by the administrator.', 'icon' => 'fa-user-edit']],
            ['type' => 'system', 'data' => ['title' => 'Welcome to MPC!', 'message' => 'Welcome to the Multi-Purpose Cooperative Management System. We are glad to have you as a member!', 'icon' => 'fa-hand-peace']],
        ];

        foreach ($members as $idx => $member) {
            // Each member gets 3-6 notifications
            $numNotifs = rand(3, 6);
            $shuffled = collect($notifTypes)->shuffle()->take($numNotifs);

            foreach ($shuffled as $nIdx => $notif) {
                $daysAgo = rand(0, 60);
                Notification::create([
                    'type' => $notif['type'],
                    'notifiable_type' => 'App\\Models\\Member',
                    'notifiable_id' => $member->id,
                    'data' => $notif['data'],
                    'read_at' => $nIdx < 2 ? Carbon::now()->subDays(rand(0, $daysAgo)) : null, // First 2 are read
                    'created_at' => Carbon::now()->subDays($daysAgo),
                    'updated_at' => Carbon::now()->subDays($daysAgo),
                ]);
            }
        }
        $this->command->info('  ✓ Notifications seeded');

        // ──────────────────────────────────────────────
        // 11. ACTIVITY LOGS (realistic activity history)
        // ──────────────────────────────────────────────
        $activityTemplates = [
            ['activity_type' => 'login', 'description' => 'Member logged in to the system'],
            ['activity_type' => 'logout', 'description' => 'Member logged out of the system'],
            ['activity_type' => 'profile_update', 'description' => 'Member updated their profile information'],
            ['activity_type' => 'contribution', 'description' => 'Monthly contribution payment submitted'],
            ['activity_type' => 'loan_application', 'description' => 'New loan application submitted'],
            ['activity_type' => 'loan_payment', 'description' => 'Loan repayment submitted'],
            ['activity_type' => 'password_change', 'description' => 'Member changed their account password'],
            ['activity_type' => 'report_viewed', 'description' => 'Member viewed their financial report'],
        ];

        $ips = ['192.168.1.10', '192.168.1.25', '10.0.0.15', '172.16.0.100', '127.0.0.1'];
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) Safari/605.1.15',
            'Mozilla/5.0 (Android 14; Mobile) Chrome/120.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) Safari/17.0',
        ];

        // Keep existing activity logs and add new ones
        foreach ($members as $member) {
            $numActivities = rand(8, 20);
            for ($a = 0; $a < $numActivities; $a++) {
                $template = $activityTemplates[array_rand($activityTemplates)];
                $daysAgo = rand(0, 90);

                ActivityLog::create([
                    'member_id' => $member->id,
                    'activity_type' => $template['activity_type'],
                    'description' => $template['description'],
                    'ip_address' => $ips[array_rand($ips)],
                    'user_agent' => $agents[array_rand($agents)],
                    'created_at' => Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                ]);
            }
        }
        $this->command->info('  ✓ Activity Logs seeded');

        // ──────────────────────────────────────────────
        // SUMMARY
        // ──────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('=== SEED SUMMARY ===');
        $this->command->info('  Members:        ' . Member::count());
        $this->command->info('  User Accounts:  ' . User::count());
        $this->command->info('  Contributions:  ' . Contribution::count());
        $this->command->info('  Loans:          ' . Loan::count());
        $this->command->info('  Repayments:     ' . LoanRepayment::count());
        $this->command->info('  Dividends:      ' . Dividend::count());
        $this->command->info('  Notifications:  ' . Notification::count());
        $this->command->info('  Funds:          ' . CooperativeFund::count());
        $this->command->info('  Announcements:  ' . CooperativeAnnouncement::count());
        $this->command->info('  Activity Logs:  ' . ActivityLog::count());
        $this->command->newLine();
        $this->command->info('All dummy data seeded successfully!');
        $this->command->info('Admin login:  admin@gmail.com / admin1234');
        $firstMember = $members[0] ?? null;
        $this->command->info('Member login: ' . ($firstMember ? $firstMember->email : 'member@mpc.local') . ' / member1234');
    }
}
