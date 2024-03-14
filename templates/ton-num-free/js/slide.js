// JavaScript Document
jQuery("document").ready(function($){
var pos = $('#head-slide').offset().top;
var nav = $('#head-slide');

$(window).scroll(function () {
if ($(this).scrollTop() > pos) {
nav.addClass("f-nav");
} else {
nav.removeClass("f-nav");
}
});
});