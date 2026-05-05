<?php
$str = file_get_contents('app/Models/Report.php');

$old = <<<PHP
    public function getHasDuplicateExpensesAttribute(): bool
    {
        foreach (\$this->expenses as \$expense) {
            // A duplicate expense shares the exact same amount and provider_rut 
            // but is a different record in the DB
            \$isDupe = \App\Models\Expense::where('amount', \$expense->amount)
                ->where('provider_rut', \$expense->provider_rut)
                ->where('id', '!=', \$expense->id)
                ->exists();
                
            if (\$isDupe) {
                return true;
            }
        }
        return false;
    }
PHP;

$new = <<<PHP
    public function getHasDuplicateExpensesAttribute(): bool
    {
        foreach (\$this->expenses as \$expense) {
            // A duplicate expense shares the exact same amount and provider_rut 
            // but is a different record in the DB
            \$isDupe = \App\Models\Expense::where('amount', \$expense->amount)
                ->where('provider_rut', \$expense->provider_rut)
                ->where('id', '!=', \$expense->id)
                ->exists();
                
            if (\$isDupe) {
                return true;
            }
        }
        return false;
    }

    public function getDuplicateReportsAttribute()
    {
        \$duplicateReportIds = collect();
        foreach (\$this->expenses as \$expense) {
            \$dupes = \App\Models\Expense::where('amount', \$expense->amount)
                ->where('provider_rut', \$expense->provider_rut)
                ->where('id', '!=', \$expense->id)
                ->pluck('report_id');
            \$duplicateReportIds = \$duplicateReportIds->merge(\$dupes);
        }
        
        return \App\Models\Report::whereIn('id', \$duplicateReportIds->unique())->where('id', '!=', \$this->id)->get();
    }
PHP;

$str = str_replace($old, $new, $str);
file_put_contents('app/Models/Report.php', $str);
