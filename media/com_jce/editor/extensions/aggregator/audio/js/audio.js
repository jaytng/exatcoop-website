/* jce - 2.9.63 | 2024-03-11 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2024 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
WFAggregator.add("audio", {
    params: {},
    props: {
        autoplay: 0,
        loop: 0,
        controls: 1,
        mute: 0
    },
    setup: function() {
        $.each(this.params, function(k, v) {
            $("#audio_" + k).val(v).filter(":checkbox, :radio").prop("checked", !!v);
        });
    },
    getTitle: function() {
        return this.title || this.name;
    },
    getType: function() {
        return "audio";
    },
    isSupported: function(v) {
        return !1;
    }
});