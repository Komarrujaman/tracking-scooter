<?php

namespace App\Console\Commands;

use App\Models\Scooter;
use App\Models\Passenger;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class UpdateScooterStatus extends Command
{
    protected $signature = 'scooter:update-status';
    protected $description = 'Update scooter status based on passenger end time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Menampilkan waktu sekarang menggunakan Carbon
        $now = Carbon::now();
        $this->info('Current time: ' . $now->toDateTimeString());

        $this->info('Starting scooter update status command...');

        // Ambil data terakhir berdasarkan scooter_id
        $subQuery = Passenger::selectRaw('MAX(id) as id')
            ->groupBy('scooter_id')
            ->pluck('id');

        // Ambil semua passengers berdasarkan subquery
        $passengers = Passenger::whereIn('id', $subQuery)
            ->where('end', '<', $now)
            ->get();

        $this->info($passengers);

        foreach ($passengers as $passenger) {
            // Ambil scooter berdasarkan id scooter dari passenger
            $scooter = Scooter::find($passenger->scooter_id);

            if ($scooter && !$scooter->status) {
                // Menampilkan waktu end dari Passenger
                $endTime = Carbon::parse($passenger->end);
                $this->info('End time for Passenger ID ' . $passenger->id . ': ' . $endTime->toDateTimeString());

                // Periksa apakah waktu sewa sudah berakhir
                if ($endTime->isPast()) {
                    // Ubah status scooter menjadi true karena waktu sewa telah berakhir
                    $scooter->status = true;
                    $scooter->save();
                    $this->info('Updated scooter status for scooter ID: ' . $scooter->id);
                }
            }
        }

        $this->info('Scooter statuses updated successfully!');
    }
}
