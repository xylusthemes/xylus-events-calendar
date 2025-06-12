<?php
/**
 * Admin Shortcode page
 *
 * @package     Easy_Events_Calendar
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2025, Rajat Patel
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$ShortcodeTable = new XTEC_Shortcode_List_Table();
$ShortcodeTable->prepare_items();

?>
<div>
    <?php $ShortcodeTable->display(); ?>
</div>