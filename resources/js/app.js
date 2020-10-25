require('./bootstrap');

// document.addEventListener('keyup', event => {

//     if (event.keyCode == 13) {
//         console.log('preventing default.....');

//         event.preventDefault();

//         if (event.target.id === 'tags') {
//             console.log(event.target.value);

//         }

//         return false;
//     }
// });


const confirmBookmarkDeleteButtons = document.querySelectorAll('.confirm-bookmark-delete');
const deleteBookmarkForm = document.querySelector('#delete-bookmark');

confirmBookmarkDeleteButtons.forEach(el => el.addEventListener('click', event => {
    event.preventDefault();
    const deleteBookmark = confirm('Are you sure you want to delete this bookmark?');

    if (deleteBookmark) {
        deleteBookmarkForm.submit();
    }
}));