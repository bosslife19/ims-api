<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->items);
    }

    /**
     * @return array
     */
    public function headings(): array
   

    {
        return [
            'Name',
            'Category',
            'Quantity',
            // Add more headings as needed
        ];
    }
}
