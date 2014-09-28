<?php

class GalleryBackendController {

	
	function __construct( ) {
		
	}


	public static function getImageAttachments() {
		global $wpdb;

		$images = $wpdb->get_results(
			"
			SELECT ID, post_content, post_title, guid AS url
			FROM $wpdb->posts
			WHERE post_mime_type LIKE 'image%'"
		);

		foreach ( $images as $image ) {
			$image = new GalleryImage($image);
			$image->build_backend();
		}
	}
}

class GalleryImage {

	private $id;
	private $url;
	private $title;
	private $description;

	function __construct($image_object) {
		$this->id = $image_object->ID;
		$this->url = $image_object->url;
		$this->title = $image_object->post_title;
		$this->description = $image_object->post_content;
	}

	function build_backend() { ?>
		<div class="imageEditor">
			<img src="<?php echo $this->url ?>" alt="" width="200">
			<h3><?php echo $this->title ?></h3>
			<p><?php echo $this->description ?></p>
			<button class="button button-primary button-large">Add to gallery</button>
			<button class="button button-secondary button-large">Edit</button>
		</div>
	<?php }
}

class Gallery {



	private $id;
	private $slug;
	private $title;
	private $description;
	private $title_image_url;
	private $hasImages = false;




	function __construct( $gallery_object ) {
		
		if ( is_object( $gallery_object ) ) {

			$this->id = $gallery_object->id;
			$this->slug = $gallery_object->slug;
			$this->title = $gallery_object->name;
			$this->description = $gallery_object->description;
			
		} 
		
		else if ( is_array( $gallery_object ) ) {

			$this->id = $gallery_object['id'];
			$this->slug = $gallery_object['slug'];
			$this->title = $gallery_object['name'];
			$this->description = $gallery_object['description'];

		}
		
	}


	function build_backend() { ?>
		<div class="galleryEditor" data-id="<?php echo $this->id; ?>">
			<img src="<?php echo $this->title_image_url ?>" alt="" height="200">
			<h3>
				<?php echo $this->title ?>
			</h3>
			<p>
				<?php echo $this->description ?>
			</p>
			<button class="button button-primary button-large" name="shortcode">Get Shortocde</button>
			<button class="button button-secondary button-large" name="images">View all images</button>
			<button class="button button-secondary button-large" name="edit">Edit</button>
		</div>
	<?php }


	function getImages() {
		global $wpdb;

		$table_name = RELATION_TABLE;
		$galleries = $wpdb->get_results(
			"SELECT ID, guid as URL
			FROM $wpdb->posts posts INNER JOIN $table_name relation on posts.ID = relation.id
			WHERE galleryid = $this->id
			"
		);

		if (!is_null($galleries)) {
			foreach ($galleries as $key => $value) {
				
			}
		} else { ?>
			<div class="galleryImageSelector">
				<?php GalleryBackendController::getImageAttachments(); ?>
			</div>
		}

	}
}

?>