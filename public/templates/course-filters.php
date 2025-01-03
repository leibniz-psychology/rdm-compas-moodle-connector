<?php
/**
 * The template for displaying course page filters.
 *
 * @package RDM Compas Moodle Connector.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<form class="eb_filter_and_sorting" method="get">
	<input type="hidden" name="eb_courses_page_key" value="<?php echo esc_attr( wp_create_nonce( 'eb_courses_page_key' ) ); ?>">

	<div class='eb_archive_filter'>
		<label class="eb_archive_filter_lbl"> <?php echo esc_html_e( 'Filter Courses by Category:', 'rdmcompas-moodle-connector' ); ?> </label>
		<select name="eb_category_filter" id="eb_category_filter" class="" >
			<option value="eb_archive_filter_all" <?php selected( $filter, 'eb_archive_filter_all' ); ?>><?php echo esc_html_e( 'All', 'rdmcompas-moodle-connector' ); ?></option>

			<?php foreach ( $categories as $eb_cat_id => $eb_cat_name ) : ?>
				<option value="<?php echo esc_attr( $eb_cat_id ); ?>" <?php selected( $filter, $eb_cat_id ); ?>><?php echo esc_html( $eb_cat_name ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class='eb_archive_sort'>
		<label class="eb_archive_sort_lbl"> <?php echo esc_html_e( 'Sort by:', 'rdmcompas-moodle-connector' ); ?> </label>
		<select name="eb_category_sort" id="eb_category_sort" class="" >
			<option value="eb_archive_sort_a_z" <?php selected( $sorting, 'eb_archive_sort_a_z' ); ?>><?php echo esc_html_e( 'Default A to Z', 'rdmcompas-moodle-connector' ); ?></option>
			<option value="eb_archive_sort_z_a" <?php selected( $sorting, 'eb_archive_sort_z_a' ); ?>><?php echo esc_html_e( 'Sort Z to A', 'rdmcompas-moodle-connector' ); ?></option>
			<option value="eb_archive_latest" <?php selected( $sorting, 'eb_archive_latest' ); ?>><?php echo esc_html_e( 'Sort by latest', 'rdmcompas-moodle-connector' ); ?></option>
			<option value="eb_archive_oldest" <?php selected( $sorting, 'eb_archive_oldest' ); ?>><?php echo esc_html_e( 'Sort by oldest', 'rdmcompas-moodle-connector' ); ?></option>
		</select>
	</div>
</form>
