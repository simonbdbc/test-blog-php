<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/preflight.min.css" rel="stylesheet">

    <!-- Any of your own CSS would go here -->

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/utilities.min.css" rel="stylesheet">
    <title>Blog</title>
</head>
<body>
<div class="flex flex-col">
<?php

# Fonction pour sauvergarder les données dans le fichier JSON. + redirection
function save($data)
{
    file_put_contents('db.json', json_encode($data, JSON_FORCE_OBJECT));
    header('Location: index.php');
}

# On charge les articles
$articles = json_decode(file_get_contents('db.json'), true);

# Si le formulaire d'édition a été déclenché, on modifie la valeur de content et on sauvegarde.
if (isset($_GET['content']) && isset($_GET['form_edit'])) {
    $articles[$_GET['form_edit']]['content'] = $_GET['content'];
    save($articles);
}

# Si le formulaire d'ajout a été déclenché, on ajoute au tableau d'articles une ligne et on sauvegarde.
if (isset($_GET['content']) && strlen($_GET['content']) > 3 && isset($_GET['add']) && $_GET['add'] == 1) {
    $articles[] = [
        'date' => date('Y-m-d H:i:s'),
        'content' => $_GET['content'],
    ];
    save($articles);
}

# Si une suppression est demandé, on enlève la valeur du tableau et on sauvegarde.
if (isset($_GET['delete'])) {
    unset($articles[$_GET['delete']]);
    save($articles);
}

?>
<div class="flex justify-around m-5">
<h1>Mon blog</h1>
<a href="index.php" class="bg-blue rounded-full text-white px-6 py-2">Accueil</a>
</div>
<div class="flex flex-col items-center justify-between bg-grey-lighter">
<?php
# Si un article a été séléctionné, on l'affiche
if (isset($_GET['view']) && isset($articles[$_GET['view']])) {
    echo "<div class='px-6 py-4'><h2>" . $articles[$_GET['view']]['date'] . "</h2>";
    echo "<p>" . $articles[$_GET['view']]['content'] . "</p></div>";
    ?>
    <div class="px-6 py-4">
    <?php if (!isset($_GET['view'])) {?>
        <a class="inline-block bg-blue rounded-full px-3 py-1 text-sm text-white mr-2" href="index.php?view=<?=$_GET['view']?>">Voir</a>
    <?php }?>
        <a class="inline-block bg-blue rounded-full px-3 py-1 text-sm text-white mr-2" href="index.php?edit=<?=$_GET['view']?>">Editer</a>
        <a class="inline-block bg-blue rounded-full px-3 py-1 text-sm text-white mr-2" href="index.php?delete=<?=$_GET['view']?>">Supprimer</a>
    </div>
    <?php
}
?>
</div>
<div class="flex flex-wrap m-5">
<?php
# Si aucun article n'a été séléctionné, on les liste tous.
if (!isset($_GET['view']) && !isset($_GET['edit'])) {
    
    foreach (($articles) as $index => &$article) {
        ?>

        <div class="flex flex-col justify-between m-5 max-w-sm rounded overflow-hidden shadow-lg bg-grey-lighter">
        <div class="px-6 py-4">
          <div class="text-s mb-2">
          <h2><?=$article['date']?></h2>
          </div>
          <p><?=$article['content']?></p>
        </div>
        <div class="px-6 py-4">
            <a class="inline-block bg-blue rounded-full px-3 py-1 text-sm text-white mr-2" href="index.php?view=<?=$index?>">Voir</a>
            <a class="inline-block bg-blue rounded-full px-3 py-1 text-sm text-white mr-2" href="index.php?edit=<?=$index?>">Editer</a>
            <a class="inline-block bg-blue rounded-full px-3 py-1 text-sm text-white mr-2" href="index.php?delete=<?=$index?>">Supprimer</a>
        </div>
        </div>

        <?php
    }}
?>
</div>


<div class="flex flex-col items-center bg-grey-lighter">
<hr>

<?php 
if (!isset($_GET['view'])) {
    # Si aucune demande d'édition n'a été déclenchée, on affiche le formulaire d'ajout
    if (!isset($_GET['edit'])) {?>
    <h2 class="m-5">Ajouter un article</h2>
    <form action="" method="get">
        <textarea name="content" class="m-5" required></textarea><br/>
        <button name="add" value="1" type="submit" class="m-5 bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded">Ajouter</button>
    </form>

    <?php } 
    # Si une demande d'édition a été déclenchée, on affiche le formulaire d'édition
    else {?>
        <h2 class="m-5">Modifier l'article <?=$articles[$_GET['edit']]['date']?></h2>
        <form action="" method="get">
        <textarea class="m-5" name="content" required><?=$articles[$_GET['edit']]['content']?></textarea><br/>
        <button class="m-5 bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded" name="form_edit" value="<?=$_GET['edit']?>" type="submit">Modifier</button>
    </form>
<?php }}?>
</div>
</div>
</body>
</html>