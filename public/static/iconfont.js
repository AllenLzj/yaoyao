(function(window){var svgSprite='<svg><symbol id="icon-bianji" viewBox="0 0 1024 1024"><path d="M343.3 560.3c-0.6 0.8-1.2 1.6-1.4 2.5l-45.1 170.9c-2.6 10 0.1 20.7 7.2 28.2 5.3 5.4 12.3 8.3 19.8 8.3 2.5 0 4.9-0.3 7.4-1l164.1-46.3c0.3 0 0.4 0.2 0.6 0.2 1.9 0 3.8-0.7 5.1-2.2l438.8-453.6c13-13.5 20.2-31.9 20.2-51.9 0-22.7-9.3-45.3-25.6-62.1L893 110.6c-16.3-16.8-38.2-26.5-60.1-26.5-19.3 0-37.1 7.4-50.2 20.9L343.9 558.8c-0.4 0.4-0.3 1-0.6 1.5z m553.6-337.2l-43.6 45-70.6-74.2 43-44.4c6.8-7.1 20-6 27.8 2.1l41.5 42.9c4.3 4.5 6.8 10.4 6.8 16.3-0.2 4.7-1.9 9.1-4.9 12.3zM421.3 567.5L738 240.1l70.7 74.3-316.1 326.7-71.3-73.6m-57.7 132.7l22.9-86.8 61 63.1-83.9 23.7m561.1-297.4c-16.6 0-30.2 14-30.3 31.4v422.9c0 22.2-17.4 40.2-38.9 40.2h-692c-21.5 0-38.9-18-38.9-40.2V166.8c0-22.2 17.5-40.2 38.9-40.2h445.7c16.7 0 30.3-14 30.3-31.3 0-17.2-13.6-31.3-30.3-31.3H158.9C106.6 64 64 108 64 162.1V862c0 54.1 42.6 98.1 94.9 98.1h701.2c52.3 0 94.9-44 94.9-98.1V434c-0.1-17.2-13.7-31.2-30.3-31.2z"  ></path></symbol></svg>';var script=function(){var scripts=document.getElementsByTagName("script");return scripts[scripts.length-1]}();var shouldInjectCss=script.getAttribute("data-injectcss");var ready=function(fn){if(document.addEventListener){if(~["complete","loaded","interactive"].indexOf(document.readyState)){setTimeout(fn,0)}else{var loadFn=function(){document.removeEventListener("DOMContentLoaded",loadFn,false);fn()};document.addEventListener("DOMContentLoaded",loadFn,false)}}else if(document.attachEvent){IEContentLoaded(window,fn)}function IEContentLoaded(w,fn){var d=w.document,done=false,init=function(){if(!done){done=true;fn()}};var polling=function(){try{d.documentElement.doScroll("left")}catch(e){setTimeout(polling,50);return}init()};polling();d.onreadystatechange=function(){if(d.readyState=="complete"){d.onreadystatechange=null;init()}}}};var before=function(el,target){target.parentNode.insertBefore(el,target)};var prepend=function(el,target){if(target.firstChild){before(el,target.firstChild)}else{target.appendChild(el)}};function appendSvg(){var div,svg;div=document.createElement("div");div.innerHTML=svgSprite;svgSprite=null;svg=div.getElementsByTagName("svg")[0];if(svg){svg.setAttribute("aria-hidden","true");svg.style.position="absolute";svg.style.width=0;svg.style.height=0;svg.style.overflow="hidden";prepend(svg,document.body)}}if(shouldInjectCss&&!window.__iconfont__svg__cssinject__){window.__iconfont__svg__cssinject__=true;try{document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>")}catch(e){console&&console.log(e)}}ready(appendSvg)})(window)