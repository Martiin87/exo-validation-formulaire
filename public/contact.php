<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';
// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__.'/../templates');
// instanciation du moteur de template
$twig = new Environment($loader);
// traitement des données
$formData = [
    'email' => '',
    'sujet' => '',
    'message' => '',
];
$errors = [];
if ($_POST) {
    foreach ($formData as $key => $value) {
        if (isset($_POST[$key])) {
            $formData[$key] = $_POST[$key];
        }
    }
    if (empty($_POST['email'])){
        $errors['email'] = 'Merci de renseigner ce champ';
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = 'Merci de renseigner un email correct';
    } elseif (strlen($_POST['email']) >= 190){
        $errors['email'] = "Merci de bien vouloir rentrer une longueur de 190 caracères inclus";
    } 
    if (empty($_POST['sujet'])){
        $errors['sujet'] = 'Veuillez remplir la section sujet';
    } elseif (strlen($_POST['sujet']) < 3 || strlen($_POST['sujet']) > 190){
        $errors['sujet'] = 'Pouvez-vous remplir la section sujet avec une longueur comprise entre 3 et 190 caractères ?';
    } elseif (preg_match("/<[^>]*>/", $_POST['message']) !== 0) {
        $errors['message'] = 'Merci de ne pas rentrer du code HTML';
    }
    if (empty($_POST['message'])){
        $errors['message'] = 'Pouvez-vous entrer votre message ?';
    } elseif (strlen($_POST['message']) < 3 || strlen($_POST['message']) > 1001){
        $errors['message'] = 'Veuillez remplir votre sujet entre 3 et 1000 caractères'; 
    } elseif (preg_match("/<[^>]*>/", $_POST['message']) !== 0) {
        $errors['message'] = 'Merci de ne pas rentrer du code HTML';
    } 
}
// affichage du rendu d'un template
echo $twig->render('contact.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'formData' => $formData,
]);