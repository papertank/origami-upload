/*
 *  Origami upload js
 *
 *  Papertank Limited
 *  David Rushton
 */
;(function ( $, window, document, undefined ) {

    "use strict";

    var pluginName = "origamiUpload",
        defaults = {
            browse_button: 'uploader',
            drop_element: 'upload-box',
            files: 'upload-queue',
            url: '/upload',
            token: '',
            max_file_size: '4mb',
            placeholder: '/placeholder.png',
            assets_url: ''
        };

    // The actual plugin constructor
    function Plugin ( element, options ) {
        this.element = element;

        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    $.extend(Plugin.prototype, {
        init: function () {

            var self = this;

            this.maximum = 40;
            this.total = 0;
            this.uploading = false;
            this.files = $('#'+this.settings.files);
            this.plupload = this.initPlupload(this.settings, this.files, {
                'onUploadStarted': function() { self.uploadStarted(); },
                'onUploadComplete': function(total) { self.uploadComplete(total); },
                'onUploadSuccess': function() { self.uploadSuccess(); }
            });

            this.bindEvents();

            // Place initialization logic here
            // You already have access to the DOM element and
            // the options via the instance, e.g. this.element
            // and this.settings
            // you can add more functions like the one below and
            // call them like so: this.yourOtherFunction(this.element, this.settings).
            $(this.element).show();
        },

        uploadStarted: function() {
            this.uploading = true;
        },

        uploadComplete: function(total) {
            if ( total.queued == 0 ) {
                this.uploading = false;
            }
        },

        uploadSuccess: function() {
            this.total++;
            console.log(this.total);
        },

        bindEvents: function() {
            this.deleteFiles();
            this.formSubmit();
            this.checkChanges();
        },

        deleteFiles: function () {

            var self = this;

            $(this.element).on('click', '.upload-cancel', function()
            {
                var file = $(this).parent();
                var existing = file.hasClass('upload-item-existing');

                if ( confirm('Are you sure you want to cancel that upload') == true ) {
                    file.fadeOut(500, function() {
                        file.remove();
                    });

                    if ( ! existing ) {
                        self.plupload.removeFile(file.attr('id'));
                        self.total--;
                    }
                }
            });
        },

        formSubmit: function() {

            var self = this;

            $(this.element).on('submit', function(event)
            {
                $(window).off("beforeunload");
            });
        },

        checkChanges: function() {

            var self = this;

            $(window).on('beforeunload', function(event) {
                if (self.uploading) {
                    return ("There are still images uploading. Please return to the page and wait for uploads to complete.");
                }

                if (self.total > 0) {
                    return ("You've not saved your images yet! Please return to the page and click 'Save' to finish the upload.");
                }
            });
        },

        initPlupload: function (settings, queue, events) {

            var self = this;

            console.log(settings.assets_url+'/Moxie.swf');

            var uploader = new plupload.Uploader({
                runtimes: "html5,flash,silverlight,html4",
                flash_swf_url : settings.assets_url+'/Moxie.swf',
                silverlight_xap_url : settings.assets_url+'/Moxie.xap',
                browse_button: settings.browse_button,
                drop_element: settings.drop_element,
                url: settings.url,
                file_data_name: 'image',
                multipart_params: {
                    '_token' : settings.token
                },
                filters: {
                    max_file_size: settings.max_file_size,
                    mime_types: [
                        {title: "Photos", extensions: "jpg,jpeg,png"}
                    ]
                },
                init: {
                    PostInit: function() {
                        self.files.html('');
                    },
                    FilesAdded: function (up, files) {

                        var total = up.files.length;

                        if ( total > self.maximum ) {
                            alert('The maximum allowed uploads is '+self.maximum+'. Please save and start a new upload');
                            plupload.each(files, function (file) {
                                up.removeFile(file);
                            });
                            return false;
                        }

                        events.onUploadStarted();
                        var html = '';
                        plupload.each(files, function (file) {
                            html += '<div id="' + file.id + '" class="upload-item"><a class="upload-cancel close">&times;</a> <span class="filename">' + file.name + '</span> <div class="photo placeholder"> <img src="'+settings.placeholder+'" /> </div>  <div class="progress progress-striped active"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"><span class="sr-only">0% Complete</span></div></div></div>';
                        });
                        queue.prepend(html);
                        uploader.start();
                    },
                    UploadProgress: function (up, file) {
                        var percent = file.percent;
                        $('#' + file.id).find('.progress-bar').css({'width': percent}).html('<span class="sr-only">'+Math.round(percent)+'% Complete</span>');
                    },
                    FileUploaded: function (up, file, info) {

                        events.onUploadComplete(up.total);

                        var box = $('#' + file.id);
                        box.find('.progress').slideUp(200, function () {
                            $(this).remove();
                        });
                        var response = jQuery.parseJSON(info.response);
                        if (typeof response.error !== 'undefined') {
                            box.addClass('upload-error');
                            box.append('<a href="#" class="upload-dismiss">Dismiss</a><span class="upload-file-error">' + response.error + '</span>');
                        } else {
                            box.addClass('upload-completed');
                            if ( typeof response.src !== 'undefined' ) {
                                $('.photo', box).removeClass('placeholder').html('<img src="' + response.src + '" alt="" />');
                            }
                            box.append('<input type="hidden" name="media[]" value="' + response.ref + '" />');
                            events.onUploadSuccess();
                        }
                    },
                    Error: function (up, err) {
                        alert('Error uploading: ' + err.message);
                    }
                }
            });
            uploader.init();
            return uploader;
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[ pluginName ] = function ( options ) {
        return this.each(function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
            }
        });
    };

})( jQuery, window, document );