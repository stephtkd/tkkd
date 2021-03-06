/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../assets/styles/app.scss';

require('./CSS/subscription.css');

// start the Stimulus application
//import '../public/build/bootstrap';

// Jquery
const $ = require('jquery');

$ (".custom-file-input").on("change", () => {
    let filename = $ (this).val().split("\\").pop();
    $(this).siblings(".custom.file.label")
        .addClass("selected").html(filename);
});