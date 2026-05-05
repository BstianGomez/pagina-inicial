<?php
$file = 'resources/views/reports/create-step2.blade.php';
$content = file_get_contents($file);

$old_js = <<<JS
            if (assignedNum > 0) {
                assignedPanel.classList.add('has-items');
                btnNext.style.display    = 'inline-flex';
                noNextHint.style.display = 'none';
                step2dot.style.cssText   = 'background:#22c55e;color:#fff;cursor:default;';
                step2dot.onclick = () => { /* noop on the button submit flow */ };
                connector.style.background = '#22c55e';
            } else {
                assignedPanel.classList.remove('has-items');
                btnNext.style.display    = 'none';
                noNextHint.style.display = 'block';
                step2dot.style.cssText   = 'background:#e2e8f0;color:#94a3b8;cursor:not-allowed;';
                step2dot.onclick = null;
                connector.style.background = '#cbd5e1';
            }
JS;

$new_js = <<<JS
            if (assignedNum > 0) {
                assignedPanel.classList.add('has-items');
                btnNext.style.display    = 'inline-flex';
                noNextHint.style.display = 'none';
                step2dot.style.cssText   = 'background:#0f6bb6;color:#fff;cursor:default;';
                step2dot.onclick = () => { /* noop on the button submit flow */ };
                connector.style.background = '#10b981';
            } else {
                assignedPanel.classList.remove('has-items');
                btnNext.style.display    = 'none';
                noNextHint.style.display = 'block';
                step2dot.style.cssText   = 'background:#0f6bb6;color:#fff;cursor:default;';
                step2dot.onclick = null;
                connector.style.background = '#10b981';
            }
JS;

$content = str_replace($old_js, $new_js, $content);
file_put_contents($file, $content);
