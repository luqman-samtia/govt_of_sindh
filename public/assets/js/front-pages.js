!function(e){e(["jquery"],(function(e){return function(){function t(t,n){return t||(t=a()),(c=e("#"+t.containerId)).length||n&&(c=function(t){return c=e("<div/>").attr("id",t.containerId).addClass(t.positionClass),c.appendTo(e(t.target)),c}(t)),c}function n(t){for(var n=c.children(),o=n.length-1;o>=0;o--)s(e(n[o]),t)}function s(t,n,s){var o=!(!s||!s.force)&&s.force;return!(!t||!o&&0!==e(":focus",t).length||(t[n.hideMethod]({duration:n.hideDuration,easing:n.hideEasing,complete:function(){r(t)}}),0))}function o(e){u&&u(e)}function i(n){function s(e){return null==e&&(e=""),e.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}function i(t){var n=t&&!1!==f.closeMethod?f.closeMethod:f.hideMethod,s=t&&!1!==f.closeDuration?f.closeDuration:f.hideDuration,i=t&&!1!==f.closeEasing?f.closeEasing:f.hideEasing;if(!e(":focus",v).length||t)return clearTimeout(b.intervalId),v[n]({duration:s,easing:i,complete:function(){r(v),clearTimeout(h),f.onHidden&&"hidden"!==D.state&&f.onHidden(),D.state="hidden",D.endTime=new Date,o(D)}})}function u(){(f.timeOut>0||f.extendedTimeOut>0)&&(h=setTimeout(i,f.extendedTimeOut),b.maxHideTime=parseFloat(f.extendedTimeOut),b.hideEta=(new Date).getTime()+b.maxHideTime)}function p(){clearTimeout(h),b.hideEta=0,v.stop(!0,!0)[f.showMethod]({duration:f.showDuration,easing:f.showEasing})}function m(){var e=(b.hideEta-(new Date).getTime())/b.maxHideTime*100;O.width(e+"%")}var f=a(),g=n.iconClass||f.iconClass;if(void 0!==n.optionsOverride&&(f=e.extend(f,n.optionsOverride),g=n.optionsOverride.iconClass||g),!function(e,t){if(e.preventDuplicates){if(t.message===l)return!0;l=t.message}return!1}(f,n)){d++,c=t(f,!0);var h=null,v=e("<div/>"),C=e("<div/>"),w=e("<div/>"),O=e("<div/>"),T=e(f.closeHtml),b={intervalId:null,hideEta:null,maxHideTime:null},D={toastId:d,state:"visible",startTime:new Date,options:f,map:n};return n.iconClass&&v.addClass(f.toastClass).addClass(g),function(){if(n.title){var e=n.title;f.escapeHtml&&(e=s(n.title)),C.append(e).addClass(f.titleClass),v.append(C)}}(),function(){if(n.message){var e=n.message;f.escapeHtml&&(e=s(n.message)),w.append(e).addClass(f.messageClass),v.append(w)}}(),f.closeButton&&(T.addClass(f.closeClass).attr("role","button"),v.prepend(T)),f.progressBar&&(O.addClass(f.progressClass),v.prepend(O)),f.rtl&&v.addClass("rtl"),f.newestOnTop?c.prepend(v):c.append(v),function(){var e="";switch(n.iconClass){case"toast-success":case"toast-info":e="polite";break;default:e="assertive"}v.attr("aria-live",e)}(),v.hide(),v[f.showMethod]({duration:f.showDuration,easing:f.showEasing,complete:f.onShown}),f.timeOut>0&&(h=setTimeout(i,f.timeOut),b.maxHideTime=parseFloat(f.timeOut),b.hideEta=(new Date).getTime()+b.maxHideTime,f.progressBar&&(b.intervalId=setInterval(m,10))),f.closeOnHover&&v.hover(p,u),!f.onclick&&f.tapToDismiss&&v.click(i),f.closeButton&&T&&T.click((function(e){e.stopPropagation?e.stopPropagation():void 0!==e.cancelBubble&&!0!==e.cancelBubble&&(e.cancelBubble=!0),f.onCloseClick&&f.onCloseClick(e),i(!0)})),f.onclick&&v.click((function(e){f.onclick(e),i()})),o(D),f.debug&&console&&console.log(D),v}}function a(){return e.extend({},{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,closeMethod:!1,closeDuration:!1,closeEasing:!1,closeOnHover:!0,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",escapeHtml:!1,target:"body",closeHtml:'<button type="button">&times;</button>',closeClass:"toast-close-button",newestOnTop:!0,preventDuplicates:!1,progressBar:!1,progressClass:"toast-progress",rtl:!1},m.options)}function r(e){c||(c=t()),e.is(":visible")||(e.remove(),e=null,0===c.children().length&&(c.remove(),l=void 0))}var c,u,l,d=0,p={error:"error",info:"info",success:"success",warning:"warning"},m={clear:function(e,o){var i=a();c||t(i),s(e,i,o)||n(i)},remove:function(n){var s=a();return c||t(s),n&&0===e(":focus",n).length?void r(n):void(c.children().length&&c.remove())},error:function(e,t,n){return i({type:p.error,iconClass:a().iconClasses.error,message:e,optionsOverride:n,title:t})},getContainer:t,info:function(e,t,n){return i({type:p.info,iconClass:a().iconClasses.info,message:e,optionsOverride:n,title:t})},options:{},subscribe:function(e){u=e},success:function(e,t,n){return i({type:p.success,iconClass:a().iconClasses.success,message:e,optionsOverride:n,title:t})},version:"2.1.4",warning:function(e,t,n){return i({type:p.warning,iconClass:a().iconClasses.warning,message:e,optionsOverride:n,title:t})}};return m}()}))}("function"==typeof define&&define.amd?define:function(e,t){"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):window.toastr=t(window.jQuery)}),toastr.options={closeButton:!0,debug:!1,newestOnTop:!1,progressBar:!0,positionClass:"toast-top-right",preventDuplicates:!1,onclick:null,showDuration:"300",hideDuration:"1000",timeOut:"5000",extendedTimeOut:"1000",showEasing:"swing",hideEasing:"linear",showMethod:"fadeIn",hideMethod:"fadeOut"},$(document).ready((function(){$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}})})),$(document).on("submit","#contactEnquiryForm",(function(e){e.preventDefault(),$.ajax({url:route("super.admin.enquiry.store"),type:"POST",data:$(this).serialize(),success:function(e){e.success?(toastr.success(e.message),$("#contactEnquiryForm")[0].reset()):toastr.error(e.message)},error:function(e){toastr.error(e.responseJSON.message),$("#contactEnquiryForm")[0].reset()}})})),$(document).on("click","#subscribeBtn",(function(e){e.preventDefault(),$.ajax({url:route("subscribe.store"),type:"POST",data:$("#subscribe-form").serialize(),success:function(e){e.success&&(toastr.success(e.message),$("#subscribe-form")[0].reset())},error:function(e){toastr.error(e.responseJSON.message),$("#subscribe-form")[0].reset()}})})),$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$(document).on("click",".languageSelection",(function(e){e.preventDefault();let t=$(this).data("prefix-value");$.ajax({type:"POST",url:route("change.language"),data:{languageName:t},success:function(){location.reload()}})})),$(document).on("click",".js-cookie-consent-agree",(function(e){$(".js-cookie-consent").addClass("d-none")})),$(document).on("click",".js-cookie-consent-declined",(function(e){$(".js-cookie-consent").addClass("d-none"),$.ajax({type:"POST",url:route("declined.cookie"),success:function(e){},error:function(e){}})}));
