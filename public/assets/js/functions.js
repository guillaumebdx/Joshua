/**
 *
 * @param id
 * Show or hide password on click on the eye;
 * these functions is implemented in html with onmousedown and onmouseup events
 */

function showPassword(id) {
    let target = document.getElementById(id);
    target.type='text';
}
function hidePassword(id) {
    let target = document.getElementById(id);
    target.type='password';
}