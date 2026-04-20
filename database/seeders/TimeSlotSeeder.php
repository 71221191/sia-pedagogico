<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $slots = [
            // MAÑANA
            ['shift' => 'mañana', 'order' => 1, 'start_time' => '07:00', 'end_time' => '07:45', 'is_break' => false],
            ['shift' => 'mañana', 'order' => 2, 'start_time' => '07:45', 'end_time' => '08:30', 'is_break' => false],
            ['shift' => 'mañana', 'order' => 3, 'start_time' => '08:30', 'end_time' => '09:15', 'is_break' => false],
            ['shift' => 'mañana', 'order' => 4, 'start_time' => '09:15', 'end_time' => '10:00', 'is_break' => false],
            ['shift' => 'mañana', 'order' => 0, 'start_time' => '10:00', 'end_time' => '10:40', 'is_break' => true], // RECREO
            ['shift' => 'mañana', 'order' => 5, 'start_time' => '10:40', 'end_time' => '11:25', 'is_break' => false],
            ['shift' => 'mañana', 'order' => 6, 'start_time' => '11:25', 'end_time' => '12:10', 'is_break' => false],

            // TARDE
            ['shift' => 'tarde', 'order' => 1, 'start_time' => '13:00', 'end_time' => '13:45', 'is_break' => false],
            ['shift' => 'tarde', 'order' => 2, 'start_time' => '13:45', 'end_time' => '14:30', 'is_break' => false],
            ['shift' => 'tarde', 'order' => 0, 'start_time' => '14:30', 'end_time' => '15:10', 'is_break' => true], // RECREO
            ['shift' => 'tarde', 'order' => 3, 'start_time' => '15:10', 'end_time' => '15:55', 'is_break' => false],
            ['shift' => 'tarde', 'order' => 4, 'start_time' => '15:55', 'end_time' => '16:40', 'is_break' => false],
            ['shift' => 'tarde', 'order' => 5, 'start_time' => '16:40', 'end_time' => '17:25', 'is_break' => false],
            ['shift' => 'tarde', 'order' => 6, 'start_time' => '17:25', 'end_time' => '18:10', 'is_break' => false],
        ];

        foreach ($slots as $slot) {
            \App\Models\TimeSlot::create($slot);
        }
    }
}
