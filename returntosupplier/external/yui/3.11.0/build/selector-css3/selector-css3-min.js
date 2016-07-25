/*
YUI 3.11.0 (build d549e5c)
Copyright 2013 Yahoo! Inc. All rights reserved.
Licensed under the BSD License.
http://yuilibrary.com/license/
*/

YUI.add("selector-css3",function(e,t){e.Selector._reNth=/^(?:([\-]?\d*)(n){1}|(odd|even)$)*([\-+]?\d*)$/,e.Selector._getNth=function(t,n,r,i){e.Selector._reNth.test(n);var s=parseInt(RegExp.$1,10),o=RegExp.$2,u=RegExp.$3,a=parseInt(RegExp.$4,10)||0,f=[],l=e.DOM._children(t.parentNode,r),c;u?(s=2,c="+",o="n",a=u==="odd"?1:0):isNaN(s)&&(s=o?1:0);if(s===0)return i&&(a=l.length-a+1),l[a-1]===t?!0:!1;s<0&&(i=!!i,s=Math.abs(s));if(!i){for(var h=a-1,p=l.length;h<p;h+=s)if(h>=0&&l[h]===t)return!0}else for(var h=l.length-a,p=l.length;h>=0;h-=s)if(h<p&&l[h]===t)return!0;return!1},e.mix(e.Selector.pseudos,{root:function(e){return e===e.ownerDocument.documentElement},"nth-child":function(t,n){return e.Selector._getNth(t,n)},"nth-last-child":function(t,n){return e.Selector._getNth(t,n,null,!0)},"nth-of-type":function(t,n){return e.Selector._getNth(t,n,t.tagName)},"nth-last-of-type":function(t,n){return e.Selector._getNth(t,n,t.tagName,!0)},"last-child":function(t){var n=e.DOM._children(t.parentNode);return n[n.length-1]===t},"first-of-type":function(t){return e.DOM._children(t.parentNode,t.tagName)[0]===t},"last-of-type":function(t){var n=e.DOM._children(t.parentNode,t.tagName);return n[n.length-1]===t},"only-child":function(t){var n=e.DOM._children(t.parentNode);return n.length===1&&n[0]===t},"only-of-type":function(t){var n=e.DOM._children(t.parentNode,t.tagName);return n.length===1&&n[0]===t},empty:function(e){return e.childNodes.length===0},not:function(t,n){return!e.Selector.test(t,n)},contains:function(e,t){var n=e.innerText||e.textContent||"";return n.indexOf(t)>-1},checked:function(e){return e.checked===!0||e.selected===!0},enabled:function(e){return e.disabled!==undefined&&!e.disabled},disabled:function(e){return e.disabled}}),e.mix(e.Selector.operators,{"^=":"^{val}","$=":"{val}$","*=":"{val}"}),e.Selector.combinators["~"]={axis:"previousSibling"}},"3.11.0",{requires:["selector-native","selector-css2"]});
