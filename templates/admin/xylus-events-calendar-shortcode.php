<?php
/**
 * Admin Shortcode page
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2025, Rajat Patel
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$Xylusec_ShortcodeTable = new XYLUSEC_Shortcode_List_Table();
$Xylusec_ShortcodeTable->prepare_items();

?>
<div>
    <?php $Xylusec_ShortcodeTable->display(); ?>
</div>