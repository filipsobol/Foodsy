<?php

use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Supplier::class, 5)
            ->create()
            ->each(function ($supplier, $index) {
                $supplier->update([
                    "name"      => "Test supplier {$index}",
                    "slug"      => "test-supplier-{$index}"
                ]);

                $supplier
                    ->locations()
                    ->attach(array_unique([$index + 1, 5 - $index]));
            });
    }
}
