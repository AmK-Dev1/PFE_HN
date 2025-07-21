<?php
namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class BudgetsImport implements ToCollection
{
    public $budgets = [];

    public function collection(Collection $rows)
    {


        // If the first row is a header, skip it; otherwise, treat all rows as data
        foreach ($rows as $index => $row) {
            // If the first row is a header, skip the first row
            if (!is_numeric($row[2]) && !is_numeric($row[3] ) && !is_numeric($row[4] )) {
                continue;
            }

            // Ensure debit and credit are numeric before calculation
            $debit = is_numeric($row[2] ?? null) ? (float) $row[2] : 0;
            $credit = is_numeric($row[3] ?? null) ? (float) $row[3] : 0;

            // Determine amount based on conditions
            $amount = ($debit === 0 && $credit === 0)
                ? (is_numeric($row[4] ?? null) ? (float) $row[4] : 0)  // Use row[4] if available and numeric, otherwise 0
                : $debit - $credit;

            // Handle both data rows the same way regardless of headers
            $this->budgets[] = [
                'code' => $row[0] ?? null,  // First column is 'code'
                'description' => $row[1] ?? null,  // Second column is 'description'
                'debit' => $row[2] ?? 0,  // Third column is 'debit'
                'credit' => $row[3] ?? 0,  // Fourth column is 'credit'
                'amount' => $amount,  // Calculate the 'amount' based on debit and credit
            ];
        }
    }

    // Return the collected budgets to be previewed
    public function getBudgets()
    {
        return $this->budgets;
    }
}

