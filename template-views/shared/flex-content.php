<?php
declare(strict_types=1);

$flexible_content = get_field('content');

if ($flexible_content) {
    foreach ($flexible_content as $fcBlock) {
        switch ($fcBlock['acf_fc_layout']) {
            case 'banner':
                fws()->render()->templateView($fcBlock, 'banner');
                break;
            case 'slider':
                fws()->render()->templateView($fcBlock, 'slider');
                break;
            case 'vue_block':
                fws()->render()->templateView($fcBlock, 'vue-block');
                break;
            default:
                fws()->render()->templateView($fcBlock, 'basic-block');
        }
    }
}
