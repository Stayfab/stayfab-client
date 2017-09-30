/* jQuery Simple Slider: Unobtrusive Numerical Slider —  https://github.com/loopj/jquery-simple-slider */
var __slice=[].slice,__indexOf=[].indexOf||function(e){for(var t=0,n=this.length;t<n;t++)if(t in this&&this[t]===e)return t;return-1};(function(e,t){var n;return n=function(){function n(n,r){var i,s=this;this.input=n,this.defaultOptions={animate:!0,snapMid:!1},this.settings=e.extend({},this.defaultOptions,r),this.input.hide(),this.slider=e("<div>").addClass("slider").attr("id",this.input.attr("id")+"-slider").css({position:"relative",userSelect:"none",boxSizing:"border-box"}).insertBefore(this.input),this.track=e("<div>").addClass("track").css({position:"absolute",top:"50%",width:"100%",userSelect:"none",cursor:"pointer"}).appendTo(this.slider),this.dragger=e("<div>").addClass("dragger").css({position:"absolute",top:"50%",userSelect:"none",cursor:"pointer"}).appendTo(this.slider),this.slider.css({minHeight:this.dragger.outerHeight(),marginLeft:this.dragger.outerWidth()/2,marginRight:this.dragger.outerWidth()/2}),this.track.css({marginTop:this.track.outerHeight()/-2}),this.dragger.css({marginTop:this.dragger.outerWidth()/-2,marginLeft:this.dragger.outerWidth()/-2}),this.track.mousedown(function(e){if(e.which!==1)return;return s.domDrag(e.pageX,e.pageY,!0),s.dragging=!0,!1}),this.dragger.mousedown(function(e){if(e.which!==1)return;return s.dragging=!0,s.dragger.addClass("dragging"),s.domDrag(e.pageX,e.pageY),!1}),e(t).mousemove(function(t){if(s.dragging)return s.domDrag(t.pageX,t.pageY),e("body").css({cursor:"pointer"})}).mouseup(function(t){if(s.dragging)return s.dragging=!1,s.dragger.removeClass("dragging"),e("body").css({cursor:"auto"})}),this.pagePos=0,this.input.val()===""?(this.value=this.getRange().min,this.input.val(this.value)):this.value=this.nearestValidValue(this.input.val()),this.setSliderPositionFromValue(this.value),i=this.valueToRatio(this.value),this.input.trigger("slider:ready",{value:this.value,ratio:i,position:i*this.slider.outerWidth()})}return n.prototype.setRatio=function(e){var t;return e=Math.min(1,e),e=Math.max(0,e),t=this.ratioToValue(e),this.setSliderPositionFromValue(t),this.valueChanged(t,e,"setRatio")},n.prototype.setValue=function(e){var t;return e=this.nearestValidValue(e),t=this.valueToRatio(e),this.setSliderPositionFromValue(e),this.valueChanged(e,t,"setValue")},n.prototype.domDrag=function(e,t,n){var r,i,s;n==null&&(n=!1),r=e-this.slider.offset().left,r=Math.min(this.slider.outerWidth(),r),r=Math.max(0,r);if(this.pagePos!==r)return this.pagePos=r,i=r/this.slider.outerWidth(),s=this.ratioToValue(i),this.valueChanged(s,i,"domDrag"),this.settings.snap?this.setSliderPositionFromValue(s,n):this.setSliderPosition(r,n)},n.prototype.setSliderPosition=function(e,t){return t==null&&(t=!1),t&&this.settings.animate?this.dragger.animate({left:e},200):this.dragger.css({left:e})},n.prototype.setSliderPositionFromValue=function(e,t){var n;return t==null&&(t=!1),n=this.valueToRatio(e),this.setSliderPosition(n*this.slider.outerWidth(),t)},n.prototype.getRange=function(){return this.settings.allowedValues?{min:Math.min.apply(Math,this.settings.allowedValues),max:Math.max.apply(Math,this.settings.allowedValues)}:this.settings.range?{min:parseFloat(this.settings.range[0]),max:parseFloat(this.settings.range[1])}:{min:0,max:1}},n.prototype.nearestValidValue=function(t){var n,r,i,s;return i=this.getRange(),t=Math.min(i.max,t),t=Math.max(i.min,t),this.settings.allowedValues?(n=null,e.each(this.settings.allowedValues,function(){if(n===null||Math.abs(this-t)<Math.abs(n-t))return n=this}),n):this.settings.step?(r=(i.max-i.min)/this.settings.step,s=Math.floor((t-i.min)/this.settings.step),(t-i.min)%this.settings.step>this.settings.step/2&&s<r&&(s+=1),s*this.settings.step+i.min):t},n.prototype.valueToRatio=function(e){var t,n,r,i,s,o,u,a;if(this.settings.equalSteps){a=this.settings.allowedValues;for(i=o=0,u=a.length;o<u;i=++o){t=a[i];if(typeof n=="undefined"||n===null||Math.abs(t-e)<Math.abs(n-e))n=t,r=i}return this.settings.snapMid?(r+.5)/this.settings.allowedValues.length:r/(this.settings.allowedValues.length-1)}return s=this.getRange(),(e-s.min)/(s.max-s.min)},n.prototype.ratioToValue=function(e){var t,n,r,i,s;return this.settings.equalSteps?(s=this.settings.allowedValues.length,i=Math.round(e*s-.5),t=Math.min(i,this.settings.allowedValues.length-1),this.settings.allowedValues[t]):(n=this.getRange(),r=e*(n.max-n.min)+n.min,this.nearestValidValue(r))},n.prototype.valueChanged=function(t,n,r){var i;if(t.toString()===this.value.toString())return;return this.value=t,i={value:t,ratio:n,position:n*this.slider.outerWidth(),trigger:r},this.input.val(t).trigger(e.Event("change",i)).trigger("slider:changed",i)},n}(),e.extend(e.fn,{simpleSlider:function(){var t,r,i;return i=arguments[0],t=2<=arguments.length?__slice.call(arguments,1):[],r=["setRatio","setValue"],e(this).each(function(){var s,o;return i&&__indexOf.call(r,i)>=0?(s=e(this).data("slider-object"),s[i].apply(s,t)):(o=i,e(this).data("slider-object",new n(e(this),o)))})}}),e(function(){return e("[data-slider]").each(function(){var t,n,r,i,s,o;return t=e(this),i={},n=t.data("slider-values"),n&&(i.allowedValues=function(){var e,t,r,i;r=n.split(","),i=[];for(e=0,t=r.length;e<t;e++)o=r[e],i.push(parseFloat(o));return i}()),r=t.data("slider-range"),r&&(i.range=r.split(",")),s=t.data("slider-step"),s&&(i.step=s),i.snap=t.data("slider-snap"),i.equalSteps=t.data("slider-equal-steps"),t.simpleSlider(i)})})})(this.jQuery||this.Zepto,this);

jQuery(document).ready(function($){
	$(".field_slider").bind("slider:changed", function(event, data) {
		$(this).parent().prev().find('strong').html(data.value);
	});
});