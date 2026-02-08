<?php
declare(strict_types=1);

namespace App\Exports;

use App\Models\TransPo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

final class TransPoExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithCustomValueBinder, WithEvents
{
    private Collection $items;
    private int $rowNumber = 0;

    public function bindValue(Cell $cell, $value): bool
    {
        if (in_array($cell->getColumn(), ['D'], true)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Kode PO',
            'Customer',
            'WA',
            'Qty',
            'Gramasi',
            'Total Amount (IDR)',
            'Status',
            'Resi Number',
        ];
    }

    public function collection(): Collection
    {
        return $this->items->sortBy(function ($po) {
            $name = (string) (optional($po->customer)->full_name ?? '');
            return Str::lower(trim($name));
        });
    }

    public function map($po): array
    {
        $waRaw = optional($po->customer)->phone_wa;
        $waDigits = $waRaw ? preg_replace('/\D+/', '', $waRaw) : null;
        if ($waDigits && substr($waDigits, 0, 1) === '0') {
            $waDigits = '62' . substr($waDigits, 1);
        }

        $totalGram = (float) ($po->total_gram ?? 0);
        $qty = (int) ($po->qty ?? 0);
        $no = ++$this->rowNumber;

        return [
            $no,
            (string) ($po->kode_po ?? ''),
            (string) (optional($po->customer)->full_name ?? ''),
            (string) ($waDigits ?? ''),
            $qty,
            number_format($totalGram, 3, '.', ''),
            (float) ($po->total_amount ?? 0),
            (string) ($po->status ?? ''),
            (string) ($po->resi_number ?? ''),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sorted = $this->items->sortBy(function ($po) {
                    $name = (string) (optional($po->customer)->full_name ?? '');
                    return Str::lower(trim($name));
                })->values();

                $count = $sorted->count();
                if ($count <= 0) { return; }

                $white = ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFFFF']]];
                $grey  = ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF7F7F7']]];

                $currentRow = 2;
                $groupStart = 2;
                $prevName = null;
                $useGrey = false;

                foreach ($sorted as $po) {
                    $name = (string) (optional($po->customer)->full_name ?? '');
                    if ($prevName === null) {
                        $prevName = $name;
                        $groupStart = $currentRow;
                    } elseif ($name !== $prevName) {
                        $style = $useGrey ? $grey : $white;
                        $sheet->getStyle('A'.$groupStart.':I'.($currentRow-1))->applyFromArray($style);
                        $useGrey = !$useGrey;
                        $prevName = $name;
                        $groupStart = $currentRow;
                    }
                    $currentRow++;
                }

                $style = $useGrey ? $grey : $white;
                $sheet->getStyle('A'.$groupStart.':I'.($currentRow-1))->applyFromArray($style);
            },
        ];
    }
}