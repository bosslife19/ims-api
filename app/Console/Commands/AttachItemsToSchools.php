<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AllSchools;
use App\Models\NewItem;

class AttachItemsToSchools extends Command
{
    protected $signature = 'items:attach-to-schools';
    protected $description = 'Attach items to their respective schools based on the school field';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Fetch all items
       // Fetch all items that are not already attached to a school
$items = NewItem::whereNull('school')->get();

foreach ($items as $item) {
    // Find the school that matches the item’s school
    $school = AllSchools::where('SCHOOL_NAME', $item->school)->first();

    if ($school) {
        // Attach the item to the school if not already attached
        if (!$school->newItems()->where('id', $item->id)->exists()) {
            $school->newItems()->save($item);
          
            $this->info("Attached item ID {$item->id} to school ID {$school->id}");
        } else {
            $this->info("Item ID {$item->id} is already attached to school ID {$school->id}");
        }
    } else {
        $this->info("No school found for item ID {$item->id} with school name {$item->school}");
    }
}

       
    }
}
