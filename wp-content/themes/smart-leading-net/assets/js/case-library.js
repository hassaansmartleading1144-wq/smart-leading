(function(){
'use strict';
const root=document.querySelector('.sls-cs');if(!root)return;
const search=root.querySelector('#cs-search'),cards=Array.from(root.querySelectorAll('.sls-cs__card')),filters=Array.from(root.querySelectorAll('[data-cs-filter]')),count=root.querySelector('#cs-count'),empty=root.querySelector('#cs-empty');
if(!search||!count||!empty)return;
let active='All Projects';
function update(){const query=search.value.trim().toLowerCase();let visible=0;cards.forEach(card=>{const show=(active==='All Projects'||card.dataset.category===active)&&card.textContent.toLowerCase().includes(query);card.hidden=!show;if(show)visible++;});count.textContent=visible+' of '+cards.length+' current projects'+(active==='All Projects'?'':' · '+active);empty.hidden=visible>0;filters.forEach(button=>button.setAttribute('aria-pressed',String(button.dataset.csFilter===active)));}
filters.forEach(button=>button.addEventListener('click',()=>{active=button.dataset.csFilter;update();}));search.addEventListener('input',update);root.querySelector('#cs-reset')?.addEventListener('click',()=>{active='All Projects';search.value='';update();search.focus();});root.querySelector('#cs-controls').hidden=false;update();
})();
