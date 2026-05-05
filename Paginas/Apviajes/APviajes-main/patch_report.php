<?php
$file = 'app/Models/Report.php';
$content = file_get_contents($file);

$insert = <<<PHP

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

if (strpos($content, 'getHasDuplicateExpensesAttribute') === false) {
    $content = str_replace('public function expenses()', $insert . "\n\n    public function expenses()", $content);
    file_put_contents($file, $content);
    echo "Added attribute\n";
}
