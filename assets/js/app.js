document.addEventListener('DOMContentLoaded',()=>{
  const base=document.querySelector('meta[name="voyalo-base"]')?.content||'/';
  if(base!=='/')document.querySelectorAll('a[href],form[action],img[src]').forEach(el=>{for(const attr of ['href','action','src']){const value=el.getAttribute(attr);if(value&&value.startsWith('/')&&!value.startsWith('//'))el.setAttribute(attr,base.replace(/\/$/,'')+value);}});
  const tabs=[...document.querySelectorAll('[data-search-tab]')],panels=[...document.querySelectorAll('[data-search-panel]')];
  tabs.forEach(tab=>tab.addEventListener('click',()=>{const name=tab.dataset.searchTab;tabs.forEach(item=>{const selected=item===tab;item.classList.toggle('active',selected);item.setAttribute('aria-selected',selected?'true':'false');});panels.forEach(panel=>panel.hidden=panel.dataset.searchPanel!==name);}));
  const menu=document.querySelector('.voy-menu-button'),nav=document.querySelector('#voy-nav');if(menu&&nav)menu.addEventListener('click',()=>{const open=nav.classList.toggle('open');menu.setAttribute('aria-expanded',open?'true':'false');});
  const checkin=document.querySelector('#date-in'),checkout=document.querySelector('#date-out');if(checkin&&checkout)checkin.addEventListener('change',()=>{checkout.min=checkin.value;if(checkout.value<=checkin.value)checkout.value='';});
  document.querySelectorAll('[data-autosubmit]').forEach(el=>el.addEventListener('change',()=>el.form.requestSubmit()));
  document.querySelectorAll('[data-availability]').forEach(form=>{
    const check=async()=>{const start=form.elements.arrivee.value,end=form.elements.depart.value,out=form.querySelector('.availability');if(!start||!end||end<=start){out.textContent='';return;}out.textContent='Vérification…';try{const base=document.querySelector('meta[name="voyalo-base"]')?.content||'/';const url=new URL(base.replace(/\/$/,'')+'/api/availability.php',location.origin);url.searchParams.set('room_id',form.elements.room_id.value);url.searchParams.set('arrivee',start);url.searchParams.set('depart',end);const res=await fetch(url,{headers:{Accept:'application/json'}});const data=await res.json();out.textContent=data.available?'Disponible':'Indisponible';out.className='availability text-sm self-center '+(data.available?'text-emerald-700':'text-rose-700');}catch{out.textContent='Disponibilité non vérifiée.';}};
    form.elements.arrivee.addEventListener('change',check);form.elements.depart.addEventListener('change',check);
  });
});
