import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';





//edit label commande_Type

document.addEventListener("DOMContentLoaded", function () {
    var label = document.querySelector('label[for="commande_Type"]');
    if (label) {
    label.textContent = "Choisissez votre plat"; // Modifiez ceci pour changer le texte
    }
    });