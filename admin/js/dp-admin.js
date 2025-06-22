// Dog Pages Admin JavaScript
// This script handles the media uploader for dog images in the admin area
// It uses WordPress's media library to allow users to select images
(function ($) {
  $(document).ready(function () {
    console.log("Dog Pages Admin JS Loaded");
    $("#upload_dog_image").on("click", function (e) {
      e.preventDefault();
      const frame = wp.media({
        title: "Select Dog Image",
        multiple: false,
        library: { type: "image" },
      });

      frame.on("select", function () {
        const attachment = frame.state().get("selection").first().toJSON();
        $("#dogpages_image").val(attachment.id);
        $("#dogpages_image_preview").attr("src", attachment.url);
      });

      frame.open();
    });

    $("#licenseKey").on("focus", function () {
      $(this).attr("type", "text");
    });

    // Optional: revert back to password on blur
    $("#licenseKey").on("blur", function () {
      $(this).attr("type", "password");
    });
  });
})(jQuery);
