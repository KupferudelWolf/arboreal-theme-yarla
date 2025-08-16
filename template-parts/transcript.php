<table class="transcript">
    <?php
    global $TRANSCRIPT;
    $panel = false;
    $panel_last = false;
    foreach ($TRANSCRIPT as $item): ?>
        <tr<?php
        $panel = preg_replace('/(?:Panel )([0-9]+).*/', '$1', $item['speaker']);
        if ($panel != $panel_last) {
            echo ' class="new-panel"';
        } ?>>
            <th><?php
            $speaker = $item['speaker'];
            if ($speaker) {
                $speaker = preg_replace('/Panel ([0-9])/', 'Panel&nbsp;$1', $speaker);
                $speaker = preg_replace('/(Panel\s[0-9])+(?:, )?(.)?/', 'Panel&nbsp;$1</br>$2', $speaker);
                echo $speaker;
            }
            ?></th>
            <th><?php echo $item['content']; ?></th>
            </tr>
            <?php
            $panel_last = $panel;
    endforeach; ?>
</table>