const r=document.querySelector(".sidebar"),t=document.querySelector(".sidebar-backdrop"),s=document.querySelector("#sidebar-trigger"),n=document.querySelector("#sidebar-trigger-close");[t,s,n].forEach(e=>{e.addEventListener("click",()=>{r.classList.toggle("show"),t.classList.toggle("show")})});const i=document.querySelectorAll(".sidebar-nav-link");document.addEventListener("DOMContentLoaded",function(){i.forEach(e=>{e.classList.contains("active")&&(e.parentElement.parentElement.parentElement.classList.add("show"),e.parentElement.parentElement.parentElement.previousElementSibling.setAttribute("aria-expanded",!0))})});