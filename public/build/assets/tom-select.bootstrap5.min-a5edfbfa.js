const m="modulepreload",h=function(i){return"/build/"+i},a={},k=function(l,o,u){if(!o||o.length===0)return l();const c=document.getElementsByTagName("link");return Promise.all(o.map(e=>{if(e=h(e),e in a)return;a[e]=!0;const n=e.endsWith(".css"),f=n?'[rel="stylesheet"]':"";if(!!u)for(let r=c.length-1;r>=0;r--){const s=c[r];if(s.href===e&&(!n||s.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${e}"]${f}`))return;const t=document.createElement("link");if(t.rel=n?"stylesheet":m,n||(t.as="script",t.crossOrigin=""),t.href=e,document.head.appendChild(t),n)return new Promise((r,s)=>{t.addEventListener("load",r),t.addEventListener("error",()=>s(new Error(`Unable to preload CSS for ${e}`)))})})).then(()=>l())};export{k as _};