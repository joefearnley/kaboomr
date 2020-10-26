require('./bootstrap');

const confirmBookmarkDeleteButtons = document.querySelectorAll('.confirm-bookmark-delete');
const deleteBookmarkForm = document.querySelector('#delete-bookmark');

confirmBookmarkDeleteButtons.forEach(el => el.addEventListener('click', event => {
    event.preventDefault();
    const deleteBookmark = confirm('Are you sure you want to delete this bookmark?');

    if (deleteBookmark) {
        deleteBookmarkForm.submit();
    }
}));
