(function() {
    tinymce.PluginManager.add('mow_tinymce_button', function( editor, url ) {
        editor.addButton( 'mow_tinymce_button', {
            image: WPURLS.siteurl + '/wp-content/plugins/mowplayer/assets/icon-128x128.png',
            title : 'Insert video',
            onclick: function() {
                var selectedNode = editor.selection.getNode();
                var name = '';
        		if (selectedNode.tagName == 'A') {
        			name = selectedNode.name || selectedNode.id || '';
        		}
        		editor.windowManager.open({
        			title: 'Mowplayer video',
        			body: {type: 'textbox', name: 'name', size: 40, label: 'Social Media URL...', value: name, placeholder: 'https://www.youtube.com/watch?v=IaOPrZ3svms'},
        			onsubmit: function(e) {

                        //var regex = /http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/)|(?:facebook.com\/(?:video\.php\?v=\d+|.*?\/videos\/\d+)))/;
                        //var regex = /http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/)|(?:facebook.com\/(?:video\.php\?v=\d+|.*?\/videos\/\d+|watch\/\?v=\d+|watch\/live\/\?v=\d+)))/;

                        var regex = /http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/)|(?:facebook.com\/(?:video\.php\?v=\d+|.*?\/videos\/\d+|watch\/\?v=\d+|watch\/live\/\?v=\d+|watch\/\?ref=watch_permalink&v=\d+)))/;
                        var res = e.data.name.match(regex);

                        if (res) {
                            var yt_video = e.data.name;
                            if (yt_video.indexOf('&') != -1) {
                                yt_video  = yt_video.split('&')[0];
                            }
                            //regex
                            //YOUTUBE
                            var regex = /http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)/;
                            if (regex.test(res)) {
                                var yt_id = yt_video.replace(regex, '');
                                var video_type = 'yt';
                            }

                            //FACEBOOK
                            //https://www.facebook.com/9gag/videos/797836024082952
                            var fb_regex_1 = /http(?:s?):\/\/(?:www\.)?facebook.com\/(?:.*\/videos\/\d*)/;
                            if (fb_regex_1.test(res)) {
                                var re = /\//g;
                                var str = res;
                                var result = re[Symbol.split](str);
                                //var user_fb = result[3];
                                var yt_id = result[5];
                                var user_fb = 'fb';
                            }

                            //https://www.facebook.com/watch/live/?v=554340498667393&ref=watch_permalink
                            var fb_regex_2 = /http(?:s?):\/\/(?:www\.)?facebook.com\/(?:watch\/live\/\?v=\d*)/;
                            if (fb_regex_2.test(res)) {
                                var re = /\//g;
                                var str = res;
                                var result = re[Symbol.split](str);
                                var result_1 = result[5];

                                var re_3 = /\/?=/;
                                var result = re_3[Symbol.split](result_1);
                                var result_2 = result[1];

                                var re_4 = /\&ref/;
                                var result = re_4[Symbol.split](result_2);

                                var user_fb = 'fb';
                                var yt_id = result[0];
                            }

                            //https://www.facebook.com/watch/?v=554340498667393
                            var fb_regex_3 = /http(?:s?):\/\/(?:www\.)?facebook.com\/(?:watch\/\?v=\d+)/;
                            if (fb_regex_3.test(res)) {
                                var re = /\//g;
                                var str = res;
                                var result = re[Symbol.split](str);
                                var re_2 = /\/?=/;
                                var result = re_2[Symbol.split](result);
                                var user_fb = 'fb';
                                var yt_id = result[1];
                            }

                            //https://www.facebook.com/watch/?ref=watch_permalink&v=395282871030822
                            var fb_regex_4 = /http(?:s?):\/\/(?:www\.)?facebook.com\/(?:watch\/\?ref=watch_permalink&v=\d+)/;
                            if (fb_regex_4.test(res)) {
                                var re = /\//g;
                                var str = res;
                                var result = re[Symbol.split](str);

                                var re_2 = /\/?ref=watch_permalink&v=/;
                                var result = re_2[Symbol.split](result);

                                console.log(result);


                                var user_fb = 'fb';
                                var yt_id = result[1];
                            }


                            //set custom mowplayer shortcode
                            if (user_fb) {
                                editor.insertContent('[Mowplayer-Video ID=' + yt_id + ' TYPE=' + user_fb + ']');
                            } else {
                                editor.insertContent('[Mowplayer-Video ID=' + yt_id +  ']');
                            }


                        } else {
                            alert('INVALID URL - Use a valid video Url \nExample: \nhttps://www.youtube.com/watch?v=IaOPrZ3svms \nhttps://www.facebook.com/watch/?v=159670801400454');
                        }
        			}
        		});
            }
        });
    });
})();
