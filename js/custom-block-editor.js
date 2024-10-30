/**
 * Mowplayer Block editor
 */

var el = wp.element.createElement;

/* Mowplayer icon */
var gradientColor1 =  el('stop', {offset: "0", stopColor: "#fe365d"});
var gradientColor2 =  el('stop', {offset: "1", stopColor: "#931aa2"});
var linearGradient1 = el('linearGradient', {id: "grd1",gradientUnits:"userSpaceOnUse",x1:"63.99",y1:"2.21",x2:"63.99",y2:"126.92" }, gradientColor1, gradientColor2);
var linearGradient2 = el('linearGradient', {id: "grd2",gradientUnits:"userSpaceOnUse",x1:"1342.861",y1:"1383.427",x2:"1342.861",y2:"2108.945" }, gradientColor1, gradientColor2);

const mowIcon = el('svg', { width: 24, height: 24,  viewBox: "0 0 140 140" },
  el('defs', {
    },
        linearGradient1,
        linearGradient2,
    ),

  el('style', {}, 'tspan { white-space:pre }.shp0 { fill: url(#grd1) }.shp1 { fill: url(#grd2) }'),

  el('path', {
        id: "Layer",
        className: "shp0",
        fillRule:"evenodd",
        d: "M64 2.21C72.19 2.21 80.3 3.82 87.86 6.95C95.43 10.09 102.3 14.68 108.09 20.47C113.89 26.26 118.48 33.13 121.61 40.7C124.75 48.26 126.36 56.37 126.36 64.56C126.36 89.78 111.17 112.52 87.87 122.18C64.57 131.84 37.75 126.5 19.91 108.67C2.07 90.83 -3.27 64.01 6.39 40.71C16.04 17.4 38.78 2.21 64 2.21ZM64 28.93C59.32 28.93 54.69 29.86 50.37 31.65C46.05 33.44 42.12 36.07 38.81 39.37C35.5 42.68 32.88 46.61 31.08 50.93C29.29 55.25 28.37 59.88 28.36 64.56C28.36 78.98 37.04 91.97 50.35 97.49C63.67 103.01 79 99.97 89.2 89.77C99.39 79.58 102.44 64.25 96.93 50.93C91.41 37.61 78.42 28.93 64 28.93Z"
    }),
    el('path', {
        id: "Layer",
        className: "shp1",
        d: "M75.41 60.76L62.13 50.25C61.69 49.91 61.16 49.71 60.61 49.65C60.06 49.59 59.5 49.69 59 49.93C58.5 50.17 58.08 50.55 57.78 51.02C57.48 51.48 57.32 52.03 57.31 52.58L57.31 73.58C57.32 74.13 57.48 74.68 57.78 75.14C58.08 75.61 58.5 75.99 59 76.23C59.5 76.47 60.06 76.57 60.61 76.51C61.16 76.45 61.69 76.25 62.13 75.91L75.41 65.42C75.76 65.14 76.04 64.78 76.23 64.38C76.42 63.98 76.52 63.54 76.52 63.09C76.52 62.64 76.42 62.2 76.23 61.8C76.04 61.4 75.76 61.04 75.41 60.76Z"
    }),




);

wp.blocks.registerBlockType('shaiful-gutenberg/notice-block', {
    title: 'Mowplayer',
    icon: mowIcon,
    category: 'embed',
    attributes: {
        url: { type: 'string' },
        parsedUrl: { type: 'string' },
        textInputClass: {type:'string', value: 'components-placeholder__input'},
        submitInputClass: {type:'string', value: 'input-button-mow'}
    },
    edit: function (props) {
        function updateTitle(event) {
            if(event){
                event.preventDefault();
            }

            //const escapeRE = new RegExp(/http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/)|(?:facebook.com\/(?:video\.php\?v=\d+|.*?\/videos\/\d+|watch\/\?v=\d+|watch\/live\/\?v=\d+)))/);
            const escapeRE = new RegExp(/http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/)|(?:facebook.com\/(?:video\.php\?v=\d+|.*?\/videos\/\d+|watch\/\?v=\d+|watch\/live\/\?v=\d+|watch\/\?ref=watch_permalink&v=\d+)))/);

            var video_url = props.attributes.url;
            if (escapeRE.test(video_url)) {
                const safeRE = (string) => {
                    return string.replace(escapeRE, "")
                }

                const fb_regex = new RegExp(/http(?:s?):\/\/(?:www\.)?facebook.com\//);
                if (fb_regex.test(video_url)) {
                    //facebook url types

                    //https://www.facebook.com/Metallica/videos/280918206508348
                    const fb_regex_1 = new RegExp(/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:.*\/videos\/\d*)/);

                    //https://www.facebook.com/watch/?v=280918206508348
                    const fb_regex_2 = new RegExp(/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:watch\/\?v=d*)/);

                    //https://www.facebook.com/watch/live/?v=554340498667393&ref=watch_permalink
                    //const fb_regex_3 = new RegExp(/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:watch\/live\/\?v=d*)/);
                    const fb_regex_3 = new RegExp(/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:watch\/live\/\?v=\d*\&ref=watch_permalink)/);

                    //https://www.facebook.com/watch/?ref=watch_permalink&v=395282871030822
                    const fb_regex_4 = new RegExp(/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:watch\/\?ref=watch_permalink&v=\d+)/);

                    if (fb_regex_1.test(video_url)) {
                        var re = /\//g;
                        var str = video_url;
                        var result = re[Symbol.split](str);
                        var user_fb = 'fb';
                        var yt_id = result[5];

                        props.setAttributes({ parsedUrl: '[Mowplayer-Video ID=' + yt_id + ' TYPE='+ user_fb + ']' });
                    }

                    if (fb_regex_2.test(video_url)) {
                        var re = /\//g;
                        var str = video_url;
                        var result = re[Symbol.split](str);
                        var re_2 = /\/?=/;
                        var result = re_2[Symbol.split](result);
                        var user_fb = 'fb';
                        var yt_id = result[1];

                        props.setAttributes({ parsedUrl: '[Mowplayer-Video ID=' + yt_id + ' TYPE='+ user_fb + ']' });
                    }

                    if (fb_regex_3.test(video_url)) {
                        var re = /\//g;
                        var str = video_url;
                        var result = re[Symbol.split](str);
                        var result_1 = result[5];

                        var re_3 = /\/?=/;
                        var result = re_3[Symbol.split](result_1);
                        var result_2 = result[1];

                        var re_4 = /\&ref/;
                        var result = re_4[Symbol.split](result_2);

                        var user_fb = 'fb';
                        var yt_id = result[0];

                        props.setAttributes({ parsedUrl: '[Mowplayer-Video ID=' + yt_id + ' TYPE='+ user_fb + ']' });
                    }


                    if (fb_regex_4.test(video_url)) {
                        var re = /\//g;
                        var str = video_url;
                        var result = re[Symbol.split](str);
                        var result_1 = result[4];

                        var re_3 = /\/?ref=watch_permalink&v=/;
                        var result = re_3[Symbol.split](result_1);
                        var result_2 = result[1];

                        var user_fb = 'fb';
                        //var yt_id = result[0];
                        var yt_id = result_2;

                        props.setAttributes({ parsedUrl: '[Mowplayer-Video ID=' + yt_id + ' TYPE='+ user_fb + ']' });
                    }



                } else {
                    props.setAttributes({ parsedUrl: '[Mowplayer-Video ID=' + safeRE(video_url) + ']' });
                }

                //props.setAttributes({ parsedUrl: '[Mowplayer-Video ID=' + safeRE(video_url) + ']' });
                props.setAttributes({ textInputClass: 'hide-element'});
                props.setAttributes({ submitInputClass: 'hide-element'});

            } else {
                alert('INVALID URL - Use a valid video Url \nExample: \nhttps://www.youtube.com/watch?v=IaOPrZ3svms \nhttps://www.facebook.com/watch/?v=159670801400454');
            }
        };
        function updateField(event) {
            props.setAttributes({ url: event.target.value });
        };

        const divStyle = {
          paddingTop: '1em',
        };

        var textInput = el('input', {
            type: 'url',
            className: 'components-placeholder__input ' +  props.attributes.textInputClass,
            placeholder: 'Social Media URL...',
            onChange: updateField
        });

        var submitButton = el('input', {
            value: 'Insert',
            type: 'submit',
            className: 'components-button is-secondary ' + props.attributes.submitInputClass
        });

        var spanPlaceholder = el('span', {
                className: 'block-editor-block-icon has-colors'
            },
            mowIcon
        );

        var divPlaceholder = el('div', {
                className: 'components-placeholder__label',
                style: divStyle
            },
                spanPlaceholder,
                'Mowplayer'
        );

        var formElement = el('form',
            {
                onSubmit: updateTitle,
            },
            textInput,
            submitButton
        );

        var divInstructions = el('div', {
            className: 'components-placeholder__instructions'
        }, 'Paste social media URL (video link).');

        var divFieldset = el('div', {
            className: 'components-placeholder__fieldset',
        },
            formElement
        );

        var videoUrlElement = el(
            'p',
            null,
            props.attributes.parsedUrl
        );
        return el('div',{
                //className: 'mow-block-container',
                className: 'components-placeholder wp-block-embed is-large'
            },
            divPlaceholder,
            divInstructions,
            divFieldset,
            videoUrlElement);
    },
    save: function (props) {
        return el(
            'p',
            null,
            props.attributes.parsedUrl
        );
    }
});
