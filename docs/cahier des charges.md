# Cahier des charges #

## site d'inscription - Taekwonkido Phénix ##



### Objectif ###
créer un site en php/mysql (avec le framework symfony) pour permettre aux élèves de s'inscrire pour adhérer au club. Le site devra permettre le paiement en ligne. 
A terme le site devra aussi permettre l'inscription aux différents événements (stage, passage de grade, repas, déplacement, …)

### Description ###

#### Adhésion (pour devenir adhérent au club) ####

On appellera "utilisateur" une personne qui souhaite s'inscrire au club ou inscrire ses enfants. L'utilisateur aura un compte pour accéder au site.
On appellera "adhérent" toute personne inscrite au club

- Le site doit permettre aux utilisateurs souhaitant adhérer de s'inscrire au club via un formulaire d'inscription.
- L'utilisateur doit pouvoir se créer un compte au site pour lui permettre de s'inscrire lui-même (s'il est adulte) ou d'inscrire ses enfants (s'il est parent)
- son compte lui permet de voir l'état d'avancement de son adhésion (en cours, payé, en attente validation, validée)
- le formulaire de création de compte doit demander les informations suivantes : nom, prénom, email, mot de passe
- le formulaire d'adhésion doit demander les informations suivantes : nom, prénom, email, date de naissance, sexe, rue, code postal, ville, nationalité, téléphone, téléphone en cas d'accident
- ensuite, le formulaire d'inscription doit proposer les différents tarifs accessibles (en fonction de ce qui est aura été configuré) et permettre le paiement en ligne
- Par défaut, les nom/prénom de la 1ère inscription correspondent aux nom/prénom de l'utilisateur
- ensuite, par défaut les nom/prénoms/adresse/email/tel/tel_accident des autres inscriptions sont ceux de la 1ère inscription
- Un utilisateur doit pouvoir voir les inscriptions des saisons passées, pour pouvoir réinscrire facilement les mêmes personnes

Attention : 
Lorsque j'inscris des adhérents, entre septembre et décembre, ils vont être enregistrés dans la table de la saison N-N+1
A partir du mois de juin(date configurable), on peut commencer à s'inscrire pour la saison suivante (donc N+1-N+2). Les adhérents seront alors inscrits dans la table de la saison N+1_N+2, sans interférer avec la table de la saison N_N+1
Au 01/09 (date configurable), on change de saison => la table de la saison courante deviendra alors N+1_N+2


#### Accès au back-office - rôles ####
Il doit y avoir un système de rôles pour accéder au back-office :
- administrateur (accès en lecture/écriture)
- consultation (accès en lecture)
- adhérent (aucun accès)

les administrateurs ont tout pouvoir : 
- création/suppression/modification des utilisateurs
- création/suppression/modification des adhérents
- configurer les tarifs
- configurer les événements


#### Tarifs ####
Les tarifs doivent être configurables par un administrateur pour une saison donnée.
La saison court du 01/01/N au 31/08/N+1
habituellement :
- tarif enfant (jusqu'à 12 ans)
- tarif adulte (à partir de 13 ans)
- tarif "ancien élève enfant"
- tarif "ancien élève adulte"
- possibilité de payer en 3x
- réduction possible pour certaines catégories d'élèves (ex : anciens élèves) 

Les adhésions sont possible du 01/06 au 01/12 (les dates doivent être configurables)


#### Vision des adhérents dans le back-office ####
Les administrateurs doivent pouvoir créer/supprimer/modifier des adhérents.
Il faudrait que le tableau soit configurable, c'est-à-dire qu'on puisse rajouter des colonnes en fonction des besoins : exemple "commande dobok", "commande passeport", …


## Architecture base de données ##
une table users :
- userId
- lastname
- firstname
- email
- password
- role (administrateur, consultation, adhérent)
- responsibleOfAdherentid

une table adherents_N_M+1 (avec N année en cours et N+1 année prochaine ; ex adherents_2021_2022)
- adherentId
- lastname
- firstname
- sex
- birthdate
- email
- street
- postalCode
- city
- nationality
- phone
- emergencyPhone
- status (élève, président, trésorier, secrétaire, professeur, assistant)
- adhesionState (en attente paiement, en attente validation, validée)
- medicalCertificate
- Photo
- comment
- grade


état de l'adhésion : 
- en attente de paiement : lorsque l'utilisateur a inscrit un adhérent mais pas encore payé
- en attente validation : lorsque le paiement est ok et on attend la validation du bureau (pour vérifier les pièces du dossier comme le certificat médical)
- validée : le dossier est complet

Un user peut être responsable de 1 à N adherents

entre 01/09/N et 31/08/N+1 => on utilise la table adherents_N_N+1


## Etape 2 - inscriptions aux événements ##
Dans un 2ème temps, il faudrait donner la possibilité de s'inscrire à différents événements (stage, compétition, passage de grade, déplacement, repas, …) et de payer en ligne avec des tarifs configurables


## Etape 3 - améliorer le front-office pour un faire le site principal du club ##
Améliorer le front-office pour y intégrer le site actuel https://phenix.cenaclerm.fr et pourquoi pas le faire en Wordpress ? A voir


