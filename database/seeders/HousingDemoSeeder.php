<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Project;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HousingDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $harihar = Project::query()->create(['name' => 'Harihar', 'code' => 'HARIHAR', 'status' => 'active']);
        $harmony = Project::query()->create(['name' => 'Harmony', 'code' => 'HARMONY', 'status' => 'active']);
        $swastic = Project::query()->create(['name' => 'Swastic', 'code' => 'SWASTIC', 'status' => 'active']);

        $makeProperty = function (int $projectId, string $type, int $i, string $projectCode) use ($now) {
            $codePrefix = $type === 'house' ? 'H' : 'S';

            return [
                'project_id' => $projectId,
                'code' => $projectCode . '-' . $codePrefix . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'type' => $type,
                'title' => ($type === 'house' ? 'House' : 'Shop') . ' ' . $i,
                'city' => fake()->randomElement(['Delhi', 'Mumbai', 'Pune', 'Ahmedabad', 'Jaipur', 'Surat', 'Indore']),
                'address' => fake()->streetAddress(),
                'bedrooms' => $type === 'house' ? fake()->numberBetween(1, 6) : null,
                'area_sqft' => fake()->numberBetween($type === 'house' ? 600 : 200, $type === 'house' ? 3800 : 1200),
                'price_per_day' => $type === 'house'
                    ? fake()->randomFloat(2, 1200, 6500)
                    : fake()->randomFloat(2, 800, 9000),
                'status' => 'available',
                'description' => fake()->sentence(12),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        };

        $properties = [];

        // Harihar: 120 houses, 25 shops
        for ($i = 1; $i <= 120; $i++) {
            $properties[] = $makeProperty($harihar->id, 'house', $i, 'HARIHAR');
        }
        for ($i = 1; $i <= 25; $i++) {
            $properties[] = $makeProperty($harihar->id, 'shop', $i, 'HARIHAR');
        }

        // Harmony: 55 houses, 15 shops
        for ($i = 1; $i <= 55; $i++) {
            $properties[] = $makeProperty($harmony->id, 'house', $i, 'HARMONY');
        }
        for ($i = 1; $i <= 15; $i++) {
            $properties[] = $makeProperty($harmony->id, 'shop', $i, 'HARMONY');
        }

        // Swastic: 25 houses, 10 shops
        for ($i = 1; $i <= 25; $i++) {
            $properties[] = $makeProperty($swastic->id, 'house', $i, 'SWASTIC');
        }
        for ($i = 1; $i <= 10; $i++) {
            $properties[] = $makeProperty($swastic->id, 'shop', $i, 'SWASTIC');
        }

        // Insert properties
        Property::query()->insert($properties);

        // Create demo bookings (random subset)
        $propertyRows = Property::query()->inRandomOrder()->limit(120)->get(['id', 'project_id', 'price_per_day']);
        $userIds = User::query()->whereNotNull('project_id')->pluck('id')->all();

        $bookings = [];
        foreach ($propertyRows as $propertyRow) {
            $start = Carbon::today()->subDays(fake()->numberBetween(0, 45))->addDays(fake()->numberBetween(0, 25));
            $days = fake()->numberBetween(1, 10);
            $end = (clone $start)->addDays($days);

            $price = (float) $propertyRow->price_per_day;
            $total = round($price * $days, 2);

            $bookings[] = [
                'project_id' => $propertyRow->project_id,
                'property_id' => $propertyRow->id,
                'user_id' => empty($userIds) ? null : fake()->randomElement($userIds),
                'customer_name' => fake()->name(),
                'customer_phone' => fake()->phoneNumber(),
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'total_amount' => $total,
                'status' => fake()->randomElement(['confirmed', 'confirmed', 'confirmed', 'pending', 'cancelled']),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Booking::query()->insert($bookings);

        // Update some properties as booked if they have confirmed/pending bookings
        $bookedPropertyIds = Booking::query()
            ->whereIn('status', ['confirmed', 'pending'])
            ->distinct()
            ->pluck('property_id')
            ->all();

        Property::query()
            ->whereIn('id', $bookedPropertyIds)
            ->update(['status' => 'booked', 'updated_at' => $now]);

        // Project-specific login users
        User::query()->create([
            'name' => 'Harihar Manager',
            'email' => 'harihar.manager@gmail.com',
            'password' => bcrypt('123456'),
            'project_id' => $harihar->id,
            'country_id' => null,
        ]);

        User::query()->create([
            'name' => 'Harmony Manager',
            'email' => 'harmony.manager@gmail.com',
            'password' => bcrypt('123456'),
            'project_id' => $harmony->id,
            'country_id' => null,
        ]);

        User::query()->create([
            'name' => 'Swastic Manager',
            'email' => 'swastic.manager@gmail.com',
            'password' => bcrypt('123456'),
            'project_id' => $swastic->id,
            'country_id' => null,
        ]);
    }
}
