<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('couriers')->insert([
            ['courier_name' => 'TCS', 'courier_tracking_url' => 'https://www.tcsexpress.com/track', 'courier_contact_number' => '021-111-123-456', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'OCS', 'courier_tracking_url' => 'https://www.ocscourier.com/track', 'courier_contact_number' => '021-111-222-333', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'Leopard Courier', 'courier_tracking_url' => 'https://www.leopardscourier.com/leopards-tracking', 'courier_contact_number' => '021-111-300-786', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'Pakistan Post', 'courier_tracking_url' => 'http://ep.gov.pk/', 'courier_contact_number' => '051-111-111-117', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'BlueEx', 'courier_tracking_url' => 'https://www.blue-ex.com/track/', 'courier_contact_number' => '021-111-258-339', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'M&P (Muller & Phipps)', 'courier_tracking_url' => 'https://www.mulphilog.com/track-consignment', 'courier_contact_number' => '021-111-202-202', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'Call Courier', 'courier_tracking_url' => 'https://callcourier.com.pk/track/', 'courier_contact_number' => '042-111-786-227', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'Trax', 'courier_tracking_url' => 'https://trax.pk/track/', 'courier_contact_number' => '042-111-11-8729', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'Rider', 'courier_tracking_url' => 'https://www.rider.pk/track', 'courier_contact_number' => '021-111-117-433', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'Swift Logistics', 'courier_tracking_url' => 'https://swiftlogistics.com.pk/', 'courier_contact_number' => '021-111-794-438', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'DHL', 'courier_tracking_url' => 'https://www.dhl.com/pk-en/home/tracking.html', 'courier_contact_number' => '021-111-345-111', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'FedEx', 'courier_tracking_url' => 'https://www.fedex.com/en-pk/tracking.html', 'courier_contact_number' => '021-111-003-339', 'created_at' => $now, 'updated_at' => $now],
            ['courier_name' => 'UPS', 'courier_tracking_url' => 'https://www.ups.com/track', 'courier_contact_number' => '021-111-002-885', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
