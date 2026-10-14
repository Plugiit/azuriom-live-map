# Plugiit Live Map

Plugin Azuriom qui intègre une ou plusieurs cartes web Minecraft en direct dans votre site :
BlueMap, Dynmap, squaremap, Pl3xMap ou n'importe quelle URL (Overviewer, carte maison…).

## Fonctionnalités

- Page publique `/plugiit-live-map` avec onglets (sélecteur déroulant sur mobile) pour passer d'une carte à l'autre.
- URL directe par carte : `/plugiit-live-map/{id}`.
- Iframe responsive, chargement différé (`loading="lazy"`), indicateur de chargement.
- Bouton plein écran (API Fullscreen, préfixe WebKit pris en charge) et lien « Ouvrir dans un nouvel onglet ».
- Message de repli si la carte ne charge pas (délai de 15 s), avec bouton « Réessayer » et lien direct.
- Serveur Azuriom associé optionnel : la page affiche son état et le nombre de joueurs en ligne.
- Administration (permission `plugiit-live-map.admin`) :
  - CRUD des cartes : nom, type, URL (http/https uniquement), serveur, icône Bootstrap Icons, position, activation ;
  - bouton **Tester l'URL** : le serveur web appelle la carte, vérifie qu'elle répond et analyse les en-têtes
    `X-Frame-Options` et `Content-Security-Policy: frame-ancestors`. En cas de blocage, la configuration à
    appliquer côté serveur de carte / proxy est expliquée (avec l'exemple Nginx). Le contenu mixte
    (site HTTPS, carte HTTP) est aussi signalé ;
  - réglages : hauteur, carte par défaut, bouton plein écran, lien « nouvel onglet ».
- Journal d'actions Azuriom (création / modification / suppression des cartes, réglages).
- Traductions françaises et anglaises.

## Installation

1. Copiez le dossier dans `plugins/plugiit-live-map` puis activez le plugin dans **Admin › Extensions**
   (les migrations sont lancées à l'activation).
2. Donnez la permission « Gérer les cartes en direct » aux rôles concernés.
3. Ajoutez la page au menu : **Admin › Navigation › Ajouter**, type « Plugin », route « Carte du serveur ».

## Configuration

**Admin › Carte en direct › Cartes** : ajoutez vos cartes puis utilisez **Tester l'URL**.

Si le test indique que l'intégration est interdite, il faut autoriser votre site côté carte. Les serveurs web
intégrés de BlueMap, Dynmap, squaremap et Pl3xMap n'envoient pas ces en-têtes par défaut ; ils viennent
généralement d'un proxy inverse ou d'un hébergeur. Exemple Nginx :

```nginx
proxy_hide_header X-Frame-Options;
add_header Content-Security-Policy "frame-ancestors 'self' https://votre-site.fr";
```

Si votre site est en HTTPS, la carte doit l'être aussi, sinon le navigateur bloque l'iframe.

**Admin › Carte en direct › Paramètres** : hauteur (limitée à 75 % de l'écran sur mobile), carte par défaut,
bouton plein écran, lien nouvel onglet.

## Widget pour les thèmes

```blade
@include('plugiit-live-map::widget')
@include('plugiit-live-map::widget', ['mapId' => 2, 'height' => 350, 'title' => false])
```

Sans `mapId`, la carte par défaut est utilisée. Rien n'est affiché s'il n'y a aucune carte active.

## URL

| Page | URL |
|---|---|
| Carte par défaut | `/plugiit-live-map` |
| Carte précise | `/plugiit-live-map/{id}` |
| Administration | `/admin/plugiit-live-map/maps`, `/admin/plugiit-live-map/settings` |
