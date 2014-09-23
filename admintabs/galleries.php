<button id="addGalleryButton" class="button button-primary button-large">Add Gallery</button>


<div id="addGallery">
	<form action="" method="post">
		<input type="text" placeholder="Name" name="name">
		<textarea name="description" id="" cols="30" rows="5" placeholder="Description"></textarea>
		<span>
			<button type="submit" class="button button-primary button-large">Save</button>
			<button class="button button-secondary button-large">Cancel</button>
		</span>
	</form>
</div>

<?php

tables_install();


function tables_install() {
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	# Charset applies to all tables	
	$charset_collate = '';
	if ( ! empty( $wpdb->charset ) ) { $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}"; }
	if ( ! empty( $wpdb->collate ) ) { $charset_collate .= " COLLATE {$wpdb->collate}"; }



	# Table containing gallery info
	$table_name = GALLERY_TABLE;
	
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		slug tinytext,
		description text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	dbDelta( $sql );



	# Table relating images to galleries
	$table_name = RELATION_TABLE;

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		imageid mediumint(9) NOT NULL,
		galleryid mediumint(9) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	dbDelta( $sql );
}

function getGalleries($offset=0, $search="") {
	global $wpdb;

	$table_name = GALLERY_TABLE;

	$galleries = $wpdb->get_results(
		"SELECT id, name, slug, description
		FROM $table_name
		ORDER BY name desc
		LIMIT $offset, 20
		"
	); 

	?> <div class="galleryEditors"> <?php

		foreach ( $galleries as $gallery ) {
			$gallery = new Gallery($gallery);
			$gallery->build_backend();
		}

	?> </div> <?php
}

getGalleries();

?>