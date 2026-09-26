<?php
require __DIR__.'/../includes/bootstrap.php';
$hasAdmin=(bool)$pdo->query("SELECT 1 FROM users WHERE role='admin' LIMIT 1")->fetchColumn();
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'&&!$hasAdmin){
    verify_csrf();
    $name=trim($_POST['nom']??'');$email=filter_var(strtolower(trim($_POST['email']??'')),FILTER_VALIDATE_EMAIL);$phone=trim($_POST['telephone']??'');$password=$_POST['password']??'';$confirm=$_POST['password_confirmation']??'';
    if(!$name||!$email||strlen($password)<12||$password!==$confirm){$error='Vérifiez les champs. Le mot de passe doit contenir au moins 12 caractères et les deux saisies doivent correspondre.';}
    else{
        $lock=$pdo->query("SELECT GET_LOCK('voyalo_first_admin_setup', 10)")->fetchColumn();
        if((int)$lock!==1){$error='La configuration est temporairement occupée. Réessayez dans un instant.';}
        else{
            try{
                if($pdo->query("SELECT 1 FROM users WHERE role='admin' LIMIT 1")->fetchColumn()){$hasAdmin=true;$error='Un compte administrateur existe déjà. Connectez-vous avec ce compte.';}
                else{$stmt=$pdo->prepare("INSERT INTO users(nom,email,password,telephone,role) VALUES(?,?,?,?, 'admin')");$stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$phone]);session_regenerate_id(true);$_SESSION['user']=['id'=>(int)$pdo->lastInsertId(),'nom'=>$name,'email'=>$email,'role'=>'admin'];redirect('/admin/index.php');}
            }catch(PDOException $e){$error='Impossible de créer le compte avec ces informations. Vérifiez notamment que cet email n’est pas déjà utilisé.';}
            finally{$pdo->query("SELECT RELEASE_LOCK('voyalo_first_admin_setup')");}
        }
    }
}
$title='Configuration du compte administrateur';require __DIR__.'/../includes/header.php';
?>
<section class="max-w-5xl mx-auto px-4 py-12"><div class="auth-layout !max-w-5xl !my-0"><aside class="auth-panel"><div><p class="uppercase tracking-[.2em] text-teal-100 text-xs font-bold">Démarrage Voyalo</p><h1 class="text-3xl font-black mt-3">Configurez l'accès administrateur.</h1><p class="text-teal-100 mt-3">Ce compte pourra gérer les hébergements, les chambres, les trajets, les départs et les circuits publiés sur Voyalo.</p></div><div class="auth-decoration"><span class="inline-flex rounded-full bg-white/15 px-4 py-2">Configuration initiale · une seule fois</span></div></aside><div class="auth-form">
<?php if($hasAdmin):?><p class="page-eyebrow">Configuration terminée</p><h2 class="text-2xl font-black mt-1">Un administrateur est déjà configuré.</h2><p class="text-slate-600 mt-3">Pour protéger l'agence, la création initiale est maintenant désactivée.</p><a class="btn w-full mt-6" href="<?=e(app_url('/login.php'))?>">Aller à la connexion</a>
<?php else:?><p class="page-eyebrow">Premier administrateur</p><h2 class="text-2xl font-black mt-1">Créer le compte principal</h2><p class="text-sm text-slate-600 mt-2">Choisissez vos identifiants d’administration et conservez-les en lieu sûr.</p>
<?php if($error):?><p class="mt-4 bg-rose-50 text-rose-800 p-3 rounded-lg" role="alert"><?=e($error)?></p><?php endif;?>
<form method="post" class="mt-6 space-y-4"><?=csrf_field()?><div><label>Nom complet</label><input name="nom" maxlength="120" required autocomplete="name"></div><div><label>Email administrateur</label><input type="email" name="email" maxlength="190" required autocomplete="email"></div><div><label>Téléphone <span class="font-normal text-slate-500">(facultatif)</span></label><input name="telephone" maxlength="30" autocomplete="tel" placeholder="6XX XXX XXX"></div><div><label>Mot de passe</label><input type="password" name="password" minlength="12" required autocomplete="new-password"><p class="text-xs text-slate-500 mt-1">12 caractères minimum.</p></div><div><label>Confirmer le mot de passe</label><input type="password" name="password_confirmation" minlength="12" required autocomplete="new-password"></div><button class="btn w-full">Créer l'administrateur</button></form><?php endif;?>
</div></div></section><?php require __DIR__.'/../includes/footer.php';?>
