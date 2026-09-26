<?php
require __DIR__.'/includes/bootstrap.php';
$title='Voyalo — Votre prochaine escapade commence ici';
$cities=$pdo->query("SELECT ville,COUNT(*) hotels FROM hotels WHERE actif=1 GROUP BY ville ORDER BY hotels DESC LIMIT 4")->fetchAll();
$popular=$pdo->query("SELECT h.id,h.nom,h.ville,h.etoiles,h.image_principale,MIN(r.prix_nuit) prix FROM hotels h JOIN rooms r ON r.hotel_id=h.id WHERE h.actif=1 AND r.disponible=1 GROUP BY h.id ORDER BY h.etoiles DESC LIMIT 3")->fetchAll();
require __DIR__.'/includes/header.php';
?>
<section class="voy-hero text-white">
  <div class="max-w-7xl mx-auto px-4 pt-14 pb-24 md:pt-20 md:pb-28">
    <p class="uppercase tracking-[.22em] text-teal-100 text-xs md:text-sm font-bold">L'Afrique en mouvement</p>
    <h1 class="text-4xl md:text-6xl font-black max-w-3xl mt-4 leading-tight">Partez l'esprit libre.<br><span class="text-orange-300">On s'occupe du voyage.</span></h1>
    <p class="mt-5 text-lg text-teal-50 max-w-2xl">Trouvez où dormir, comment vous déplacer et quelle expérience vivre. Réservez simplement, payez avec vos moyens habituels.</p>

    <div class="voy-search-wrap mt-10">
      <div class="flex flex-wrap gap-2" role="tablist" aria-label="Type de réservation">
        <button type="button" class="voy-tab active" data-search-tab="hotel" role="tab" aria-selected="true">⌂ <span>Séjours</span></button>
        <button type="button" class="voy-tab" data-search-tab="ticket" role="tab" aria-selected="false">➜ <span>Billets</span></button>
        <button type="button" class="voy-tab" data-search-tab="package" role="tab" aria-selected="false">✦ <span>Circuits</span></button>
      </div>
      <div class="voy-search-card">
        <form data-search-panel="hotel" action="/hotels.php" method="get" class="voy-search-grid">
          <div class="voy-search-field"><label for="dest-hotel">Destination</label><input id="dest-hotel" name="ville" placeholder="Ville ou destination" autocomplete="address-level2"></div>
          <div class="voy-search-field"><label for="date-in">Arrivée</label><input id="date-in" type="date" name="arrivee" min="<?=date('Y-m-d')?>"></div>
          <div class="voy-search-field"><label for="date-out">Départ</label><input id="date-out" type="date" name="depart" min="<?=date('Y-m-d')?>"></div>
          <div class="voy-search-field"><label for="guests">Voyageurs</label><input id="guests" type="number" name="personnes" value="2" min="1" max="20"></div>
          <button class="voy-search-submit">Rechercher</button>
        </form>
        <form data-search-panel="ticket" action="/billets.php" method="get" class="voy-search-grid" hidden>
          <div class="voy-search-field"><label for="origin">Départ de</label><input id="origin" name="origine" placeholder="Douala" required></div>
          <div class="voy-search-field"><label for="destination">Destination</label><input id="destination" name="destination" placeholder="Yaoundé" required></div>
          <div class="voy-search-field"><label for="travel-date">Date du départ</label><input id="travel-date" type="date" name="date" min="<?=date('Y-m-d')?>"></div>
          <button class="voy-search-submit">Voir les départs</button>
        </form>
        <form data-search-panel="package" action="/forfaits.php" method="get" class="voy-search-package" hidden>
          <div><strong>Envie d'une escapade ?</strong><p>Parcourez nos circuits guidés et choisissez votre prochaine aventure.</p></div>
          <button class="voy-search-submit">Découvrir les circuits</button>
        </form>
      </div>
    </div>
    <p class="mt-4 text-sm text-teal-100">Paiements Mobile Money · Prix affichés en FCFA · Assistance locale</p>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 -mt-12 relative z-10">
  <div class="grid sm:grid-cols-3 gap-3">
    <article class="voy-proof"><span class="voy-proof-icon">✓</span><div><h2>Réservation confirmée</h2><p>Retrouvez tous vos voyages au même endroit.</p></div></article>
    <article class="voy-proof"><span class="voy-proof-icon">FC</span><div><h2>Paiement adapté</h2><p>Orange Money, MTN MoMo ou carte.</p></div></article>
    <article class="voy-proof"><span class="voy-proof-icon">⌖</span><div><h2>Pensé pour le Cameroun</h2><p>Des destinations proches et lointaines.</p></div></article>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 pt-16">
  <div class="flex justify-between items-end gap-4"><div><p class="section-kicker">À deux pas de chez vous</p><h2 class="section-title">Où vous emmène le prochain week-end ?</h2><p class="text-slate-600 mt-2">Des idées d'escapades pour commencer à rêver.</p></div><a class="text-teal-800 font-bold whitespace-nowrap" href="/hotels.php">Voir les hôtels →</a></div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-7">
    <?php $destinations=[['Kribi','Cameroun','photo-1500375592092-40eb2168fd21'],['Douala','Cameroun','photo-1519501025264-65ba15a82390'],['Yaoundé','Cameroun','photo-1519608487953-e999c86e7455'],['Bafoussam','Cameroun','photo-1519681393784-d120267933ba']]; foreach($destinations as $i=>$d): $count=0; foreach($cities as $c) if(strtolower($c['ville'])===strtolower($d[0])) $count=(int)$c['hotels']; ?>
      <a href="/hotels.php?ville=<?=rawurlencode($d[0])?>" class="voy-destination"><img loading="lazy" src="https://images.unsplash.com/<?=$d[2]?>?auto=format&fit=crop&w=900&q=80" alt="Paysage à <?=e($d[0])?>"><span class="voy-destination-shade"></span><div><h3><?=e($d[0])?></h3><p><?=$count?> hébergement<?=$count===1?'':'s'?> · <?=e($d[1])?></p></div></a>
    <?php endforeach; ?>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-16">
  <div class="flex justify-between items-end gap-4"><div><p class="section-kicker">Sélection Voyalo</p><h2 class="section-title">Des séjours à découvrir</h2></div><a class="text-teal-800 font-bold whitespace-nowrap" href="/hotels.php">Explorer les hébergements →</a></div>
  <div class="grid md:grid-cols-3 gap-5 mt-7">
    <?php foreach($popular as $h): ?><article class="card voy-property"><a href="/hotel-detail.php?id=<?=(int)$h['id']?>"><img loading="lazy" src="<?=e($h['image_principale']?:'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=80')?>" alt="Hôtel <?=e($h['nom'])?>"><div class="p-5"><div class="flex justify-between gap-3"><div><h3 class="font-bold text-lg"><?=e($h['nom'])?></h3><p class="text-slate-500 text-sm mt-1"><?=e($h['ville'])?> · <?=str_repeat('★',(int)$h['etoiles'])?></p></div><span class="voy-rating">Voyalo<br><b>Choix</b></span></div><p class="mt-5 text-sm text-slate-600">À partir de</p><p class="text-xl font-black text-slate-900"><?=money($h['prix'])?><span class="text-sm font-normal text-slate-500"> / nuit</span></p><span class="btn mt-4">Voir les disponibilités</span></div></a></article><?php endforeach; ?>
    <?php if(!$popular): ?><div class="card p-6 md:col-span-3"><h3 class="font-bold">Vos prochaines adresses arrivent bientôt.</h3><p class="text-slate-600 mt-2">Notre équipe prépare une belle sélection d'hébergements pour vos escapades.</p></div><?php endif; ?>
  </div>
</section>

<section class="voy-band"><div class="max-w-7xl mx-auto px-4 py-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-5"><div><p class="section-kicker text-orange-200">À vous de choisir</p><h2 class="text-2xl md:text-3xl font-black text-white">Un trajet, une chambre ou toute une aventure.</h2><p class="text-teal-100 mt-2">Organisez votre prochain départ avec Voyalo.</p></div><div class="flex gap-3"><a href="/billets.php" class="btn bg-white text-teal-900 hover:bg-teal-50">Trouver un billet</a><a href="/forfaits.php" class="btn bg-orange-500 hover:bg-orange-600">Voir les circuits</a></div></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
