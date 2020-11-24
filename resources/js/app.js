require('./bootstrap');

const confirmBookmarkDeleteButtons = document.querySelectorAll('.confirm-bookmark-delete');

confirmBookmarkDeleteButtons.forEach(el => el.addEventListener('click', event => {
    event.preventDefault();
    const deleteBookmark = confirm('Are you sure you want to delete this bookmark?');

    if (deleteBookmark) {
        // get specific form based on delete button attribute
        const bookmarkId = event.target.dataset.bookmarkId;
        const deleteForm = document.querySelector('#delete-bookmark-form-' + bookmarkId);

        deleteForm.submit();
    }
}));


const confirmUserDeleteButtons = document.querySelectorAll('.confirm-user-delete');

confirmUserDeleteButtons.forEach(el => el.addEventListener('click', event => {
    event.preventDefault();
    const deleteuser = confirm('Are you sure you want to delete this user?');

    if (deleteuser) {
        // get specific form based on delete button attribute
        const userId = event.target.dataset.userId;
        const deleteForm = document.querySelector('#delete-user-form-' + userId);

        deleteForm.submit();
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


