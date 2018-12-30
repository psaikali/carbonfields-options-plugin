<?php

namespace Mosaika\Plugin\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Créée une page d'options pour notre plugin.
 * Les onglets sont initialement vides mais sont définis et remplis de champs via des filtres définis plus bas.
 *
 * @link https://carbonfields.net/docs/containers-theme-options/
 *
 * @return void
 */
function options_initialize_admin_page() {
	$tabs = apply_filters( 'msk_plugin_options_tabs', [] );

	if ( empty( $tabs ) ) {
		return;
	}

	// On créée la page d'options.
	$theme_options = Container::make( 'theme_options', __( 'Options du plugin', 'msk-plugin' ) );

	// On définit son slug utilisé dans l'URL de la page.
	$theme_options->set_page_file( 'plugin-mosaika' );

	// On définit son nom dans le menu d'admin.
	$theme_options->set_page_menu_title( __( 'Plugin Mosaika.fr', 'msk-plugin' ) );

	// On définit sa position dans le menu d'admin.
	$theme_options->set_page_menu_position( 31 );

	// On change son icône dans le menu d'admin.
	$theme_options->set_icon( 'dashicons-palmtree' );

	// Et enfin, pour chaque onglet, on charge les champs de l'onglet concerné.
	foreach ( $tabs as $tab_slug => $tab_title ) {
		$theme_options->add_tab(
			esc_html( $tab_title ),
			apply_filters( "msk_plugin_options_fields_tab_{$tab_slug}", [] )
		);
	}
}
add_action( 'carbon_fields_register_fields', __NAMESPACE__ . '\\options_initialize_admin_page' );

/**
 * Liste des onglets dans lesquels seront rangés les champs de notre page d'options.
 *
 * @param array $tabs []
 * @return array $tabs Tableau des onglets : la clé d'une entrée est utilisée par le filtre chargeant les champs de l'onglet, la valeur d'une entrée est le titre de l'onglet.
 */
function options_set_tabs( $tabs ) {
	return [
		'general'  => __( 'Général', 'msk-plugin' ),
		'social'   => __( 'Réseaux sociaux', 'msk-plugin' ),
		'advanced' => __( 'Avancé', 'msk-plugin' ),
	];
}
add_filter( 'msk_plugin_options_tabs', __NAMESPACE__ . '\\options_set_tabs' );

/**
 * Ajoute des champs dans l'onglet "Général".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link https://carbonfields.net/docs/fields-usage/
 */
function options_general_tab_theme_fields() {
	$fields = [];

	// Champ riche.
	$fields[] = Field::make( 'rich_text', 'champ_riche', __( 'Champs WYSIWYG', 'msk-plugin' ) )
				->set_help_text( __( 'Ce texte d\'aide permet à l\'utilisateur de comprendre l\'intérêt de champ.', 'msk-plugin' ) )
				->set_required();

	// Champ <select>.
	$fields[] = Field::make( 'select', 'champ_select', __( 'Champs menu déroulant', 'msk-plugin' ) )
				->set_options( [
					'option1' => __( 'Option n°1', 'msk-plugin' ),
					'option2' => __( 'Option n°2', 'msk-plugin' ),
					'option3' => __( 'Option n°3', 'msk-plugin' ),
					'option4' => __( 'Option n°4', 'msk-plugin' ),
				] );

	return $fields;
}
add_filter( 'msk_plugin_options_fields_tab_general', __NAMESPACE__ . '\\options_general_tab_theme_fields', 10 );

/**
 * Ajoute des champs dans l'onglet "Réseaux sociaux".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link https://carbonfields.net/docs/fields-usage/
 */
function options_social_tab_theme_fields() {
	$fields = [];

	$networks = [
		'instagram',
		'twitter',
		'vimeo',
		'youtube',
		'github',
		'pinterest',
	];

	// Réseaux sociaux.
	array_walk(
		$networks,
		function ( $network ) use ( &$fields ) {
			/* Translators: "{social network name} URL" */
			$title    = sprintf( __( 'URL profil %1$s', 'msk-plugin' ), ucfirst( $network ) );
			$fields[] = Field::make( 'text', "social_url_{$network}", $title )
						->set_width( 50 );
		}
	);

	return $fields;
}
add_filter( 'msk_plugin_options_fields_tab_social', __NAMESPACE__ . '\\options_social_tab_theme_fields', 10 );

/**
 * Ajoute des champs dans l'onglet "Avancé".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link https://carbonfields.net/docs/fields-usage/
 */
function options_advanced_tab_theme_fields() {
	$fields = [];

	// wp_head extra scripts/styles.
	$fields[] = Field::make( 'header_scripts', 'extra_header_code' );

	// wp_footer extra scripts/styles.
	$fields[] = Field::make( 'footer_scripts', 'extra_footer_code' );

	return $fields;
}
add_filter( 'msk_plugin_options_fields_tab_advanced', __NAMESPACE__ . '\\options_advanced_tab_theme_fields', 10 );

/**
 * Affiche la valeur d'un champ sous la metabox d'onglets
 *
 * @return void
 */
function display_content_after_fields() {
	printf(
		'<hr><div>
			<h4>%1$s</h4>
			%2$s
		</div>',
		__( 'Valeur du champ "Champ WYSIWYG"', 'msk-plugin' ),
		\carbon_get_theme_option( 'champ_riche' )
	);

	printf(
		'<br><hr><div>
			<h4>%1$s</h4>
			%2$s
		</div>',
		__( 'Valeur du champ "Champs menu déroulant"', 'msk-plugin' ),
		\carbon_get_theme_option( 'champ_select' )
	);
}
add_action( 'carbon_fields_container_options_du_plugin_after_fields', __NAMESPACE__ . '\\display_content_after_fields' );

/**
 * Affiche un contenu promotionnel dans la sidebar
 *
 * @return void
 */
function display_content_after_sidebar() {
	?>
	<style>
		#msk-plugin-promo-box {
			background: #fff;
			border:1px solid #DADADA;
			padding:2rem;
			text-align: center;
			border-radius:5px;
		}

		#msk-plugin-promo-box h2 {
			font-size: 3rem;
			margin:0;
		}
	</style>

	<div id="msk-plugin-promo-box" class="wp-core-ui">
		<h2>🚀</h2>
		<h3>Passez à la version pro&nbsp;!</h3>
		<p><strong>Pour seulement 899€ HT par mois, bénéficiez de nombreuses améliorations :</strong></p>
		<ul>
			<li>✔ Super fonctionnalité 1</li>
			<li>✔ Médiocre fonctionnalité 2</li>
			<li>✔ Fonctionnalité passable 3</li>
		</ul>
		<a class="button" href="https://mosaika.fr/blog">🛒 J'achète</a>
	</div>
	<?php
}
add_action( 'carbon_fields_container_options_du_plugin_after_sidebar', __NAMESPACE__ . '\\display_content_after_sidebar' );