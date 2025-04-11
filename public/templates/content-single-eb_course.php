<?php
/**
 * The template for displaying single course content.
 *
 * This template can be overridden by copying it to yourtheme/rdmcompas-moodle-connector/
 *
 * @version     1.2.0
 * @package     eb_course
 */

namespace app\wisdmlabs\edwiserBridge;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Variables.
global $post;

/*
 * Filter to get all the initial infos i.e all initial variables which will be used while showing on single course page.
 *
 */
$single_course_data = apply_filters( 'eb_content_single_course_before', $post->ID );
//array:
//'eb_plugin_url'
//'categories'
//'course_institution'
//'course_contact_person'
//'course_date_start'
//'course_date_modified'
//'course_format'
//'course_target_group'
//'course_discipline'
//'course_number_participants'
//'course_duration'
//'course_required_material'
//'course_persistent_identifier'
//'course_license'
//'moodle_course_url'
?>

<meta itemprop="url" content="<?php echo esc_url( $single_course_data['moodle_course_url'] ); ?>"/>
<meta itemprop="educationalUse" content="<?php echo esc_html( $single_course_data['course_format'] ); ?>"/>
<span itemprop="hasCourseInstance" itemscope itemtype="http://schema.org/CourseInstance">
    <meta itemprop="startDate" content="<?php echo esc_html( $single_course_data['course_date_start'] ); ?>"/>
    <meta itemprop="courseMode" content="<?php echo esc_html( $single_course_data['course_format'] ); ?>"/>
    <meta itemprop="instructor" content="<?php echo esc_html( $single_course_data['course_contact_person'] ); ?>"/>
</span>

<!-- Curse details wrapper. -->
<div class="rdm-tc-course-overview-wrapper">

    <!-- Course overview wrapper -->
    <div class="rdm-tc-course-overview">
        <h4><?php echo __( "Overview", 'rdmcompas-moodle-connector' ) ?></h4>
		<?php
		if ( ! is_search() ) {
			echo '<p itemprop="audience" itemscope itemtype="http://schema.org/Audience"><strong>' . __( 'Target', 'rdmcompas-moodle-connector' ) . '</strong>: <span itemprop="audienceType">' . $single_course_data['course_target_group'] . '</span></p>';
			echo '<p><strong>' . __( 'Data Curation Lifecycle steps', 'rdmcompas-moodle-connector' ) . '</strong>: ' . $single_course_data['course_discipline'] . '</p>';
			echo '<p><strong>' . __( 'License', 'rdmcompas-moodle-connector' ) . '</strong>: <span itemprop="license">' . getCCLicense( $single_course_data['course_license'] ) . '</span></p>';
			if ( $single_course_data['course_required_material'] ) {
				echo '<p><strong>' . __( 'Required Material', 'rdmcompas-moodle-connector' ) . '</strong>: <span itemprop="coursePrerequisites">' . $single_course_data['course_required_material'] . '</span></p>';
			}
			echo '<p><strong>' . __( 'Previous experience', 'rdmcompas-moodle-connector' ) . '</strong>: <span itemprop="educationalLevel">' . $single_course_data['course_previous_experience'] . '</span></p>';
			if ( $single_course_data['course_persistent_identifier'] ) {
				echo '<p><strong>' . __( 'Persistent Identifier', 'rdmcompas-moodle-connector' ) . '</strong>: <span itemprop="courseCode">' . $single_course_data['course_persistent_identifier'] . '</span></p>';
			}
			echo '<p><strong>' . __( 'Duration', 'rdmcompas-moodle-connector' ) . '</strong>: <span itemprop="timeRequired">' . $single_course_data['course_duration'] . '</span></p>';
			if ( $single_course_data['course_format'] == "Webinar blended-learning" ) {
				echo '<p><strong>' . __( 'Number of participants', 'rdmcompas-moodle-connector' ) . '</strong>: ' . $single_course_data['course_number_participants'] . '</p>';
			}
			echo '<p><strong>' . __( 'Last modified', 'rdmcompas-moodle-connector' ) . '</strong>: <span itemprop="dateModified">' . $single_course_data['course_date_modified'] . '</span></p>';
		}
		?>
    </div>
</div>
<!--Course summary wrapper-->
<div class="eb-course-desc-wrapper rdm-tc-course-summary">
    <h1 class="intro__title" itemprop="name"><?php the_title(); ?></h1>
    <!--         Course image wrapper-->
    <!--    <div class="eb-course-img-wrapper">-->
    <!--    <div>-->
    <!--        <div>-->
    <!--            --><?php
	//            if (has_post_thumbnail()) {
	//                the_post_thumbnail();
	//            } else {
	//                echo '<img src="' . esc_html($single_course_data['eb_plugin_url']) . 'images/no-image.jpg" />';
	//            }
	//
	//
	?>
    <!--        </div>-->
    <!--    </div>-->
	<?php
	if ( is_search() ) {
		?>
        <div class="entry-summary">
			<?php
			the_excerpt();
			?>
        </div>
		<?php
	} else {
		?>
        <h4><?php esc_html_e( 'Summary', 'rdmcompas-moodle-connector' ); ?></h4>
        <span itemprop="description"><?php the_content(); ?></span>
        <?php
	} ?>
    <p> <?php echo '<span itemprop="provider" itemscope itemtype="http://schema.org/Organization">'. __( 'This course is offered by', 'rdmcompas-moodle-connector' ) .': <span itemprop="name">'. $single_course_data['course_institution'] .'</span></span>'; ?> </p>
	<?php if ( $single_course_data['course_format'] == "Webinar blended-learning" ) { ?>
        <div class="rdmc-tc-contact">
            <p><?php echo __( 'For further questions write to', 'rdmcompas-moodle-connector' ) . " " . $single_course_data['course_contact_person'] ?>
                ,
                <a href="mailto:<?php echo $single_course_data['course_contact_person_email']; ?>">
					<?php echo $single_course_data['course_contact_person_email']; ?></a>
            </p>
        </div>
        <div class="eb-validity-wrapper">
			<?php if ( $single_course_data['course_date_start'] > time() ) { ?>
                <div class="rdm-tc-course-button">
                    <a class="rdm-tc-button rdm-tc-button-blue"
                       href="<?php echo esc_url( $single_course_data['moodle_course_url'] ); ?>" target="_blank"
                       rel="noopener"><?php echo __( 'Register now', 'rdmcompas-moodle-connector' ); ?>!</a>
                </div>
                <span class="dashicons dashicons-clock"></span>
				<?php echo __( 'Starts', 'rdmcompas-moodle-connector' ) . " " . $single_course_data['course_date_start']; ?>

			<?php } else { ?>
                <div class="rdm-tc-course-button">
                    <a class="rdm-tc-button rdm-tc-button-blue"
                       href="<?php echo esc_url( $single_course_data['moodle_course_url'] ); ?>" target="_blank"
                       rel="noopener"><?php echo __( 'Check out course material', 'rdmcompas-moodle-connector' ); ?>!</a>
                </div>
                <span class="dashicons dashicons-clock"></span>
				<?php echo __( 'Started on', 'rdmcompas-moodle-connector' ) . " " . $single_course_data['course_date_start']; ?>
			<?php } ?>
        </div>
	<?php } else { ?>
        <div class="rdm-tc-course-button">
            <a class="rdm-tc-button rdm-tc-button-blue"
               href="<?php echo esc_url( $single_course_data['moodle_course_url'] ); ?>" target="_blank"
               rel="noopener"><?php echo __( 'Start now', 'rdmcompas-moodle-connector' ); ?>!</a>
        </div>
	<?php } ?>
</div>
