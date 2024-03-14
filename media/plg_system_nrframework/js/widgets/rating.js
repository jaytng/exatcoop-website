var NRF_Rating=function(){function t(){this.wrapperClass="nrf-rating-wrapper",this.emptyState="iconEmpty",this.filledState="iconFilled",this.activeState="iconActive",this.ratingElementWrapper,this.init()}var e=t.prototype;return e.init=function(){document.addEventListener("click",function(t){!t.target.closest("."+this.wrapperClass)||this.isReadOnlyOrDisabled(t)||this.updateValue(t)}.bind(this)),document.addEventListener("mouseover",function(t){this.ratingElementWrapper=t.target.closest("."+this.wrapperClass),this.ratingElementWrapper&&(t.preventDefault(),this.isReadOnlyOrDisabled(t)||(this.isHalfRating()?this.addHalfRatingState(t):this.addState(t)))}.bind(this)),document.addEventListener("mouseout",function(t){this.ratingElementWrapper=t.target.closest("."+this.wrapperClass),this.ratingElementWrapper&&!this.isReadOnlyOrDisabled(t)&&(this.isHalfRating()?this.resetHalfRatingState(t):this.resetState(t))}.bind(this))},e.updateValue=function(t){this.removeActiveState(t);var e,a=t.target.closest("label").previousElementSibling;a&&(e=this.ratingElementWrapper.querySelector("input:checked"))&&!a.disabled&&e.value==a.value&&(t.preventDefault(),this.resetAllRatingValues(t))},e.resetAllRatingValues=function(t){var e=this,a=t.target.closest("."+this.wrapperClass).querySelectorAll("."+this.filledState);a&&(a.forEach(function(t){t.classList.remove(e.filledState,e.emptyState,e.activeState),t.previousElementSibling.checked=!1}),t.target.closest("label").previousElementSibling.dispatchEvent(new Event("change",{bubbles:!0})))},e.removeActiveState=function(t){t.target.closest("."+this.activeState)&&t.target.closest("."+this.activeState).classList.remove(this.activeState)},e.addState=function(t){var e,a=this,i=t.target.closest("label");i&&(e=i.previousElementSibling.value,t.target.closest("."+this.wrapperClass).querySelectorAll("label").forEach(function(t){a.addRatingItemState(t,e)}),i.classList.add(this.activeState))},e.addRatingItemState=function(t,e){var a=t.previousElementSibling.value;parseFloat(a)>parseFloat(e)?t.classList.add(this.emptyState):t.classList.add(this.filledState)},e.resetState=function(t){var e,a=this;this.ratingElementWrapper=t.target.closest("."+this.wrapperClass),this.ratingElementWrapper&&(e=this.ratingElementWrapper.querySelector("input:checked")||0,this.ratingElementWrapper.querySelectorAll("label").forEach(function(t){a.resetRatingItemState(t,e)}))},e.resetRatingItemState=function(t,e){var a;t.classList.remove(this.emptyState,this.filledState,this.activeState),e&&(a=t.previousElementSibling.value,parseFloat(a)>parseFloat(e.value)||t.classList.add(this.filledState))},e.addHalfRatingState=function(t){var e,a,i=this,s=t.target.closest(".rating_item_group");s&&(e=t.target.closest("label"),s.classList.add(this.activeState),a=e.previousElementSibling.value,t.target.closest("."+this.wrapperClass).querySelectorAll(".rating_item_group").forEach(function(t){t.querySelectorAll("label").forEach(function(t){i.addRatingItemState(t,a)})}))},e.resetHalfRatingState=function(t){var e,a=this;this.ratingElementWrapper=t.target.closest("."+this.wrapperClass),this.ratingElementWrapper&&(e=this.ratingElementWrapper.querySelector("input:checked")||0,this.ratingElementWrapper.querySelectorAll(".rating_item_group").forEach(function(t){t.classList.contains(a.activeState)&&t.classList.remove(a.activeState),t.querySelectorAll("label").forEach(function(t){a.resetRatingItemState(t,e)})}))},e.updateHalfRatingValue=function(t){this.removeActiveState(t);var e=t.target.closest("."+this.wrapperClass).querySelector("input:checked"),e=!!e&&e.value;t.target.closest("label").previousElementSibling.value==e&&(t.preventDefault(),this.resetAllRatingValues(t))},e.removeHalfRatingActiveState=function(t){t.target.closest(".rating_item_group")||t.target.closest(".rating_item_group").classList.remove(this.activeState)},e.isHalfRating=function(){return this.ratingElementWrapper.classList.contains("half")},e.getNextSiblings=function(t){for(var e=[];t=t.nextSibling;)3!==t.nodeType&&e.push(t);return e},e.isReadOnlyOrDisabled=function(t){t=t.target.closest("label");return!t||t.previousElementSibling.disabled},t}();document.addEventListener("DOMContentLoaded",function(t){new NRF_Rating});

