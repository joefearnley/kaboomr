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

const search = term => {
    if (term.trim() !== '') {
        location.href = `/bookmarks/search/${term}`;
    }
};

if(typeof(searchFormInput) != 'undefined' && searchFormInput != null) {
    searchFormButton.addEventListener('click', event => {
        event.preventDefault();
        search(searchFormInput.value);
    });

    searchFormInput.addEventListener('keyup', event => {
        event.preventDefault();
        if (event.key == 'Enter') {
            search(searchFormInput.value);
        }
    });
}


