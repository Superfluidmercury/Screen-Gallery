var newGalleryWidget = {

	newGallery: jQuery('#addGallery'),
	newGalleryName: jQuery('#addGallery [name=name]'),
	newGalleryDescription: jQuery('#addGallery [name=description]'),

	toggleButton: jQuery('#addGalleryButton'),
	sendButton: jQuery('#addGallery [type=submit]'),
	cancelButton: jQuery('#addGallery [name=cancel]'),

	
	

	init: function() {
		this.toggleButton.on('click', function() {
			newGalleryWidget.newGallery.slideToggle();
		});

		this.sendButton.on('click', function(e) {
			newGalleryWidget.send(e);
		});

		this.cancelButton.on('click', function() {
			newGalleryWidget.newGalleryName.val("");
			newGalleryWidget.newGalleryDescription.val("");
			newGalleryWidget.newGallery.slideToggle();
		});
	},

	send: function(e) {
		e.preventDefault();
		jQuery.ajax({
			type: 'POST',
			url: ajaxdata.ajaxurl,
			data: {
				action: 'add_gallery',
				name: this.newGalleryName.val(),
				description: this.newGalleryDescription.val()
			},

			success: function(data){
				jQuery('.galleryEditors').prepend(data);
			}
		}); 

	}
}

newGalleryWidget.init();