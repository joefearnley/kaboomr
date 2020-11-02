require('./bootstrap');

const confirmBookmarkDeleteButtons = document.querySelectorAll('.confirm-bookmark-delete');
const deleteBookmarkForm = document.querySelector('.delete-bookmark');

confirmBookmarkDeleteButtons.forEach(el => el.addEventListener('click', event => {
    event.preventDefault();
    const deleteBookmark = confirm('Are you sure you want to delete this bookmark?');

    if (deleteBookmark) {
        deleteBookmarkForm.submit();
    }
}));


const searchFormInput = document.querySelector('#search-input');
const searchFormButton = document.querySelector('#search-button');

if(typeof(searchFormInput) != 'undefined' && searchFormInput != null) {
    searchFormButton.addEventListener('click', event => {
        event.preventDefault();
        const searchTerm = searchFormInput.value;
        location.href = `/bookmarks/search/${searchTerm}`;
    });
}