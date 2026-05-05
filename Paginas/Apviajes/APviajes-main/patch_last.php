<?php
$actualPath = \App\Models\Expense::where('attachment_path', 'not like', '%dummy%')
              ->where('attachment_path', '!=', 'pendiente')
              ->pluck('attachment_path')
              ->first();

if($actualPath) {
    \App\Models\Expense::where('attachment_path', 'dummy/path')->update(['attachment_path' => $actualPath]);
    echo "Updated dummy attachments to: " . $actualPath;
}
